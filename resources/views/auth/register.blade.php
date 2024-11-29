@extends('base')
@section('page_title', 'Ajouter un utilisateur')
@section('titre', '#Ajouter un utilisateur')
@section('style')
    <link id="theme-style" rel="stylesheet" href="{{ asset("styles_dashboard/assets/register.css") }}">
@endsection
@section('content')
    <div>
        <form method="post" enctype="multipart/form-data" class="shadow p-3" id="formulaire"
              action="{{ route('register') }}">
            @csrf
            <div>
                <img id="imagePreview" src="{{ asset('assets/utilisateur.png') }}" alt=""
                     style="width: 100px; height: 100px; border-radius: 50px" class="mb-2">
            </div>
            <div class="mb-3">
                <x-text-input id="id_photo" class="block mt-1 w-full" type="file" class="form-control" name="photo"/>
                <x-input-error :messages="$errors->get('photo')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <x-input-label class="sr-only" for="id_nom" :value="__('nom')"/>
                <x-text-input id="id_nom" class="block mt-1 w-full form-control" type="text" name="nom"
                              :value="old('nom')"
                              placeholder="nom" required autofocus autocomplete="nom"/>
                <x-input-error :messages="$errors->get('nom')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <x-input-label for="id_sexe" :value="__('sexe')"/>
                <select id="id_sexe" name="sexe" class="form-control" required>
                    <option value=""></option>
                    <option @if( old('sexe') === "Homme" ) selected @endif value="Homme">Homme</option>
                    <option @if( old('sexe') === "Femme" ) selected @endif value="Femme">Femme</option>
                </select>
                <x-input-error :messages="$errors->get('sexe')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <label class="sr-only-focusable" for="id_adresse">Adresse</label>
                <input id="id_adresse" name="adresse" value="{{ old('adresse') }}" type="text" class="form-control"
                       placeholder="Adresse de résidence permanente"/>
            </div>
            <div class="mb-3">
                <x-input-label class="sr-only" for="id_telephone" :value="__('téléphone')"/>
                <x-text-input id="id_telephone" class="block mt-1 w-full form-control" type="number" name="telephone"
                              :value="old('telephone')" placeholder="numéro de téléphone" required
                              autocomplete="téléphone"/>
                <x-input-error :messages="$errors->get('telephone')" class="mt-2 text-danger"/>
            </div>
            <div class="email mb-3">
                <x-input-label class="sr-only" for="id_mail" :value="__('adresse email')"/>
                <x-text-input id="id_mail" class="block mt-1 w-full form-control" type="email" name="email"
                              :value="old('email')"
                              placeholder="exemple@exemple.com" required autocomplete="email"/>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <x-input-label for="id_droits" :value="__('type de compte')"/>
                <select id="id_droits" onchange="typecomptechange(this)" name="droits" class="form-control" required>
                    <option disabled>----------</option>
                    <option @if( old('droits') === "administrateur" ) selected
                            @endif value="administrateur">administrateur
                    </option>
                    <option @if( old('droits') === "visiteur" ) selected
                            @endif value="visiteur">visiteur
                    </option>
                </select>
                <x-input-error :messages="$errors->get('droits')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3" id="div_projet" style="display: none">
                <x-input-label for="id_projet" :value="__('sélectionner le projet auquel donner accès')"/>
                <select id="id_projet" name="projet_id" class="form-control">
                    <option disabled>----------</option>
                    @foreach($projets as $projet)
                        <option @if( old('projet_id') === $projet->id ) selected
                            @endif value="{{ $projet->id }}">{{ $projet->code_reference }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('droits')" class="mt-2 text-danger"/>
            </div>
            <div class="col-xs-12 col-md-9">
                <div class="row">
                    <div class="col-md-6 p-0">
                        <div>
                            <x-input-label for="password" :value="__('mot de passe')"/>
                            <x-text-input id="password" class="block mt-1 w-full form-control"
                                          type="password"
                                          name="password"
                                          required autocomplete="new-password" aria-describedby="requirements"/>
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger"/>
                        </div>
                        <div>
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
            </div>
            <button type="submit" class="w-100" value="Enregistrer" id="submit_form" disabled> Enregistrer</button>
        </form>
    </div>
@endsection
@section('scripts')
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
    <script src="{{ asset("js/register.js") }}"></script>
@endsection

