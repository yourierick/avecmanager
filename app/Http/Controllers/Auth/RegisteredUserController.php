<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ProjetAvec;
use App\Models\User;
use GuzzleHttp\Psr7\UploadedFile;
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
        $current_user = Auth::user();
        $projets = ProjetAvec::all();
        $projet_count = $projets->count();
        return view('auth.register', ['current_user'=>$current_user, 'projets'=>$projets, 'projet_count'=>$projet_count]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'droits' => ['required'],
        ]);

        $droit_utilisateur = $request->get('droits');
        if ($droit_utilisateur === "administrateur") {
            $request->validate([
                'photo'=>['max:5000'],
                'nom' => ['required', 'string', 'max:255'],
                'sexe' => ['required'],
                'telephone' => ['required', 'unique:'.User::class],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', Rules\Password::defaults()],
            ]);

            $imagePath = "";
            if ($request->hasFile('photo')){
                /** @var UploadedFile|null $photo */
                $image = $request->photo;
                $imagePath = $image->store('medias/profiles', 'public');
            }

            $user = User::create([
                'photo' => $imagePath,
                'nom' => $request->get("nom"),
                'sexe' => $request->get("sexe"),
                'adresse'=>$request->get("adresse"),
                'telephone'=>$request->get("telephone"),
                'email' => $request->get("email"),
                'password' => Hash::make($request->get("password")),
                'droits'=>$request->get("droits"),
            ]);

            return redirect(route('manageprofile.list_users', absolute: false));
        }elseif ($droit_utilisateur === "utilisateur") {
            $request->validate([
                'photo'=>['max:5000'],
                'nom' => ['required', 'string', 'max:255'],
                'sexe' => ['required'],
                'telephone' => ['required', 'unique:'.User::class],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', Rules\Password::defaults()],
                'fonction'=>['required'],
                'projet_id'=>['required'],
            ]);

            if ($request->get('fonction') === 'animateur') {
                $request->validate([
                    'superviseur_id'=>['required'],
                ]);

                $imagePath = "";
                if ($request->hasFile('photo')){
                    /** @var UploadedFile|null $photo */
                    $image = $request->photo;
                    $imagePath = $image->store('medias/profiles', 'public');
                }

                $projet_id = $request->get('projet_id');
                $agent = User::create([
                    'photo' => $imagePath,
                    'nom' => $request->get("nom"),
                    'sexe' => $request->get("sexe"),
                    'adresse' => $request->get("adresse"),
                    'telephone' => $request->get("telephone"),
                    'email' => $request->get("email"),
                    'password' => Hash::make($request->get("password")),
                    'projet_id' => $projet_id,
                    'fonction' => $request->get("fonction"),
                    'droits' => "utilisateur",
                    'superviseur_id'=>$request->get('superviseur_id'),
                ]);
                $message = "l'agent a été ajouté avec succès";

                return redirect()->back()->with('success', $message);
            }else {
                $projet_id = $request->get('projet_id');

                if ($request->get('fonction') === "chef de projet" || $request->get('fonction') === "coordinateur du projet" || $request->get('fonction') === "assistant suivi et évaluation") {
                    $scan = User::where('projet_id', $projet_id)->where('fonction', $request->get('fonction'))->first();

                    if (!is_null($scan)){
                        $message = "ce projet a déjà un ".$request->get("fonction");
                        return redirect()->back()->with('error', $message)->withInput($request->only("photo", "nom", "sexe", "adresse", "telephone", "email"));
                    }else {
                        $imagePath = "";
                        if ($request->hasFile('photo')){
                            /** @var UploadedFile|null $photo */
                            $image = $request->photo;
                            $imagePath = $image->store('medias/profiles', 'public');
                        }

                        $agent = User::create([
                            'photo' => $imagePath,
                            'nom' => $request->get("nom"),
                            'sexe' => $request->get("sexe"),
                            'adresse' => $request->get("adresse"),
                            'telephone' => $request->get("telephone"),
                            'email' => $request->get("email"),
                            'password' => Hash::make($request->get("password")),
                            'projet_id' => $projet_id,
                            'fonction' => $request->get("fonction"),
                            'droits' => "utilisateur",
                        ]);
                        $message = "l'agent a été ajouté avec succès";
                        return redirect()->back()->with('success', $message);
                    }
                }else {
                    $imagePath = "";
                    if ($request->hasFile('photo')){
                        /** @var UploadedFile|null $photo */
                        $image = $request->photo;
                        $imagePath = $image->store('medias/profiles', 'public');
                    }

                    $agent = User::create([
                        'photo' => $imagePath,
                        'nom' => $request->get("nom"),
                        'sexe' => $request->get("sexe"),
                        'adresse' => $request->get("adresse"),
                        'telephone' => $request->get("telephone"),
                        'email' => $request->get("email"),
                        'password' => Hash::make($request->get("password")),
                        'projet_id' => $projet_id,
                        'fonction' => $request->get("fonction"),
                        'droits' => "utilisateur",
                    ]);
                    $message = "l'agent a été ajouté avec succès";
                    return redirect()->back()->with('success', $message);
                }
            }
        }else {
            $request->validate([
                'photo'=>['max:5000'],
                'nom' => ['required', 'string', 'max:255'],
                'sexe' => ['required'],
                'telephone' => ['required', 'unique:'.User::class],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', Rules\Password::defaults()],
                'projet_id'=>['required'],
            ]);

            $imagePath = "";
            if ($request->hasFile('photo')){
                /** @var UploadedFile|null $photo */
                $image = $request->photo;
                $imagePath = $image->store('medias/profiles', 'public');
            }

            $message = "le partenaire a été ajouté avec succès";
            $visiteur = User::create([
                'photo' => $imagePath,
                'nom' => $request->get("nom"),
                'sexe' => $request->get("sexe"),
                'adresse' => $request->get("adresse"),
                'telephone' => $request->get("telephone"),
                'email' => $request->get("email"),
                'password' => Hash::make($request->get("password")),
                'projet_id' => $request->get('projet_id'),
                'droits' => "visiteur",
            ]);

            return redirect()->back()->with('success', $message);
        }
    }
}
