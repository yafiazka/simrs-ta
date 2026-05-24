<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    public string $username = '';
    public string $password = '';
    public bool $remember = false;

    protected array $rules = [
        'username' => 'required|string|min:3',
        'password' => 'required|string|min:4',
    ];

    protected array $messages = [
        'username.required' => 'Username wajib diisi.',
        'username.min' => 'Username minimal 3 karakter.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 4 karakter.',
    ];

    public function mount(): void
    {
        if (Auth::check()) {
            redirect()->intended('/admin');
        }
    }

    public function authenticate()
    {
        $this->validate();

        $credentials = [
            'username' => $this->username,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();

            // Redirect ke halaman admin/dashboard
            return redirect()->intended('/admin');
        }

        // Jika login gagal, tambahkan error ke key username & reset password field
        $this->addError('username', 'Username atau password yang Anda masukkan salah.');
        $this->password = '';
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.auth');
    }
}
