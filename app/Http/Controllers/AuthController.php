<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi ketat untuk mencegah payload SQL Injection & input tidak valid
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $normalizedEmail = Str::lower(trim($credentials['email']));
        $throttleKey = 'login-attempt:' . Str::transliterate($normalizedEmail . '|' . $request->ip());

        // 2. Cek apakah sudah mencapai batas maksimal 5 kali percobaan gagal
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = (int) ceil($seconds / 60);

            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login gagal (maksimal 5 kali). Silakan coba lagi dalam {$seconds} detik (~{$minutes} menit).",
            ])->onlyInput('email');
        }

        // 3. Ambil semua akun dengan email tersebut menggunakan Eloquent ORM (PDO Prepared Statement - aman dari SQL Injection)
        $users = User::where('email', $normalizedEmail)->get();

        // 4. Tentukan durasi blokir berdasarkan peran tertinggi pada email tersebut:
        //    Admin = 5 menit (300 detik), Guru = 2 menit (120 detik), Murid = 1 menit (60 detik)
        $decaySeconds = 60;
        if ($users->contains('role', 'admin')) {
            $decaySeconds = 300;
        } elseif ($users->contains('role', 'teacher')) {
            $decaySeconds = 120;
        }

        // 5. Filter akun yang password-nya cocok
        $matchedUsers = $users->filter(
            fn ($user) => Hash::check($credentials['password'], $user->password)
        )->values();

        if ($matchedUsers->isEmpty()) {
            RateLimiter::hit($throttleKey, $decaySeconds);
            $remaining = RateLimiter::remaining($throttleKey, 5);

            $errorMessage = $remaining > 0
                ? "Email atau kata sandi yang Anda masukkan salah. Sisa percobaan Anda: {$remaining} kali."
                : "Batas 5 kali percobaan gagal tercapai. Silakan tunggu " . ($decaySeconds / 60) . " menit sebelum mencoba kembali.";

            return back()->withErrors([
                'email' => $errorMessage,
            ])->onlyInput('email');
        }

        // 6. Login berhasil -> bersihkan penghitung percobaan gagal
        RateLimiter::clear($throttleKey);

        // Jika hanya 1 peran yang cocok -> langsung login otomatis
        if ($matchedUsers->count() === 1) {
            $user = $matchedUsers->first();
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return $this->redirectByRole($user->role);
        }

        // Jika email & password kembar di 2 peran (Murid & Guru) -> simpan sementara di session untuk dipilih
        $request->session()->put('pending_role_selection', [
            'user_ids' => $matchedUsers->pluck('id')->all(),
            'remember' => $request->boolean('remember'),
        ]);

        return view('auth.select-role', ['accounts' => $matchedUsers]);
    }

    public function selectRole(Request $request)
    {
        $pending = $request->session()->get('pending_role_selection');
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'min:1'],
        ]);

        if (! $pending || ! in_array((int) $validated['user_id'], $pending['user_ids'], true)) {
            return redirect()->route('login');
        }

        $user = User::findOrFail((int) $validated['user_id']);
        $request->session()->forget('pending_role_selection');

        Auth::login($user, (bool) ($pending['remember'] ?? false));
        $request->session()->regenerate();

        return $this->redirectByRole($user->role);
    }

    protected function redirectByRole(string $role)
    {
        if ($role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($role === 'teacher') {
            return redirect()->intended(route('teacher.dashboard'));
        }

        return redirect()->intended(route('student.dashboard'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $normalizedEmail = Str::lower(trim((string) $request->input('email')));
        $request->merge(['email' => $normalizedEmail]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:student,teacher'],
            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                Rule::unique('users')->where(fn ($query) => $query->where('role', $request->role)),
            ],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar sebagai ' . ($request->role === 'teacher' ? 'Guru (Pengajar)' : 'Murid') . '.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        return redirect()->route('student.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}