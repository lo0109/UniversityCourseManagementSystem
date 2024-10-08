<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // dd($request->all());
        \Log::info('Store method called');
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'userID' => ['required', 'digits:6', 'unique:'.User::class],
            'teacher' => ['required', 'boolean'],
            'email' => [ 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        \Log::info('Validation passed.');
        \Log::info('User creation called');

        $user = User::create([
            'name' => $request->name,
            'userID' => $request->userID,
            'teacher' => $request->teacher,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        \Log::info('User created successfully:', $user->toArray());

        event(new Registered($user));

        Auth::login($user);

        // return redirect(route('dashboard', absolute: false));
        return redirect()->route('dashboard');

    }
}
