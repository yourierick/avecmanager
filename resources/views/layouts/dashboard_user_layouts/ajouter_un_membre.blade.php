@extends('base_utilisateur')
@section('big_title')
    <span style="color: peru" class="bi-file-word-fill"> PROJET {{ $projet->code_reference }}</span><br>
    AVEC {{ $avec->designation }}
@endsection
@section('content')
    <br><br><div class="p-4 shadow-lg">
        <h4>Ajouter un membre</h4>
        <form method="post" action="{{ route("gestionprojet.ajouter_un_membre", $avec->id) }}" class="mt-6 space-y-6"
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
                        accept=".jpeg, .jpg, .png"
                        onchange="file_validate(this)"
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
                    <input id="id_telephone" name="numeros_de_telephone" type="number"
                           class="form-control"
                           value="{{ old('numeros_de_telephone') }}" required autofocus
                           placeholder="0000000000"/>
                </div>
                <x-input-error :messages="$errors->get('numeros_de_telephone')" class="mt-2 text-danger"/>
            </div>

            <div class="flex items-center mt-2 gap-4">
                <button class="btn btn-primary text-light"> Enregistrer
                </button>
            </div>
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
    <script src="{{ asset("js/personnal_scripts/file_validator.js") }}"></script>
@endsection
