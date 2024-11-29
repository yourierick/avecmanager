@extends('base')
@section('big_title')
    <span class="bi-person" style="color: peru"> INFORMATION DE PROFILE</span>
@endsection
@section('small_description', "mettre à jour les informations de ce profile")
@section('page_courant', 'modifier ce profile')
@section('style')
    <style>
        button[type=submit] {
            transition: .10s;
            border-radius: 4px;
            border: 1px solid rgba(0, 0, 0, 0.12);
            padding: 14px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 14px;
            background-color: #0a58ca;
            color: #fafafa;
        }

        button[type=submit]:hover {
            border-color: transparent;
            color: #fafafa;
        }
    </style>
    <link id="theme-style" rel="stylesheet" href="{{ asset("personnal_styles/edit_profile_user_style.css") }}">
    <link id="theme-style" rel="stylesheet" href="{{ asset("personnal_styles/portal.css") }}">
@endsection
@section('content')
    <section class="shadow-lg p-4">
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('user.update_profile', $user->id) }}" class="mt-6 space-y-6"
              enctype="multipart/form-data">
            @csrf
            @method('put')
            <div>
                <img id="imagePreview"
                     src="@if($user->photo) /storage/{{ $user->photo }} @else {{ asset('assets/utilisateur.png') }}  @endif"
                     alt=""
                     style="width: 100px; height: 100px; border-radius: 50px" class="mb-2">
            </div>
            <div class="mb-3">
                <input id="id_photo" name="photo" value="{{ $user->photo }}" type="file" class="form-control"/>
                <x-input-error class="mt-2 text-danger" :messages="$errors->get('photo')"/>
            </div>
            <div class="form-check">
                <div class="form-check form-switch mt-1">
                    <input name="statut" class="form-check-input" type="checkbox"
                           id="settings-switch-1" @if($user->statut) checked @endif>
                    <label class="form-check-label" for="settings-switch-1">statut du compte</label>
                </div>
            </div><!--//form-check-->
            <div class="mb-3 form-group form-group-default">
                <x-input-label for="nom" :value="__('nom')"/>
                <input id="nom" name="nom" type="text" class="mt-1 block w-full form-control"
                       value="{{old('nom', $user->nom)}}" placeholder="nom" required autofocus/>
                <x-input-error class="mt-2 text-danger" :messages="$errors->get('nom')"/>
            </div>
            <div class="mb-3 form-group form-group-default">
                <x-input-label for="id_sexe" :value="__('sexe')"/>
                <select id="id_sexe" name="sexe" class="form-control" required>
                    <option disabled>----------------</option>
                    <option @if( old('sexe', $user->sexe) === "homme" ) selected @endif value="homme">homme</option>
                    <option @if( old('sexe', $user->sexe) === "femme" ) selected @endif value="femme">femme</option>
                </select>
                <x-input-error :messages="$errors->get('sexe')" class="mt-2 text-danger"/>
            </div>

            <div class="mb-3 form-group form-group-default">
                <x-input-label for="nom" :value="__('adresse')"/>
                <input id="adresse" name="adresse" type="text" placeholder="adresse de résidence" class="mt-1 block w-full form-control"
                       value="{{ old('adresse', $user->adresse) }}" required autofocus/>
                <x-input-error class="mt-2 text-danger" :messages="$errors->get('adresse')"/>
            </div>

            <div class="mb-3 form-group form-group-default">
                <label for="id_telephone">Téléphone</label>
                <input id="id_telephone" name="telephone" value="{{ old('telephone', $user->telephone) }}" type="number"
                       class="form-control" placeholder="Numéro de téléphone"/>
                <x-input-error :messages="$errors->get('telephone')" class="mt-2 text-danger"/>
            </div>

            <div class="mb-3 form-group form-group-default">
                <x-input-label for="email" :value="__('Email')"/>
                <input id="email" name="email" type="email" class="mt-1 block w-full form-control"
                              value="{{ old('email', $user->email) }}" required/>
                <x-input-error class="mt-2 text-danger" :messages="$errors->get('email')"/>

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification"
                                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="mb-3 form-group form-group-default">
                <x-input-label for="id_type" :value="__('type de compte')"/>
                <select id="id_type" name="droits" onchange="typecomptechange()" class="form-control" required>
                    <option disabled>--------------</option>
                    <option @if( old('droits', $user->droits) === "administrateur" ) selected
                            @endif value="administrateur">administrateur
                    </option>
                    <option @if( old('droits', $user->droits) === "utilisateur" ) selected @endif value="utilisateur">
                        utilisateur
                    </option>
                    <option @if( old('droits', $user->droits) === "visiteur" ) selected @endif value="visiteur">
                        visiteur
                    </option>
                </select>
                <x-input-error :messages="$errors->get('droits')" class="mt-2 text-danger"/>
            </div>

            <div class="mb-3" id="div_projet" style="display: none">
                <div class="form-group form-group-default">
                    <label>Projet</label>
                    <select id="id_projet" name="projet_id" class="form-control">
                        <option selected disabled>-------------</option>
                        @foreach($projets as $projet)
                            <option @if($user->projet_id === $projet->id ) selected @endif value="{{ $projet->id }}">
                                {{ $projet->code_reference }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <x-input-error :messages="$errors->get('projet_id')" class="mt-2 text-danger"/>
            </div>

            <div class="mb-3" id="div_fonction" style="display: none">
                <div class="form-group form-group-default">
                    <x-input-label for="id_fonction" :value="__('fonction')"/>
                    <select id="id_fonction" name="fonction" class="form-control">
                        <option selected disabled>-------------</option>
                        <option @if( $user->fonction === "coordinateur du projet" ) selected
                                @endif value="coordinateur du projet">coordinateur du projet
                        </option>
                        <option @if( $user->fonction === "chef de projet" ) selected
                                @endif value="chef de projet">chef de projet
                        </option>
                        <option @if( $user->fonction === "assistant suivi et évaluation" ) selected
                                @endif value="assistant suivi et évaluation">assistant suivi et évaluation
                        </option>
                        <option @if( $user->fonction === "superviseur" ) selected
                                @endif value="superviseur">superviseur
                        </option>
                        <option @if( $user->fonction === "animateur" ) selected
                                @endif value="animateur">animateur
                        </option>
                    </select>
                </div>
                <x-input-error :messages="$errors->get('fonction')" class="mt-2 text-danger"/>
            </div>

            <div class="mb-3" id="div_superviseur" style="display: none">
                <div class="form-group form-group-default">
                    <label for="id_superviseur">superviseur</label>
                    <select id="id_superviseur" name="superviseur_id" class="form-control">
                    </select>
                </div>
                <x-input-error :messages="$errors->get('superviseur_id')" class="mt-2 text-danger"/>
            </div>

            @if ($user->fonction === "superviseur" || $user->fonction === "animateur")
                <div class="mb-3 shadow p-3 bg-white">
                    <div class="col-12 col-md-4">
                        <span style="color: dodgerblue">Droits d'utilisateur</span>
                        <div class="section-intro">Cet utilisateur pourra réaliser les actions suivantes:</div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="app-card app-card-settings p-4">
                            <div class="app-card-body">
                                @if($user->fonction === "superviseur")
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" value="peux ajouter un animateur" name="autorisation[]" id="checkbox-1" @if($user->autorisations !== null) @if(in_array('peux ajouter un animateur', json_decode($user->autorisations, true))) checked @endif @endif>
                                        <label class="form-check-label" for="checkbox-1">
                                            peux ajouter un animateur
                                        </label>
                                    </div><!--//form-check-->
                                @endif
                                @if($user->fonction === "superviseur" || $user->fonction === "animateur")
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" value="peux faire une transaction" name="autorisation[]" id="checkbox-2" @if($user->autorisations !== null) @if(in_array('peux faire une transaction', json_decode($user->autorisations, true))) checked @endif @endif>
                                        <label class="form-check-label" for="checkbox-2">
                                            peux faire une transaction
                                        </label>
                                    </div><!--//form-check-->
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" value="peux ajouter une avec" name="autorisation[]" id="checkbox-3" @if($user->autorisations !== null) @if(in_array('peux ajouter une avec', json_decode($user->autorisations, true))) checked @endif @endif>
                                        <label class="form-check-label" for="checkbox-3">
                                            peux ajouter une avec
                                        </label>
                                    </div><!--//form-check-->
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" value="peux ajouter un membre dans l'avec" name="autorisation[]" id="checkbox-4" @if($user->autorisations !== null) @if(in_array("peux ajouter un membre dans l'avec", json_decode($user->autorisations, true))) checked @endif @endif>
                                        <label class="form-check-label" for="checkbox-4">
                                            peux ajouter un membre dans l'avec
                                        </label>
                                    </div><!--//form-check-->
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" value="peux supprimer un membre" name="autorisation[]" id="checkbox-5" @if($user->autorisations !== null) @if(in_array('peux supprimer un membre', json_decode($user->autorisations, true))) checked @endif @endif>
                                        <label class="form-check-label" for="checkbox-5">
                                            peux supprimer un membre
                                        </label>
                                    </div><!--//form-check-->
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" value="peux configurer une avec" name="autorisation[]" id="checkbox-7" @if($user->autorisations !== null) @if(in_array('peux configurer une avec', json_decode($user->autorisations, true))) checked @endif @endif>
                                        <label class="form-check-label" for="checkbox-7">
                                            peux configurer une avec
                                        </label>
                                    </div><!--//form-check-->
                                @endif
                            </div><!--//app-card-body-->
                        </div><!--//app-card-->
                    </div>
                </div>
            @endif

            <div class="flex items-center" style="margin-top: 10px">
                <button type="submit" class="btn btn-primary">enregistrer</button>
            </div>
        </form>
    </section>
    <section class="mt-3 shadow p-4">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Changer le mot de passe') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Choisissez un mot de passe qui respecte les normes de sécurité') }}
            </p>
        </header>

        <form method="post" id="formulaire" action="{{ route('user.update_password', $user->id) }}"
              class="mt-6 space-y-6">
            @csrf
            @method('put')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-2">
                        <div class="form-group form-group-default">
                            <label for="password">Mot de passe</label>
                            <input id="password" class="form-control"
                                          type="password"
                                          name="password"
                                          required placeholder="nouveau mot de passe" aria-describedby="requirements"/>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger"/>
                    </div>
                    <div class="mb-1">
                        <div class="form-group form-group-default">
                            <label for="password-confirmation">Confirmation du mot de passe</label>
                            <input id="password-confirmation" class="form-control"
                                          type="password" name="password-confirmation" required
                                          placeholder="confirmer le mot de passe"/>
                        </div>
                        <x-input-error :messages="$errors->get('password-confirmation')" class="mt-2 text-danger"/>
                    </div>
                    <div class="password-requirements">
                        <p class="requirement error" id="match" style="display: none">Les mots de passe
                            doivent correspondre</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <p class="mb-2">Exigences du mot de passe</p>
                    <p class="small text-muted mb-2">Pour créer un nouveau mot de passe, vous devez
                        remplir toutes les exigences suivantes:</p>
                    <ul class="small text-muted pl-4 mb-0">
                        <li class="requirement" id="length">Minimum 8 caractères</li>
                        <li class="requirement" id="lowercase">Doit inclure une miniscule</li>
                        <li class="requirement" id="uppercase">Doit inclure une majuscule</li>
                        <li class="requirement" id="number">Doit inclure un chiffre</li>
                        <li class="requirement" id="characters">Doit inclure un caractère spécial:
                            #.-?!@$%^&*
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex items-center" style="margin-top: 10px">
                <button type="submit" id="submit_form" class="btn-submit" disabled>enregistrer</button>
            </div>
        </form>
    </section>

    <br><br>
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="max-w-xl">
            <section class="space-y-6">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Effacer ce compte') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Il est à noter que cette action est irréversible, cliquer sur supprimer pour continuer') }}
                    </p>
                </header>
                <button type="button" data-bs-toggle="modal" data-bs-target='#modal_supcompte'
                        class="btn btn-danger text-light">
                    SUPPRIMER
                </button>
                <div class="modal fade" id='modal_supcompte'>
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title h5 text-center "
                                    id="exampleModalCenteredScrollableTitle">
                                    Demande de confirmation
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post"
                                      action="{{ route('user.destroy_account', $user->id) }}"
                                      class="p-6">
                                    @csrf
                                    @method('delete')

                                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Etes-vous sûr de vouloir supprimer ce compte?') }}
                                    </h2>

                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('Il est à noter que cette action est irréversible, cliquer sur supprimer pour continuer') }}
                                    </p>

                                    <div class="mt-6">
                                        <x-text-input
                                            id="id_password"
                                            name="password"
                                            type="password"
                                            class="mt-1 block w-3/4 form-control mb-2"
                                            placeholder="{{ __('veuillez entrer votre mot de passe administrateur') }}"
                                        />
                                        <x-input-error :messages="$errors->userDeletion->get('password')"
                                                       class="mt-2 text-danger"/>
                                    </div>
                                    <div class="mt-6 flex justify-end">
                                        <button class="btn btn-danger btn-sm text-light" type="submit">SUPPRIMER
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary btn-sm text-light" type="button"
                                        data-bs-dismiss="modal">Annuler
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset("js/personnal_scripts/edit_profile_user_script.js") }}"></script>
    <script>
        @if(session('success'))
            $(document).ready(function () {
                $.notify({
                    icon: 'icon-bell',
                    title: 'Avecmanager',
                    message: '{{ session('success') }}',
                }, {
                    type: 'secondary',
                    placement: {
                        from: "bottom",
                        align: "right"
                    },
                    time: 1000,
                });
            });
        @elseif(session('error'))
            $(document).ready(function () {
                $.notify({
                    icon: 'icon-bell',
                    title: 'Avecmanager',
                    message: '{{ session('error') }}',
                }, {
                    type: 'danger',
                    placement: {
                        from: "bottom",
                        align: "right"
                    },
                    time: 1000,
                });
            });
        @endif

        $("#id_fonction").on("change", function() {
            let div_superviseur = document.getElementById("div_superviseur");
            let select_superviseur = document.getElementById("id_superviseur");
            let select_projet = document.getElementById("id_projet");
            if (this.value === "animateur") {
                div_superviseur.style.display = "unset";
                select_superviseur.setAttribute('required', "true");

                $.ajax({
                    url: "../loadsuperviseurs/"+select_projet.value,
                    type: "get",

                    success: function (data) {
                        select_superviseur.innerHTML = "";
                        var options = '<option disabled>-------------------<option>';
                        for (let i=0; i<data.superviseurs.length; i++){
                            options += '<option value="' + data.superviseurs[i].id + '">' + data.superviseurs[i].nom + '</option>';
                        }
                        $('#id_superviseur').append(options);
                    }
                })
            }else {
                select_superviseur.removeAttribute('required');
                div_superviseur.style.display = "none";
            }
        })

        $(document).ready(function () {
            let select_fonction = document.getElementById("id_fonction");
            if (select_fonction.value === "animateur") {
                let div_superviseur = document.getElementById('div_superviseur');
                div_superviseur.style.display = "unset";
                let select_superviseur = document.getElementById("id_superviseur");
                let select_projet = document.getElementById("id_projet");
                div_superviseur.style.display = "unset";
                select_superviseur.setAttribute('required', "true");

                $.ajax({
                    url: "../loadsuperviseurs/"+select_projet.value,
                    type: "get",

                    success: function (data) {
                        select_superviseur.clear;
                        let superviseur_id = null;
                        @if($user->superviseur_id)
                            superviseur_id = {{ $user->superviseur_id }};
                        @endif
                        var options = "<option disabled>-------------------<option>";
                        for (let i=0; i<data.superviseurs.length; i++){
                            if (superviseur_id) {
                                var selected = data.superviseurs[i].id === superviseur_id ? ' selected' : '';
                                options += '<option value="' + data.superviseurs[i].id + '"' + selected + '>' + data.superviseurs[i].nom + '</option>';
                            }else {
                                options += '<option value="' + data.superviseurs[i].id + '">' + data.superviseurs[i].nom + '</option>';
                            }
                        }

                        $('#id_superviseur').append(options);
                    }
                })
            }
        })
    </script>
@endsection

