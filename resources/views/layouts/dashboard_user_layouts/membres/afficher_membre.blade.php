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
        <div class="col-md-4">
            <div class="btn-group dropdown" style="float: right;">
                <button class="btn dropdown-toggle" type="button" style="background-color: whitesmoke; color: darkblue" data-bs-toggle="dropdown" aria-expanded="false">
                    Options
                </button>
                <ul class="dropdown-menu p-2" role="menu" style="background-color: #ffffff; border: 1px solid blue">
                    <li>
                        @if($projet->statut === "en cours")
                            @if($avec->membres->count() >= 15 && $avec->membres->count() <= 30 || $transactionsCount != 0)
                                @if($membre->statut === "actif")
                                    @if($current_user->autorisations)
                                        @if(in_array("peux faire une transaction", json_decode($current_user->autorisations, true)))
                                            <a class="dropdown-item btn btn-outline-secondary perso" href="{{ route('gestionprojet.transactions_hebdomadaire', $membre->id) }}"><span class="bi-currency-exchange text-primary"></span> nouvelle transaction</a>
                                            <div class="dropdown-divider"></div>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        @endif
                        <a class="dropdown-item btn btn-outline-secondary perso" href="{{ route("rapports.rapport_transactions_membre", [$membre->id, $projet->id, $avec->id]) }}"><span class="bi-file-earmark-excel"> rélevé des transactions</span></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('page_courant', "membre de l'avec")
