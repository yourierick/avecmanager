@extends('base_utilisateur')
@section('big_title')
    <div class="mb-4">
        <span style="color: peru; font-weight: 500" class="bi-file-word-fill"> PROJET REFERENCE: {{ $projet->code_reference }}</span>
        <br><span class="text-muted">AVEC: {{ $avec->designation }}</span>
    </div>
@endsection
@section('small_description', 'donner une assistance à un membre')
@section('content')
    <div class="p-4 shadow-lg">
        <h4>Donner une assistance à un membre</h4>
        <form method="post" action="{{ route('gestionprojet.save_transaction_assistance', [$avec->id, $projet->id]) }}" class="mt-6 space-y-6">
            @csrf
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="id_beneficiaire">bénéficiaire</label>
                    <select name="beneficiaire" id="id_beneficiaire" class="form-control">
                        <option value="" selected disabled>-------------</option>
                        @foreach($membres as $membre)
                            <option @if(old("beneficiaire") == $membre->id) selected @endif value="{{ $membre->id }}">{{ $membre->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <x-input-error :messages="$errors->get('beneficiaire')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="id_cas">cas d'assistance</label>
                    <select name="cas" id="id_cas" class="form-control">
                        <option value="" selected disabled>---------------</option>
                        @foreach($cas as $cas_assistance)
                            <option @if(old("cas") === $cas_assistance->cas) selected @endif value="{{ $cas_assistance->cas }}">{{ $cas_assistance->cas }}</option>
                        @endforeach
                    </select>
                </div>
                <x-input-error :messages="$errors->get('cas')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="id_montant">montant à donner</label>
                    <input id="id_montant" name="montant" class="form-control" type="number" step="any" min="0" max="{{ $montanttotal }}" placeholder="montant à donner">
                </div>
                <x-input-error :messages="$errors->get('montant')" class="mt-2 text-danger"/>
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
@endsection
