<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Returns the view containing users lists
     *
     * @param Request $request
     * @return View
     */
    public function myLists(Request $request): View
    {
        return view('user.lists', [
            'lists' => $request->user()->lists,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Validate the profile picture separately
        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();

        // Update name and email
        $user->fill($request->validated());

        // Handle email changes
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');

            // Store the file in the 'public/profile_pictures' directory
            $path = $file->store('profile_pictures', 'public');

            // Optionally delete the old picture to avoid unused files
            if ($user->profile_picture && $user->profile_picture !== 'profile_pictures/default-avatar.png') {
                Storage::disk('public')->delete($user->profile_picture);
            }


            // Save the new path to the user's profile
            $user->profile_picture = $path;
        }

        // Save user changes
        $user->save();

        // Redirect back with success status
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    /**
     * Delete the user's account.
     */
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

        return Redirect::to('/');
    }
}
