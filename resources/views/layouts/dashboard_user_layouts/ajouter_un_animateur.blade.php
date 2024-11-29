@extends('base_utilisateur')
@section('big_title')
    PROJET {{ $projet->code_reference }}
@endsection
@section('small_description', 'ajouter un nouvel animateur')
@section('style')
    <link id="theme-style" rel="stylesheet" href="{{ asset("styles_dashboard/assets/register.css") }}">
@endsection
@section('content')
    <div class="p-4 shadow-lg">
        <h4>Ajouter un animateur</h4>
        <form method="post" action="{{ route('gestionprojet.ajouter_un_animateur', $projet->id) }}" id="formulaire" class="mt-6 space-y-6"
              enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label>photo de profile</label>
                    <input
                        id="id_photo"
                        type="file"
                        class="form-control"
                        name="photo"
                        placeholder="photo de profile"
                        value="{{ old('photo') }}"
                    />
                </div>
                <x-input-error :messages="$errors->get('photo')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="nom">nom</label>
                    <input
                        id="nom"
                        type="text"
                        class="form-control"
                        name="nom"
                        placeholder="nom de l'utilisateur"
                        value="{{ old('nom') }}"
                        required
                    />
                </div>
                <x-input-error :messages="$errors->get('nom')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="id_sexe">sexe</label>
                    <select id="id_sexe" name="sexe" class="form-control" required>
                        <option selected disabled>-------------</option>
                        <option @if( old('sexe') === "homme" ) selected
                                @endif value="homme">homme
                        </option>
                        <option @if( old('sexe') === "femme" ) selected
                                @endif value="femme">femme
                        </option>
                    </select>
                </div>
                <x-input-error :messages="$errors->get('sexe')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="id_fonction">fonction</label>
                    <select id="id_fonction" name="fonction" class="form-control" required>
                        <option @if( old('fonction') === "animateur" ) selected
                                @endif value="animateur">animateur
                        </option>
                    </select>
                </div>
                <x-input-error :messages="$errors->get('fonction')" class="mt-2 text-danger"/>
            </div>
            @if($current_user->fonction === "superviseur")
                <div class="mb-3">
                    <div class="form-group form-group-default">
                        <label for="id_superviseur">superviseur</label>
                        <select id="id_superviseur" name="superviseur_id" class="form-control" required>
                            <option @if( old('superviseur_id') === $current_user->id ) selected
                                    @endif value="{{ $current_user->id }}">{{ $current_user->nom }}
                            </option>
                        </select>
                    </div>
                </div>
            @endif
            @if($current_user->fonction === "chef de projet")
                <div class="mb-3" id="div_superviseur" style="display: none">
                    <div class="form-group form-group-default">
                        <label for="id_superviseur">superviseur</label>
                        <select id="id_superviseur" name="superviseur_id" class="form-control" required>
                            <option selected disabled>-------------</option>
                            @foreach($superviseurs as $superviseur)
                                <option @if( old('superviseur_id') === $superviseur->id ) selected
                                        @endif value="{{ $superviseur->id }}">{{ $superviseur->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <x-input-error :messages="$errors->get('superviseur_id')" class="mt-2 text-danger"/>
                </div>
            @endif
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="adresse">adresse</label>
                    <input id="adresse" name="adresse" type="text"
                           class="form-control"
                           value="{{ old('adresse') }}" required autofocus
                           placeholder="adresse de résidence actuelle"/>
                </div>
                <x-input-error :messages="$errors->get('adresse')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="id_telephone">Téléphone</label>
                    <input id="id_telephone" name="telephone" type="number"
                           class="form-control"
                           value="{{ old('telephone') }}" required autofocus
                           placeholder="0000000000"/>
                </div>
                <x-input-error :messages="$errors->get('telephone')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="id_email">adresse eléctronique</label>
                    <div class="input-group mb-3">
                        <input
                            type="text"
                            id="id_email"
                            class="form-control"
                            placeholder="adresse email"
                            aria-label="adresse electronique"
                            aria-describedby="basic-addon2"
                            name="email"
                        />
                        <span class="input-group-text" id="basic-addon2">@example.com</span>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <x-input-label for="password" :value="__('mot de passe')"/>
                        <x-text-input id="password" class="block mt-1 w-full form-control"
                                      type="password"
                                      name="password"
                                      required autocomplete="new-password" aria-describedby="requirements"/>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger"/>
                    </div>
                    <div class="form-group">
                        <x-input-label for="password-confirmation" :value="__('confirmer le mot de passe')"/>
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
            <div class="flex items-center mt-2 gap-4">
                <button disabled type="submit" id="submit_form" class="btn btn-primary text-light"> Enregistrer
                </button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset("js/register.js") }}"></script>
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
    </script>
@endsection
