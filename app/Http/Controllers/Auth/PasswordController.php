<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required']],
            ['current_password.required' => 'Ce champs est obligatoire',
                'current_password.current_password'=> 'ancien mot de passe incorrect',
                'password.required'=>'Ce champs est obligatoire',
                'password.confirmed'=>'Les mots de passe ne correspondent pas',
                'password.min'=>'Le mot de passe doit avoir au minimum 8 caractères',
                'password.regex'=>'Le mot de passe doit contenir au moins une lettre et un chiffre',
            ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'le mot de passe a été mis à jour');
    }
}
