@extends('base_utilisateur')
@section("style")
    <style>
        .perso:hover{
            color: #000000;
            background-color: #d9d6d6;
            border-radius: 9px;
            transition: .5s ease;
        }
    </style>
@endsection
@section('big_title')
    <div class="row mb-4">
        <div class="col-md-8">
            <span class="text-muted bi-file-word-fill">PROJET REFERENCE: {{ $projet->code_reference }}</span>
            <br><span style="color: #ee6900; text-transform: uppercase; font-weight: bold">AVEC: {{ $avec->designation }}</span>
        </div>
    </div>
@endsection
@section('page_courant', "membre de l'avec")
@section('content')
    <hr>
    <div class="row-card-no-pd p-4 bg-white" style="background-color: transparent">
        <h1 style="font-weight: bold"><span class="bi bi-exclamation-triangle text-warning" style="font-size: 24pt"></span> cas d'abandon d'un membre</h1>
        <div style="margin-left: 40px">
            <p>Vous voyez ceci car vous avez changé le statut de @if($membre->sexe === "homme") monsieur
                @else madame @endif {{ $membre->nom }} sur abandon, si cela n'a pas été fait intentionnellement
                vous pouvez simplement retourner à la page précédente, sinon veuillez renseigner les champs ci-dessous!
            </p>

            <form action="{{ route("gestionprojet.gestion_cas_abandon_membre_treatment", [$membre->id, $avec->id]) }}" method="post">
                @csrf
                <div class="mb-3">
                    <div class="form-group form-group-default">
                        <label for="id_mois">mois</label>
                        <select name="mois_id" id="id_mois" onchange="load_semaines(this)" class="form-control" required>
                            <option selected disabled>--------------</option>
                            @foreach($cycle_de_gestion as $mois)
                                <option @if(old('mois_id') === $mois->id) selected @endif value="{{ $mois->id }}">{{ $mois->designation }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-input-error :messages="$errors->get('mois_id')" class="mt-2 text-danger"/>
                </div>
                <div class="mb-3">
                    <div class="form-group form-group-default">
                        <label for="id_semaine">semaine</label>
                        <select name="semaine" id="id_semaine" class="form-control" required>
                            <option selected disabled>--------------</option>
                        </select>
                    </div>
                    <x-input-error :messages="$errors->get('mois_id')" class="mt-2 text-danger"/>
                </div>
                <div>
                    <label>
                        <input name="calculpart" type="checkbox" checked readonly id="flexCheckpart">
                        Les parts achetées par @if($membre->sexe === "homme") monsieur
                        @else madame @endif {{ $membre->nom }} doivent lui être rendu moins le montant de sa dette.
                    </label>
                </div>
                <div>
                    <label class="text-danger">
                        <input name="calculinteret" type="checkbox" id="flexCheckinteret">
                        Les intérêts sur les parts achetées par @if($membre->sexe === "homme") monsieur
                        @else madame @endif {{ $membre->nom }} doivent être calculés et rendu à @if($membre->sexe === "homme") monsieur
                        @else madame @endif {{ $membre->nom }}.
                    </label>
                </div>

                <br><p><span class="bi bi-exclamation-triangle text-danger" style="font-size: 12pt"></span> Avant de continuer, sachez que cette action est irréversible.
                    Toutes les transactions effectuées par ce membre seront définitivement gelées. cliquer sur continuer si vous êtes sûr de poursuivre.
                </p>

                <button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#modal">
                    <span>continuer</span>
                </button>
                <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">
                                    <span class="fw-mediumbold"><span class="bi bi-exclamation-triangle text-danger" style="font-size: 12pt"></span> démande</span>
                                    <span class="fw-light"> de confirmation</span>
                                </h5>
                                <button type="button" class="close" data-bs-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="dropdown-divider" style="margin: 0"></div>
                                <p>
                                    Avant de continuer, sachez que cette action est irréversible.
                                    Toutes les transactions effectuées par ce membre seront définitivement supprimées. cliquer sur oui pour continuer.
                                </p>
                                <button type="submit" id="addRowButton" class="btn btn-label-danger">
                                    oui
                                </button>
                                <button type="button" class="btn btn-label-primary" data-bs-dismiss="modal">
                                    non
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('gestionprojet.afficher_avec', $avec->id) }}" class="btn btn-primary">revenir à l'avec</a>
            </form>
        </div>
    </div>
@endsection
@section("scripts")
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
        function load_semaines(element) {
            $.ajax({
                url: '../../load_semaines/' + element.value + "/" + {{ $membre->id }} + "/" + {{ $avec->id }},
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#id_semaine').empty();
                    $.each(data, function(key, value){
                        $('#id_semaine').append('<option value="' + value + '">' + value + '</option>');
                    })
                }, error: function (xhr, status, error){
                    $.notify({
                        icon: 'icon-bell',
                        title: 'Avecmanager',
                        message: 'une erreur est survenue lors du traitement de le requête',
                    }, {
                        type: 'danger',
                        placement: {
                            from: "bottom",
                            align: "right"
                        },
                        time: 1000,
                    });
                }
            })
        }
    </script>
@endsection
