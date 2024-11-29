@extends('base')
@section('big_title')
    <span style="color: peru" class="bi-file-word-fill">PROJET {{ $projet->code_reference }}</span>
@endsection
@section('small_description', 'configuration de ce projet')
@section('style')
    <link id="theme-style" rel="stylesheet" href="{{ asset("styles_dashboard/assets/register.css") }}">
@endsection
@section('content')
    <div class="modal fade" id="addRowModalmois" tabindex="-1"
         role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold"> édition</span>
                        <span class="fw-light"> du mois </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="small">
                        éditer ce mois du cycle de gestion du projet en
                        utilisant ce formulaire
                    </p>
                    <form
                        action="{{ route('projet.configuration_mois_cycle_de_gestion') }}"
                        method="post">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-sm-12">
                                <input name="mois" type="hidden"
                                       value=""
                                       id="mois_id"
                                       class="form-control"
                                       placeholder="désignation"/>
                                <div class="form-group form-group-default">
                                    <label>désignation du mois</label>
                                    <input name="designation" type="text"
                                           class="form-control"
                                           placeholder="désignation"/>
                                </div>
                            </div>
                        </div>
                        <button type="submit" id="addButton"
                                class="btn btn-primary">
                            soumettre
                        </button>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-danger"
                            data-bs-dismiss="modal">
                        fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ModalSupaxe"
         tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold"> Demande</span>
                        <span
                            class="fw-light"> de confirmation </span>
                    </h5>
                    <button type="button" class="close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-warning">
                        êtes-vous sûr de vouloir supprimer cet axe,
                        il est à noter que cette action est
                        irréversible
                    </p>
                    <form
                        action="{{ route('projet.supprimer_axe') }}"
                        method="post">
                        @csrf
                        @method('delete')
                        <div class="row">
                            <div class="col-sm-12">
                                <input name="axe_id" type="hidden" value="" id="axe_id"/>
                                <div
                                    class="form-group form-group-default">
                                    <button type="submit"
                                            id="addRowButton"
                                            class="btn btn-danger">
                                        soumettre
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="p-4 shadow-lg">
        <h4>Ajouter un staff de gestion du projet</h4>
        <form method="post" action="{{ route("register") }}" id="formulaire" class="mt-6 space-y-6 bg-white p-4"
              enctype="multipart/form-data">
            @csrf
            <div>
                <img id="imagePreview" src="{{ asset('assets/utilisateur.png') }}" alt=""
                     style="width: 100px; height: 100px; border-radius: 50px" class="mb-2">
            </div>
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
            <div>
                <div>
                    <input
                        id="id_projet"
                        type="hidden"
                        class="form-control"
                        name="projet_id"
                        placeholder="identifiant du projet"
                        value="{{ $projet->id }}"
                        readonly
                        required
                    />
                </div>
                <x-input-error :messages="$errors->get('projet_id')" class="mt-2 text-danger"/>
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
                    <select id="id_fonction" onchange="fonctionchange(this)" name="fonction" class="form-control" required>
                        <option selected disabled>-------------</option>
                        <option @if( old('fonction') === "coordinateur du projet" ) selected
                                @endif value="coordinateur du projet">coordinateur du projet
                        </option>
                        <option @if( old('fonction') === "chef de projet" ) selected
                                @endif value="chef de projet">chef de projet
                        </option>
                        <option @if( old('fonction') === "assistant suivi et évaluation" ) selected
                                @endif value="assistant suivi et évaluation">assistant suivi et évaluation
                        </option>
                        <option @if( old('fonction') === "superviseur" ) selected
                                @endif value="superviseur">superviseur
                        </option>
                        <option @if( old('fonction') === "animateur" ) selected
                                @endif value="animateur">animateur
                        </option>
                    </select>
                </div>
                <x-input-error :messages="$errors->get('fonction')" class="mt-2 text-danger"/>
            </div>
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
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="adresse">adresse de résidence</label>
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
                    <input id="id_telephone" name="telephone" type="text"
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
                            value="{{ old("email") }}"
                            name="email"
                        />
                        <span class="input-group-text" id="basic-addon2">@example.com</span>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger"/>
                </div>
                <div class="form-group form-group-default">
                    <label for="id_droits">type de compte</label>
                    <select id="id_droits" name="droits" class="form-control" required>
                        <option disabled>----------</option>
                        <option selected value="utilisateur">utilisateur</option>
                    </select>
                    <x-input-error :messages="$errors->get('droits')" class="mt-2 text-danger"/>
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
            </div>
        </form>
    </div>
    <br>
    <div>
        <div class="col-lg-12 p-0">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="col-md-12 p-0">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center" style="gap: 15px">
                                    <h4 class="card-title">axes de couverture du projet</h4>
                                    <button class="ms-auto bi-plus-circle-fill btn-sm btn-outline-info"
                                            style="border: none" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Modal -->
                                <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title">
                                                    <span class="fw-mediumbold"> nouvel</span>
                                                    <span class="fw-light"> enregistrement </span>
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="small">
                                                    créer un nouvel axe au projet en utilisant ce formulaire
                                                </p>
                                                <form id="form_axe">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group form-group-default">
                                                                <label>désignation de l'axe</label>
                                                                <input id="addName" name="designation" type="text"
                                                                       class="form-control" placeholder="désignation"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" id="addRowButtonAxe" class="btn btn-primary">
                                                        soumettre
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                                    fermer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="add-row" class="display table table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>designation de l'axe</th>
                                            <th style="width: 10%">action</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>designation de l'axe</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>
                                        @foreach($axes as $axe)
                                            <tr>
                                                <td>{{ $axe->designation }}</td>
                                                <td>
                                                    <div class="form-button-action">
                                                        <button type="button" value="{{ $axe->id }}" onclick="loadidaxe(this)" data-bs-toggle="modal"
                                                                data-bs-target="#ModalSupaxe" title=""
                                                                class="btn btn-link btn-danger"
                                                                data-original-title="remove">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">mois du cycle de gestion du projet</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table-sm w-100 table-striped table-hover">
                            <thead>
                                <tr style="background-color: #d9d9d9">
                                    <th style="text-transform: uppercase; font-weight: 450">n°</th>
                                    <th style="text-transform: uppercase; font-weight: 450">mois</th>
                                    <th style="text-transform: uppercase; font-weight: 450">désignation</th>
                                    <th style="text-transform: uppercase; font-weight: 450">action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cycle_de_gestion as $mois)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $mois->mois }}</td>
                                        <td>{{ $mois->designation }}</td>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" onclick="loadidmois(this)" data-bs-toggle="modal" data-bs-target="#addRowModalmois"
                                                    class="btn btn-link btn-primary btn-lg" value="{{ $mois->id }}"><i class="fa fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="shadow-lg bg-amber-50 bg-white">
        <form action="{{ route('projet.save_edition_projet', $projet->id) }}" style="padding: 12px" method="post"
              class="p-3">
            <h4>Informations générales sur le projet</h4>
            @csrf
            @method('put')
            <div class="mb-3">
                <label class="text-secondary">code de référence du projet</label>
                <input class="block mt-1 w-full form-control" type="text" name="code_reference"
                       value="{{ $projet->code_reference }}" required>
                <x-input-error :messages="$errors->get('code_reference')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <label>cycle de gestion</label>
                <input name="cycle_de_gestion" value="{{ $projet->cycle_de_gestion }}" type="number"
                       class="form-control"
                       placeholder="Durée du cycle de gestion du projet">
                <x-input-error :messages="$errors->get('cycle_de_gestion')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <x-input-label :value="__('date de début')"/>
                <input class="block mt-1 w-full form-control" type="date" name="date_de_debut"
                       value="{{ $projet->date_de_debut->format('Y-m-d') }}" required>
                <x-input-error :messages="$errors->get('date_de_debut')" class="mt-2 text-danger"/>
            </div>
            <div class="email mb-3">
                <x-input-label :value="__('date de fin')"/>
                <input class="block mt-1 w-full form-control" type="date" name="date_de_fin"
                       value="{{ $projet->date_de_fin->format('Y-m-d') }}" required>
                <x-input-error :messages="$errors->get('date de fin')" class="mt-2 text-danger"/>
            </div>
            <div class="email mb-3">
                <span>statut: </span><label class="text-primary"
                                            style="font-weight: bold">{{ $projet->statut }}</label>
            </div>
            <button type="submit" value="modifier" name="action" class="btn btn-primary text-light">
                appliquer les modifications
            </button>
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
        $(document).ready(function () {
            $("#basic-datatables").DataTable({});
            $('#form_axe').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('projet.ajouter_axe', $projet->id) }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $.notify({
                            icon: 'icon-bell',
                            title: 'Avecmanager',
                            message: 'ajouté',
                        }, {
                            type: 'secondary',
                            placement: {
                                from: "bottom",
                                align: "right"
                            },
                            time: 1000,
                        });
                    }, error: function (xhr) {
                        $.notify({
                            icon: 'icon-bell',
                            title: 'Avecmanager',
                            message: "une erreur s'est produite lors du traitement de la requête",
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
            })
            // Add Row
            $("#add-row").DataTable({
                pageLength: 5,
            });

            var action = '<td> <div class="form-button-action"><button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

            $("#addRowButtonAxe").click(function () {
                $("#add-row")
                    .dataTable()
                    .fnAddData([
                        $("#addName").val(),
                        action,
                    ]);
                $("#addRowModal").modal("hide");
            });
        });
    </script>
    <script src="{{ asset("js/personnal_scripts/file_validator.js") }}"></script>
@endsection
