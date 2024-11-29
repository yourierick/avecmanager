@extends('base_utilisateur')
@section('big_title', 'INFORMATION DE PROFILE')
@section('small_description', "mettre à jour vos informations de profile")
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
    <link rel="stylesheet" href="{{ asset("styles_dashboard/assets/register.css") }}">
@endsection
@section('content')
    <section>
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
            @csrf
            <div>
                <img id="imagePreview" src="@if($user->photo) /storage/{{ $user->photo }} @else {{ asset('assets/utilisateur.png') }}  @endif" alt=""
                     style="width: 100px; height: 100px; border-radius: 50px" class="mb-2">
            </div>
            <div class="mb-3">
                <input id="id_photo" accept=".jpeg, .jpg, .png"
                       onchange="file_validate(this)" name="photo" value="{{ $user->photo }}" type="file" class="form-control"/>
                <x-input-error class="mt-2 text-danger" :messages="$errors->get('photo')" />
            </div>

            <div class="mb-3">
                <x-input-label for="nom" :value="__('nom')" />
                <x-text-input id="nom" name="nom" type="text" class="mt-1 block w-full form-control" :value="old('nom', $user->nom)" required autofocus autocomplete="nom" />
                <x-input-error class="mt-2 text-danger" :messages="$errors->get('nom')" />
            </div>
            <div class="mb-3">
                <x-input-label for="id_sexe" :value="__('sexe')"/>
                <select id="id_sexe" name="sexe" class="form-control" required>
                    <option value=""></option>
                    <option @if( old('sexe', $user->sexe) === "homme" ) selected @endif value="homme">homme</option>
                    <option @if( old('sexe', $user->sexe) === "femme" ) selected @endif value="femme">femme</option>
                </select>
                <x-input-error :messages="$errors->get('sexe')" class="mt-2 text-danger"/>
            </div>

            <div class="mb-3">
                <x-input-label for="nom" :value="__('adresse')" />
                <x-text-input id="adresse" name="adresse" type="text" class="mt-1 block w-full form-control" :value="old('adresse', $user->adresse)" required autofocus autocomplete="adresse" />
                <x-input-error class="mt-2 text-danger" :messages="$errors->get('adresse')" />
            </div>

            <div class="mb-3">
                <label for="id_telephone">Téléphone</label>
                <input id="id_telephone" name="telephone" value="{{ old('telephone', $user->telephone) }}" type="number"
                       class="form-control" placeholder="Numéro de téléphone"/>
                <x-input-error :messages="$errors->get('telephone')" class="mt-2 text-danger"/>
            </div>

            <div class="mb-3">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full form-control" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2 text-danger" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
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

            <div class="flex items-center" style="margin-top: 10px">
                <button type="submit">Enregistrer</button>
            </div>
        </form>
    </section>
    <section class="mt-3 shadow p-4">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Changer le mot de passe') }}
            </h2>

            <p class="mt-1 text-sm text-muted">
                {{ __('Choisissez un mot de passe qui respecte les normes de sécurité') }}
            </p>
        </header>

        <form method="post" id="formulaire" action="{{ route('password.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('put')

            <div class="row">
                <div class="col-md-6">
                    <div>
                        <x-input-label for="update_password_current_password" :value="__('mot de passe actuel')" />
                        <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full form-control" autocomplete="current-password" />
                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-danger" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('nouveau mot de passe')"/>
                        <x-text-input id="password" class="block mt-1 w-full form-control"
                                      type="password"
                                      name="password"
                                      required autocomplete="new-password" aria-describedby="requirements"/>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger"/>
                    </div>
                    <div>
                        <x-input-label for="password-confirmation" :value="__('confirmation du mot de passe')"/>
                        <x-text-input id="password-confirmation" class="block mt-1 w-full form-control"
                                      type="password" name="password-confirmation" required
                                      autocomplete="new-password"/>
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
@endsection
@section('scripts')
    <script src="{{ asset("js/register.js") }}"></script>
    <script>
        @if(session('success'))
        $(document).ready(function() {
            $.notify({
                icon: 'icon-bell',
                title: 'Avecmanager',
                message: '{{ session('success') }}',
            },{
                type: 'secondary',
                placement: {
                    from: "bottom",
                    align: "right"
                },
                time: 1000,
            });
        });
        @elseif(session('error'))
        $(document).ready(function() {
            $.notify({
                icon: 'icon-bell',
                title: 'Avecmanager',
                message: '{{ session('error') }}',
            },{
                type: 'danger',
                placement: {
                    from: "bottom",
                    align: "right"
                },
                time: 1000,
            });
        });
        @endif
    </script>
    <script src="{{ asset("js/personnal_scripts/file_validator.js") }}"></script>
@endsection
