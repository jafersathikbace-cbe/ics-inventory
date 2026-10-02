<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $isAdminOrSuper = $user->hasAnyRole(['Admin', 'Super Admin']);

        $rules = [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'password' => ['nullable', 'string', 'min:6', 'max:100', 'confirmed'],
        ];

        // Only Admin/Super Admin can update telegram_chat_id.
        if ($isAdminOrSuper) {
            $rules['telegram_chat_id'] = ['nullable', 'string', 'max:80'];
        }

        $data = $request->validate($rules);

        // Determine whether the email address actually changed.
        $emailChanged = $user->email !== $data['email'];

        $user->name = $data['name'];
        $user->email = $data['email'];

        // Changing the email requires re-verification.
        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        if ($isAdminOrSuper) {
            $user->telegram_chat_id = $data['telegram_chat_id'] ?? null;
        } else {
            // Operators should not have telegram_chat_id.
            $user->telegram_chat_id = null;
        }

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profile updated.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}