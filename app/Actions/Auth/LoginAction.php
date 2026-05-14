<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginAction 
{
    public function execute(array $credentials, bool $remember = false) 
    {
        $user = User::whereHas('employee', function ($query) use ($credentials) {
            $query->where('email', $credentials['email']);
        })->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        Auth::login($user, $remember);

        session()->regenerate();
        return true;
    }
}