@section('content')
    <hr>
    <div class="row row-card-no-pd" style="background-color: transparent">
        <div class="col-sm-12 col-md-6">
            <div class="card card-stats card-round"  style="background-color: transparent">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                @if($membre->statut === "actif")
                                    <img src="/storage/{{ $membre->photo }}" style="width: 100px; height: 100px; border: 2px solid green; border-radius: 50px" class="mb-2" alt="...">
                                    <div class="avatar avatar-online">
                                        <img src="/storage/{{ $membre->photo }}" alt="..." class="avatar-img rounded-circle">
                                    </div>
                                @elseif($membre->statut === "abandon")
                                    <img src="/storage/{{ $membre->photo }}" style="width: 100px; height: 100px; border: 2px solid red; border-radius: 50px" class="mb-2" alt="...">
                                    <div class="avatar avatar-offline">
                                        <img src="/storage/{{ $membre->photo }}" alt="..." class="avatar-img rounded-circle">
                                    </div>
                                @else
                                    <img src="/storage/{{ $membre->photo }}" style="width: 100px; height: 100px; border: 2px solid orange; border-radius: 50px" class="mb-2" alt="...">
                                    <div class="avatar avatar-away">
                                        <img src="/storage/{{ $membre->photo }}" alt="..." class="avatar-img rounded-circle">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-7 col-stats m-0 p-0">
                            <div class="numbers">
                                <p class="m-0 text-primary" style="font-weight: bold">MEMBRE ID: {{ $membre->id }}</p>
                                <p class="m-0 text-secondary">{{ $membre->nom}}</p>
                                <p class="m-0">Sexe: {{ $membre->sexe}}</p>
                                <p class="m-0">Télephone: {{ $membre->numeros_de_telephone}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="card card-stats card-round p-3">
                <div class="card-body">
                    <h4>situation du membre</h4>
                    <div class="row shadow-sm">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="icon-pie-chart text-primary" style="font-size: 18pt"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">total des parts achetées</p>
                                <h4 class="card-title text-primary">{{ $membre->part_tot_achetees }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row shadow-sm">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="bx bxs-bank text-success" style="font-size: 22pt"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">montant total épargné</p>
                                <h4 class="card-title text-success">@php $montant = $membre->part_tot_achetees * $avec->valeur_part @endphp {{ $montant }} FC</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row shadow-sm">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="fa fa-coins text-warning" style="font-size: 22pt"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">gains</p>
                                <h4 class="card-title text-warning">
                                    {{ $membre->gains }} FC
                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown-divider"></div>
                    <div class="row shadow-sm">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="fa fa-coins text-danger mr-1" style="font-size: 22pt"></i><i class="fa fa-minus text-danger"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">prêt</p>
                                <h4 class="card-title text-danger">
                                    @php
                                        $credit_a_rembourser = $membre->credit + $membre->interets_sur_credit;
                                    @endphp
                                    {{ $credit_a_rembourser }} FC
                                </h4>
                            </div>
                        </div>
                    </div>
                    <p class="m-0 p-1 text-danger" style="text-align: center; font-weight: bold">
                        date de remboursement: <span @if($membre->date_de_remboursement <= \Carbon\Carbon::today()) @class(['bx-flashing']) @endif>
                            @php
                                $date = $membre->date_de_remboursement ? $membre->date_de_remboursement->format('d/m/Y') : "";
                            @endphp
                            {{ $date }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @if($projet->statut === "en cours")
        @if($membre->statut != "abandon")
            <div class="p-4 shadow-lg">
                <h5><span class="fa fa-arrow-alt-circle-right"></span> modifier le profile du membre ici!</h5>
                @if(!is_null($membre_fonction))
                    <span class="mb-3" style="color: #0a53be; font-weight: bold">fonction de @if($membre->sexe === "homme")
                            monsieur
                        @else
                            madame
                        @endif {{$membre->nom}} dans l'avec:</span> {{ $membre_fonction->fonction }}
                    <a href="#" data-bs-toggle="modal" data-bs-target="#ModalSup" class="text-danger mb-4"><span
                            class="fa fa-arrow-alt-circle-right"> supprimer la fonction</span></a>
                    <div class="modal fade" id="ModalSup" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">
                                        <span class="fw-mediumbold"> demande de</span>
                                        <span class="fw-light"> confirmation</span>
                                    </h5>
                                    <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p class="small">
                                        voulez-vous vraiment rétirer cette fonction à ce membre ?
                                    </p>
                                    <form action="{{ route("gestionprojet.supprimer_fonction_membre", $membre->id) }}"
                                          method="post">
                                        @csrf
                                        @method("delete")
                                        <button type="submit" id="addRowButton" class="btn btn-primary">
                                            oui
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            non
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <br><br>
                <form method="post" action="{{ route("gestionprojet.editer_un_membre", $membre->id) }}"
                      class="mt-6 space-y-6"
                      enctype="multipart/form-data">
                    @csrf
                    @method('put')
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
                            <label for="id_statut">statut du membre</label>
                            <select name="statut" id="id_statut" class="form-control">
                                <option @if($membre->statut === "actif") selected @endif value="actif">actif</option>
                                <option @if($membre->statut === "inactif") selected @endif value="inactif">inactif</option>
                                <option @if($membre->statut === "abandon") selected @endif value="abandon">abandon</option>
                            </select>
                        </div>
                        <x-input-error :messages="$errors->get('nom')" class="mt-2 text-danger"/>
                    </div>
                    <div class="mb-3">
                        <div class="form-group form-group-default">
                            <label for="nom">nom</label>
                            <input
                                id="nom"
                                type="text"
                                class="form-control"
                                name="nom"
                                accept=".jpeg, .jpg, .png"
                                onchange="file_validate(this)"
                                placeholder="nom de l'utilisateur"
                                value="{{ $membre->nom }}"
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
                                <option @if($membre->sexe === "homme") selected @endif value="homme">homme</option>
                                <option @if($membre->sexe === "femme") selected @endif value="femme">femme</option>
                            </select>
                        </div>
                        <x-input-error :messages="$errors->get('sexe')" class="mt-2 text-danger"/>
                    </div>
                    <div class="mb-3">
                        <div class="form-group form-group-default">
                            <label for="adresse">adresse</label>
                            <input id="adresse" name="adresse" type="text"
                                   class="form-control"
                                   value="{{ $membre->adresse }}" required
                                   placeholder="adresse de résidence actuelle"/>
                        </div>
                        <x-input-error :messages="$errors->get('adresse')" class="mt-2 text-danger"/>
                    </div>
                    <div class="mb-3">
                        <div class="form-group form-group-default">
                            <label for="id_telephone">Téléphone</label>
                            <input id="id_telephone" name="numeros_de_telephone" type="number"
                                   class="form-control"
                                   value="{{ $membre->numeros_de_telephone }}" required
                                   placeholder="0000000000"/>
                        </div>
                        <x-input-error :messages="$errors->get('numeros_de_telephone')" class="mt-2 text-danger"/>
                    </div>

                    <div class="flex items-center mt-2 gap-4">
                        <button class="btn btn-primary text-light" type="submit"> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        @endif
    @endif
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

        @if($alert_remboursement)
            $(document).ready(function () {
                $.notify({
                    icon: 'icon-bell',
                    title: 'alerte remboursement de dette',
                    message: '{{ $alert_remboursement }}',
                }, {
                    type: 'warning',
                    placement: {
                        from: "top",
                        align: "center"
                    },
                    time:15000,
                });
            });
        @endif
    </script>
    <script src="{{ asset("js/personnal_scripts/file_validator.js") }}"></script>
@endsection
