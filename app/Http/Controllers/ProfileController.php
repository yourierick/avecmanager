<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ProjetAvec;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    //Pour l'administration (type de compte: administrateur)
    public function edit(Request $request): View
    {
        $projets = ProjetAvec::all();
        $projet_count = $projets->count();
        if ($request->user()->droits === "administrateur") {
            return view('profile.edit', [
                'user' => $request->user(), 'current_user'=>$request->user(), "projet_count"=>$projet_count,
            ]);
        }elseif ($request->user()->droits === "administrateur") {
            return view('layouts.dashboard_user_layouts.profile.edit', [
                'user' => $request->user(), 'current_user'=>$request->user(), "projet_count"=>$projet_count,
            ]);
        }else {
            return view('layouts.dashboard_guest_layouts.profile.edit', [
                'user' => $request->user(), 'current_user'=>$request->user(), "projet_count"=>$projet_count,
            ]);
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $photo_init = $request->user()->photo;
        if ($request->hasFile('photo')){
            /** @var UploadedFile|null $photo */
            $image = $request->photo;
            if ($image !== null && !$image->getError()){
                $imagePath = $image->store('medias/profiles', 'public');
                if ($photo_init) {
                    Storage::disk('public')->delete($photo_init);
                }
                $request->user()->photo = $imagePath;
            }
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->nom = $request->nom;
        $request->user()->sexe = $request->sexe;
        $request->user()->adresse = $request->adresse;
        $request->user()->telephone = $request->telephone;

        $request->user()->save();

        return redirect()->back()->with("success", "le profile a été mis à jour");
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
