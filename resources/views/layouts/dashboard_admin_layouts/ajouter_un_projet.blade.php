@extends('base')
@section('big_title', 'AJOUTER UN PROJET')
@section('small_description', 'ajouter un nouveau projet')
@section('content')
    <div>
        <form method="post" class="shadow p-3" action="{{ route('projet.enregistrer') }}">
            @csrf
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label>code de référence du projet</label>
                    <input
                        id="id_code_reference"
                        type="text"
                        class="form-control"
                        name="code_reference"
                        placeholder="code de référence du projet"
                        value="{{ old('code_reference') }}"
                        required
                    />
                </div>
                <x-input-error :messages="$errors->get('code_reference')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="id_context">code de référence du projet</label>
                    <textarea name="context" placeholder="contexte du projet" id="id_context" class="form-control" cols="30" rows="7">{{ old('context') }}</textarea>
                </div>
                <x-input-error :messages="$errors->get('context')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label>cycle d'accompagnement</label>
                    <input
                        id="cycle"
                        type="number"
                        class="form-control"
                        placeholder="durée d'accompagnement des avec prévue par le projet en mois"
                        name="cycle_de_gestion"
                        value="{{ old('cycle_de_gestion') }}"
                        required
                    />
                </div>
                <x-input-error :messages="$errors->get('cycle_de_gestion')" class="mt-2 text-danger"/>
            </div>

            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label>date de début</label>
                    <input
                        id="date_debut"
                        type="date"
                        class="form-control"
                        name="date_de_debut"
                        value="{{ old('date_de_debut') }}"
                        required
                    />
                </div>
                <x-input-error :messages="$errors->get('date_de_debut')" class="mt-2 text-danger"/>
            </div>

            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label>date de fin</label>
                    <input
                        id="date_fin"
                        type="date"
                        class="form-control"
                        name="date_de_fin"
                        value="{{ old('date_de_fin') }}"
                        required
                    />
                </div>
                <x-input-error :messages="$errors->get('date_de_fin')" class="mt-2 text-danger"/>
            </div>
            <button type="submit" class="w-100 btn btn-primary text-light"> Enregistrer</button>
        </form>
    </div>
@endsection
