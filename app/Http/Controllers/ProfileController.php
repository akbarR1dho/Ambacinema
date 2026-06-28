<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Show the profile edit view.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information (name and email).
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        $this->userRepo->update($user->id, $validated);

        return back()->with('success', 'Profile information updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validated = $request->validated();

        $this->userRepo->update($request->user()->id, [
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
