<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Ambil semua akun dengan email tersebut (bisa student, teacher, atau admin)
    $users = User::where('email', $credentials['email'])->get();

    // Filter akun yang password-nya cocok
    $matchedUsers = $users->filter(fn ($user) => Hash::check($credentials['password'], $user->password))->values();

    if ($matchedUsers->isEmpty()) {
        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Jika hanya 1 peran yang cocok -> langsung login otomatis!
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
        $request->validate(['user_id' => ['required', 'integer']]);

        if (! $pending || ! in_array((int) $request->user_id, $pending['user_ids'], true)) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($request->user_id);
        $request->session()->forget('pending_role_selection');

        Auth::login($user, $pending['remember']);
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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:student,teacher'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->where(fn ($query) => $query->where('role', $request->role)),
            ],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar sebagai ' . ($request->role === 'teacher' ? 'Guru (Pengajar)' : 'Murid') . '.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

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