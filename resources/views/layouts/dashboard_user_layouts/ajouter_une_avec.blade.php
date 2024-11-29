@extends('base_utilisateur')
@section('big_title')
    <span style="color: peru" class="bi-file-word-fill">PROJET {{ $projet->code_reference }}</span>
@endsection
@section('small_description', 'ajouter une nouvelle avec')
@section('content')
    <div class="p-4 shadow-lg">
        <h4>Ajouter une avec</h4>
        <form method="post" action="{{ route('gestionprojet.ajouter_une_avec', $projet->id) }}" class="mt-6 space-y-6"
              enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="id_designation">désignation</label>
                    <input
                        id="id_designation"
                        type="text"
                        class="form-control"
                        name="designation"
                        placeholder="nom de l'avec"
                        value="{{ old('designation') }}"
                        required
                    />
                </div>
                <x-input-error :messages="$errors->get('designation')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="id_axe">axe</label>
                    <select id="id_axe" name="axe_id" class="form-control" required>
                        <option selected disabled>---------------</option>
                        @foreach($axes as $axe)
                            <option @if(old("axe_id") === $axe->id) selected @endif value="{{ $axe->id }}">
                                {{ $axe->designation }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <x-input-error :messages="$errors->get('axe_id')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="valeur_part">valeur d'une part</label>
                    <input
                        id="valeur_part"
                        type="number"
                        step="any"
                        class="form-control"
                        name="valeur_part"
                        placeholder="valeur d'une part"
                        value="{{ old('valeur_part') }}"
                        required
                    />
                </div>
                <x-input-error :messages="$errors->get('nombre_femmes')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="maximum_part_achetable">maximum de parts achetables</label>
                    <input
                        id="maximum_part_achetable"
                        type="number"
                        class="form-control"
                        name="maximum_part_achetable"
                        placeholder="maximum des parts achetables"
                        value="{{ old('maximum_part_achetable') }}"
                        required
                    />
                </div>
                <x-input-error :messages="$errors->get('maximum_part_achetable')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="valeur_montant_solidarite">valeur d'une cotisation solidarité</label>
                    <input
                        id="valeur_montant_solidarite"
                        type="number"
                        step="any"
                        class="form-control"
                        name="valeur_montant_solidarite"
                        placeholder="valeur d'une cotisation solidarité"
                        value="{{ old('valeur_montant_solidarite') }}"
                        required
                    />
                </div>
                <x-input-error :messages="$errors->get('maximum_part_achetable')" class="mt-2 text-danger"/>
            </div>
            @if($current_user->fonction === "superviseur")
                <div class="mb-3">
                    <div class="form-group form-group-default">
                        <label for="id_superviseur">superviseur</label>
                        <select id="id_superviseur" name="superviseur_id" class="form-control" required>
                            <option value="{{ $current_user->id }}">
                                {{ $current_user->nom }}
                            </option>
                        </select>
                    </div>
                    <x-input-error :messages="$errors->get('superviseur_id')" class="mt-2 text-danger"/>
                </div>
            @else
                <div class="mb-3">
                    <div class="form-group form-group-default">
                        <label for="id_superviseur">superviseur</label>
                        <select id="id_superviseur" name="superviseur_id" class="form-control" required>
                            <option value="{{ $current_user->superviseur_id }}">
                                @php
                                    $superviseur = \App\Models\User::find($current_user->superviseur_id)
                                @endphp
                                {{ $superviseur->nom }}
                            </option>
                        </select>
                    </div>
                    <x-input-error :messages="$errors->get('superviseur_id')" class="mt-2 text-danger"/>
                </div>
            @endif
            @if($current_user->fonction !== "animateur")
                <div class="mb-3">
                    <div class="form-group form-group-default">
                        <label for="id_animateur">animateur</label>
                        <select id="id_animateur" name="animateur_id" class="form-control" required>
                            @foreach($animateurs as $animateur)
                                <option value="{{ $animateur->id }}">
                                    {{ $animateur->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <x-input-error :messages="$errors->get('animateur_id')" class="mt-2 text-danger"/>
                </div>
            @else
                <div class="mb-3">
                    <div class="form-group form-group-default">
                        <label for="id_animateur">animateur</label>
                        <select id="id_animateur" name="animateur_id" class="form-control" required>
                            <option value="{{ $current_user->id }}">
                                {{ $current_user->nom }}
                            </option>
                        </select>
                    </div>
                    <x-input-error :messages="$errors->get('animateur_id')" class="mt-2 text-danger"/>
                </div>
            @endif
            <div class="flex items-center mt-2 gap-4">
                <button type="submit" class="btn btn-primary text-light"> Enregistrer
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
@endsection
