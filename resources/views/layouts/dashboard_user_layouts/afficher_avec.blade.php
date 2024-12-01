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
            <span style="color: peru; font-weight: 500" class="bi-file-word-fill">PROJET REFERENCE: {{ $projet->code_reference }}</span>
        </div>
        <div class="col-md-4">
            <div class="btn-group dropdown" style="float: right;">
                <button class="btn dropdown-toggle" type="button" style="background-color: whitesmoke; color: darkblue" data-bs-toggle="dropdown" aria-expanded="false">
                    Options
                </button>
                <ul class="dropdown-menu p-2" role="menu" style="background-color: #ffffff; border: 1px solid blue">
                    <li>
                        @if($projet->statut === "en cours")
                            @if($avec->membres->count() < 30)
                                @if($current_user->autorisations)
                                    @if(in_array("peux ajouter un membre dans l'avec", json_decode($current_user->autorisations, true)))
                                        <a class="dropdown-item btn btn-outline-secondary perso" href="{{ route('gestionprojet.ajouter_un_membre', $avec->id) }}"><span class="bi-plus-circle-fill text-primary"></span> Ajouter un membre</a>
                                    @endif
                                @endif
                            @endif
                            @if($current_user->autorisations)
                                @if(in_array("peux configurer une avec", json_decode($current_user->autorisations, true)))
                                    <a class="dropdown-item btn btn-outline-secondary perso" data-bs-toggle="modal" data-bs-target="#addRowModalcomite" href="#"><span class="bi-plus-circle-fill text-success"></span> Ajouter un comitard</a>
                                    <a class="dropdown-item btn btn-outline-secondary perso" data-bs-toggle="modal" data-bs-target="#addRowModalregleinteret" href="#"><span class="bi-plus-circle-fill text-info"></span> Règles de taxation des intérêts</a>
                                    <a class="dropdown-item btn btn-outline-secondary perso" data-bs-toggle="modal" data-bs-target="#addRowModalregleamande" href="#"><span class="bi-plus-circle-fill text-secondary"></span> Règles de taxation des amandes</a>
                                    <a class="dropdown-item btn btn-outline-secondary perso" data-bs-toggle="modal" data-bs-target="#addRowModalcasoctroi" href="#"><span class="bi-plus-circle-fill text-secondary"></span> Cas d'octroi du soutien solidarité</a>
                                @endif
                            @endif
                            <div class="dropdown-divider"></div>
                        @endif
                        <span>Rapports</span>
                        <a class="dropdown-item btn btn-outline-secondary perso" href="{{ route("rapports.releve_transactions_caisse_solidarite", [$avec->id, $projet->id]) }}"><span class="bi-file-earmark-excel-fill text-warning"> rélevé caisse solidarité</span></a>
                        <a class="dropdown-item btn btn-outline-secondary perso" href="{{ route("rapports.rapport_transactions_avec", [$avec->id, $projet->id]) }}"><span class="bi-file-earmark-excel-fill text-primary"> rapports des transactions</span></a>
                        <a class="dropdown-item btn btn-outline-secondary perso" href="{{ route("rapports.situation_generale_avec", [$avec->id, $projet->id]) }}"><span class="bi-search"> situation générale de l'avec</span></a>
                    </li>
                </ul>
            </div>
        </div>
        @if($projet->statut === "en cours")
            <div class="modal fade" id="addRowModalcasoctroi" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title">
                                <span class="fw-mediumbold"> nouveau cas d'octroi</span>
                                <span class="fw-light"> du soutien</span>
                            </h5>
                            <button type="button" class="close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p class="small">
                                ajouter un nouveau cas d'octroi du soutien solidarité
                            </p>
                            <form action="{{ route('gestionprojet.ajouter_cas_octroi_soutien', $avec->id) }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group form-group-default">
                                            <label for="id_cas">Cas d'octroi du soutien</label>
                                            <textarea name="cas" id="id_cas" class="form-control" cols="30" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" id="addRowButton" class="btn btn-primary">
                                    soumettre
                                </button>
                                <button type="button" class="btn btn-label-danger" data-bs-dismiss="modal">
                                    fermer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="addRowModalcomite" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title">
                                <span class="fw-mediumbold"> nouveau membre</span>
                                <span class="fw-light"> du comité </span>
                            </h5>
                            <button type="button" class="close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p class="small">
                                ajouter un membre au comité de l'avec en utilisant ce formulaire
                            </p>
                            <form action="{{ route("gestionprojet.ajouter_comite", $avec->id) }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group form-group-default">
                                            <label>Membre</label>
                                            <select name="membre_id" id="id_membre" class="form-control">
                                                @foreach($membres as $membre)
                                                    <option value="{{ $membre->id }}">{{ $membre->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group form-group-default">
                                            <label for="id_fonction">Fonction</label>
                                            <select name="fonction" id="id_fonction" class="form-control">
                                                <option value="président(e)">président(e)</option>
                                                <option value="secrétaire">secrétaire</option>
                                                <option value="trésorier(e)">trésorier(e)</option>
                                                <option value="compteur">compteur</option>
                                                <option value="gardien(ne) des clés">gardien(ne) des clés</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" id="addRowButton" class="btn btn-primary">
                                    soumettre
                                </button>
                                <button type="button" class="btn btn-label-danger" data-bs-dismiss="modal">
                                    fermer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="addRowModalregleinteret" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title">
                                <span class="fw-mediumbold"> nouvelle règle de</span>
                                <span class="fw-light"> taxation d'intérêt</span>
                            </h5>
                            <button type="button" class="close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p class="small">
                                ajouter une nouvelle règle de taxation d'intérêt en utilisant ce formulaire
                            </p>
                            <form action="{{ route('gestionprojet.ajouter_regle_de_taxation_interet', $avec->id) }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group form-group-default">
                                            <label for="id_enonce_regle">enoncée de la règle de taxation</label>
                                            <div class="input-group">
                                                <span class="input-group-text">énoncée</span>
                                                <textarea class="form-control" aria-label="enoncee" name="enonce_regle" style="height: 27px;"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-default">
                                            <label>valeur minimum</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text mr-2">FC</span>
                                                <input type="number" step="any" name="valeur_min" class="form-control" placeholder="valeur minimum" aria-label="min">
                                                <span class="input-group-text">.00</span>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-default">
                                            <label>valeur maximum</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text mr-2">FC</span>
                                                <input type="number" step="any" name="valeur_max" class="form-control" placeholder="valeur maximum" aria-label="max">
                                                <span class="input-group-text">.00</span>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-default">
                                            <label>taux d'intérêt</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text mr-2" id="basic-addon1">%</span>
                                                <input type="number" step="any" name="taux_interet" class="form-control border-1" placeholder=" taux d'intérêt" aria-label="taux" aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" id="addRowButton" class="btn btn-primary">
                                    soumettre
                                </button>
                                <button type="button" class="btn btn-label-danger" data-bs-dismiss="modal">
                                    fermer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="addRowModalregleamande" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title">
                                <span class="fw-mediumbold"> Règle de taxation</span>
                                <span class="fw-light"> des amandes </span>
                            </h5>
                            <button type="button" class="close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p class="small">
                                ajouter une règle de taxation des amandes en utilisant ce formulaire
                            </p>
                            <form action="{{ route("gestionprojet.ajouter_regle_de_taxation_amande", $avec->id) }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group form-group-default">
                                            <label for="id_enonce_regle">enoncée de la règle de taxation</label>
                                            <div class="input-group">
                                                <span class="input-group-text">énoncée</span>
                                                <textarea class="form-control" aria-label="regle" name="regle" style="height: 27px;"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-default">
                                            <label>montant de l'amande</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text mr-2">FC</span>
                                                <input type="number" step="any" name="amande" class="form-control" placeholder="montant de l'amande" aria-label="amande">
                                                <span class="input-group-text">.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" id="addRowButton" class="btn btn-primary">
                                    soumettre
                                </button>
                                <button type="button" class="btn btn-label-danger" data-bs-dismiss="modal">
                                    fermer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('page_courant', "vue de l'avec")
@section('content')
    <div class="modal fade" id="deleteRowmembre" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold"> Demande</span>
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
                        voulez-vous vraimemt supprimer ce membre?
                    </p>
                    <form action="{{ route("gestionprojet.supprimer_un_membre_avec") }}" method="post">
                        @csrf
                        @method("delete")

                        <input type="hidden" name="membre_id" id="membre_id" value="">
                        <button type="submit" id="addRowButton" class="btn btn-label-danger">
                            oui
                        </button>
                        <button type="button" class="btn btn-label-primary" data-bs-dismiss="modal">
                            non
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteRowModalregleamande" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 mb-0">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold"> Démande</span>
                        <span class="fw-light"> de confirmation</span>
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mt-0">
                    <div class="dropdown-divider" style="margin: 0"></div>
                    <p>
                        voulez-vous vraimemt supprimer cette règle?
                    </p>
                    <form action="{{ route('gestionprojet.supprimer_regle_de_taxation_amande') }}" method="post">
                        @csrf
                        @method("delete")

                        <input type="hidden" name="regle_id" id="regle_id" value="">
                        <button type="submit" id="addRowButton" class="btn btn-label-danger">
                            oui
                        </button>
                        <button type="button" class="btn btn-label-primary" data-bs-dismiss="modal">
                            non
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteRowModalregleinteret" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold"> Démande</span>
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
                        voulez-vous vraimemt supprimer cette règle?
                    </p>
                    <form action="{{ route('gestionprojet.supprimer_regle_de_taxation_interet') }}" method="post">
                        @csrf
                        @method("delete")

                        <input type="hidden" name="regle_id" value="" id="regleinteret_id">
                        <button type="submit" id="addRowButton" class="btn btn-label-danger">
                            oui
                        </button>
                        <button type="button" class="btn btn-label-primary" data-bs-dismiss="modal">
                            non
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteRowModalcas" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold"> Démande</span>
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
                        voulez-vous vraimemt supprimer ce cas?
                    </p>
                    <form action="{{ route('gestionprojet.supprimer_cas_octroi_soutien') }}" method="post">
                        @csrf
                        @method("delete")

                        <input type="hidden" name="cas_id" id="cas_id" value="">
                        <button type="submit" id="addRowButton" class="btn btn-label-danger">
                            oui
                        </button>
                        <button type="button" class="btn btn-label-primary" data-bs-dismiss="modal">
                            non
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-card-no-pd mb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <p class="text-primary">INFORMATIONS GENERALES DE L'AVEC</p>
                </div>
                @if($projet->statut === "en cours")
                    @if($current_user->autorisations)
                        @if(in_array("peux configurer une avec", json_decode($current_user->autorisations, true)))
                            <div class="col-md-6 col-xs-12">
                                <a class="btn text-secondary" style="float: right" data-bs-toggle="modal" data-bs-target="#settingsmodal" href="#"><span class="bi bi-gear-wide-connected text-dark"> Paramètres</span></a>
                                <div class="modal fade" id="settingsmodal" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title">
                                                    <span class="fw-mediumbold bi bi-gear-wide-connected text-primary"> paramètres</span>
                                                    <span class="fw-light"> de l'avec</span>
                                                </h5>
                                                <button type="button" class="close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="small">
                                                    paramètres de configuration de l'avec
                                                </p>
                                                <form action="{{ route("gestionprojet.edit_avec_configuration", $avec->id) }}" method="post">
                                                    @csrf
                                                    @method('put')
                                                    <div class="form-group form-group-default">
                                                        <label for="id_designation">nom de l'avec</label>
                                                        <input name="designation" id="id_designation" class="form-control" type="text" value="{{ $avec->designation }}">
                                                    </div>
                                                    <div class="form-group form-group-default">
                                                        <label for="id_valeur_part">valeur d'une part</label>
                                                        <input name="valeur_part" id="id_valeur_part" class="form-control" type="text" value="{{ $avec->valeur_part }}">
                                                    </div>
                                                    <div class="form-group form-group-default">
                                                        <label for="id_maximum_part_achetable">maximum des parts achetables</label>
                                                        <input name="maximum_part_achetable" id="id_maximum_part_achetable" class="form-control" type="text" value="{{ $avec->maximum_part_achetable }}">
                                                    </div>
                                                    <div class="form-group form-group-default">
                                                        <label for="id_valeur_montant_solidarite">valeur de la cotisation solidarité</label>
                                                        <input name="valeur_montant_solidarite" id="id_valeur_montant_solidarite" class="form-control" type="text" value="{{ $avec->valeur_montant_solidarite }}">
                                                    </div>
                                                    <div class="form-group form-group-default">
                                                        <label for="id_animateur">animateur</label>
                                                        <select name="animateur_id" id="id_animateur" class="form-control">
                                                            @foreach($animateurs as $animateur)
                                                                <option @if($animateur->id == $avec->animateur_id) selected @endif value="{{ $animateur->id }}">{{ $animateur->nom }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group form-group-default">
                                                        <label for="id_axe">axe</label>
                                                        <select name="axe_id" id="id_axe" class="form-control">
                                                            @foreach($axes as $axe)
                                                                <option @if($axe->id == $avec->axe_id) selected @endif value="{{ $axe->id }}">{{ $axe->designation }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button type="submit" id="addRowButton" class="btn btn-primary">
                                                        enregistrer
                                                    </button>
                                                    <button type="button" class="btn btn-label-danger" data-bs-dismiss="modal">
                                                        fermer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
            <div class="dropdown-divider"></div>
            <div class="row">
                <div class="col-12">
                    <p class="mb-0 mt-0"><span style="font-weight: bold">Nom de l'AVEC: </span><span style="text-transform: uppercase">{{ $avec->designation }}</span></p>
                    <p class="mb-0 mt-0"><span style="font-weight: bold">Axe de location: </span><span style="text-transform: uppercase">{{ $avec->axe->designation }}</span></p>
                    <p class="mb-0 mt-0"><span style="font-weight: bold">Animateur en charge: </span><span style="text-transform: uppercase">{{ $avec->animateur->nom }}</span></p>
                </div>
            </div>
            <div class="row">

            </div>
        </div>
    </div>
    <div class="row row-card-no-pd mt-0">
        <div class="col-sm-12 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="icon-pie-chart text-warning" style="font-size: 25pt"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">Valeur d'une part</p>
                                <h4 class="card-title">{{ $avec->valeur_part }} FC</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="icon-wallet text-success" style="font-size: 25pt"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">Maximum des parts achetables</p>
                                <h4 class="card-title">{{ $avec->maximum_part_achetable }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="icon-map text-danger" style="font-size: 25pt"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">Cotisation solidarité</p>
                                <h4 class="card-title">{{ $avec->valeur_montant_solidarite }} FC</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <h4 class="card-title">Tous les membres de l'avec</h4>
                        <button id="BtnExportToExcel" class="btn text-primary"><img style="width: 25px; height: 25px" src="{{ asset("assets/excel.png") }}" alt=""> Export</button>
                        <button id="BtnExportToPdf" class="btn text-primary"><img style="width: 25px; height: 25px" src="{{ asset("assets/pdf.png") }}" alt=""> Export</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table-sm w-100 table-striped table-hover">
                            <thead>
                            <tr class="ligne">
                                <th class="cell-th" style="background-color: #a2bcfc">nom</th>
                                <th class="cell-th" style="background-color: #a2bcfc">sexe</th>
                                <th class="cell-th" style="background-color: #a2bcfc">adresse</th>
                                <th class="cell-th" style="background-color: #a2bcfc">telephone</th>
                                <th class="cell-th" style="background-color: #a2bcfc">fonction</th>
                                <th class="cell-th" style="background-color: #a2bcfc">statut</th>
                                <th class="cell-th" style="background-color: #a2bcfc">gains</th>
                                <th style="background-color: #a2bcfc">photo</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>nom</th>
                                <th>sexe</th>
                                <th>adresse</th>
                                <th>telephone</th>
                                <th>fonction</th>
                                <th>statut</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($membres as $membre)
                                <tr class="ligne ln-pdf">
                                    <td class="cell-td">{{ $membre->nom }}</td>
                                    <td class="cell-td">{{ $membre->sexe }}</td>
                                    <td class="cell-td">{{ $membre->adresse }}</td>
                                    <td class="cell-td">{{ $membre->numeros_de_telephone }}</td>
                                    <td class="cell-td">{{ $membre->fonction ? $membre->fonction->fonction: "" }}</td>
                                    <td @class(["badge", "cell-td", "ml-2", "text-white", "bg-danger"=>$membre->statut === "abandon", "bg-success"=>$membre->statut === "actif", "bg-warning"=>$membre->statut === "inactif"])>{{ $membre->statut }}</td>
                                    <td class="cell-td">{{ $membre->gains }} FC</td>
                                    <td>
                                        <div class="avatar">
                                            <img src="/storage/{{ $membre->photo }}" alt="..." class="avatar-img rounded-circle">
                                        </div>
                                    </td>
                                    <td class="d-flex">
                                        <a class="btn-sm text-success" href="{{ route('gestionprojet.afficher_un_membre', $membre->id) }}"><span class="bi-eye"></span></a>
                                        @if($projet->statut === "en cours")
                                            @if($current_user->autorisations)
                                                @if(in_array("peux supprimer un membre", json_decode($current_user->autorisations, true)) && \Carbon\Carbon::parse($membre->created_at)->diffInDays() <= 7)
                                                    @if($membre->transactions->count() == 0)
                                                        <button class="btn btn-sm text-danger" value="{{ $membre->id }}" onclick="loadidmembre(this)" data-bs-toggle="modal" data-bs-target="#deleteRowmembre">
                                                            <span class="bi-trash"></span>
                                                        </button>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Règles de taxation des amandes dans l'avec</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select-regles_amandes" class="display table-sm w-100 table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="background-color: #bac8ff">Enoncé de la règle</th>
                                <th style="background-color: #bac8ff">Amande</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Enoncé de la règle</th>
                                <th>Amande</th>
                            </tr>
                            </tfoot>
                            <tbody>
                                @foreach($regles_de_taxation_des_amandes as $regle)
                                    <tr>
                                        <td>{{ $regle->regle }}</td>
                                        <td>{{ $regle->amande }} FC</td>
                                        <td>
                                            @if($projet->statut === "en cours")
                                                @if($current_user->autorisations)
                                                    @if(in_array("peux configurer une avec", json_decode($current_user->autorisations, true)))
                                                        <button class="btn btn-sm text-danger" value="{{ $regle->id }}" onclick="loadidregleamande(this)" data-bs-toggle="modal" data-bs-target="#deleteRowModalregleamande"><span class="bi-trash"></span></button>
                                                    @endif
                                                @endif
                                            @endif
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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Règles de taxation des intérêts dans l'avec</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select-regles_interets" class="display table-sm w-100 table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="background-color: #bac8ff">Enoncé de la règle</th>
                                <th style="background-color: #bac8ff">montant minimum</th>
                                <th style="background-color: #bac8ff">montant maximum</th>
                                <th style="background-color: #bac8ff">taux d'intérêt</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Enoncé de la règle</th>
                                <th>montant minimum</th>
                                <th>montant maximum</th>
                                <th>taux d'intérêt</th>
                            </tr>
                            </tfoot>
                            <tbody>
                                @foreach($regles_de_taxation_des_interets as $regle)
                                    <tr>
                                        <td title="{{ $regle->enonce_regle }}">{{ Str::limit($regle->enonce_regle, 50) }}</td>
                                        <td>{{ $regle->valeur_min }} FC</td>
                                        <td>{{ $regle->valeur_max }} FC</td>
                                        <td>{{ $regle->taux_interet }} %</td>
                                        <td>
                                            @if($projet->statut === "en cours")
                                                @if($current_user->autorisations)
                                                    @if(in_array("peux configurer une avec", json_decode($current_user->autorisations, true)))
                                                        <button class="btn btn-sm text-danger" value="{{ $regle->id }}" onclick="loadidregleinteret(this)" data-bs-toggle="modal" data-bs-target="#deleteRowModalregleinteret"><span class="bi-trash"></span></button>
                                                    @endif
                                                @endif
                                            @endif
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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Cas d'octroi de l'assistance solidarité</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select-cas" class="display table-sm w-100 table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="background-color: #bac8ff">cas</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>cas</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($cas_octroi_soutien as $cas)
                                <tr>
                                    <td title="{{ $cas->cas }}">{{ Str::limit($cas->cas, 150) }}</td>
                                    <td>
                                        @if($projet->statut === "en cours")
                                            @if($current_user->autorisations)
                                                @if(in_array("peux configurer une avec", json_decode($current_user->autorisations, true)))
                                                    <button class="btn btn-sm text-danger" value="{{ $cas->id }}" onclick="loadidcasoctroi(this)" data-bs-toggle="modal" data-bs-target="#deleteRowModalcas">
                                                        <span class="bi-trash"></span>
                                                    </button>
                                                @endif
                                            @endif
                                        @endif
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

        function loadidmembre(element) {
            let input_id = document.getElementById("membre_id");
            input_id.value = element.value
        }
        function loadidregleamande(element) {
            let input_id = document.getElementById("regle_id");
            input_id.value = element.value
        }
        function loadidregleinteret(element) {
            let input_id = document.getElementById("regleinteret_id");
            input_id.value = element.value
        }
        function loadidcasoctroi(element) {
            let input_id = document.getElementById("cas_id");
            input_id.value = element.value
        }

        $(document).ready(function () {
            $("#multi-filter-select").DataTable({
                pageLength: 5,
                initComplete: function () {
                    this.api()
                    .columns()
                    .every(function () {
                        var column = this;
                        var select = $(
                            '<select class="form-select"><option value=""></option></select>'
                        )
                        .appendTo($(column.footer()).empty())
                        .on("change", function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());

                            column
                                .search(val ? "^" + val + "$" : "", true, false)
                                .draw();
                        });

                        column
                        .data()
                        .unique()
                        .sort()
                        .each(function (d, j) {
                            select.append(
                                '<option value="' + d + '">' + d + "</option>"
                            );
                        });
                    });
                },
            });
            $("#multi-filter-select-regles_amandes").DataTable({
                pageLength: 5,
                initComplete: function () {
                    this.api()
                        .columns()
                        .every(function () {
                            var column = this;
                            var select = $(
                                '<select class="form-select"><option value=""></option></select>'
                            )
                                .appendTo($(column.footer()).empty())
                                .on("change", function () {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                    column
                                        .search(val ? "^" + val + "$" : "", true, false)
                                        .draw();
                                });

                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function (d, j) {
                                    select.append(
                                        '<option value="' + d + '">' + d + "</option>"
                                    );
                                });
                        });
                },
            });
            $("#multi-filter-select-regles_interets").DataTable({
                pageLength: 5,
                initComplete: function () {
                    this.api()
                        .columns()
                        .every(function () {
                            var column = this;
                            var select = $(
                                '<select class="form-select"><option value=""></option></select>'
                            )
                                .appendTo($(column.footer()).empty())
                                .on("change", function () {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                    column
                                        .search(val ? "^" + val + "$" : "", true, false)
                                        .draw();
                                });

                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function (d, j) {
                                    select.append(
                                        '<option value="' + d + '">' + d + "</option>"
                                    );
                                });
                        });
                },
            });
            $("#multi-filter-select-cas").DataTable({
                pageLength: 5,
                initComplete: function () {
                    this.api()
                        .columns()
                        .every(function () {
                            var column = this;
                            var select = $(
                                '<select class="form-select"><option value=""></option></select>'
                            )
                                .appendTo($(column.footer()).empty())
                                .on("change", function () {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                    column
                                        .search(val ? "^" + val + "$" : "", true, false)
                                        .draw();
                                });

                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function (d, j) {
                                    select.append(
                                        '<option value="' + d + '">' + d + "</option>"
                                    );
                                });
                        });
                },
            });
        });

        //Exporter en excel
        document.getElementById("BtnExportToExcel").addEventListener('click', async function () {
            //import ExcelJS et FileSaver
            const ExcelJS = window.ExcelJS;
            const saveAs = window.saveAs;

            //créer un classeur et une feuille
            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet("liste des membres");

            //Ajouter un titre et définir son style
            worksheet.mergeCells("A1:G1");
            const titleCell = worksheet.getCell("A1");
            titleCell.value = "LISTE DES MEMBRES DE L'AVEC {{ strtoupper($avec->designation) }}\n";
            titleCell.font = {name: "Times Roman", size: 12, bold: true, color: {argb: 'FFFFFFFF'}};
            titleCell.alignment = {vertical: "top", horizontal: "center", wrapText: true};
            titleCell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: {argb: 'FF000024'},
            };

            //Ajouter les données du tableau HTML
            const table = document.getElementById("multi-filter-select");
            const rows = table.querySelectorAll(".ligne");

            //Ajouter les lignes du tableau dans ExcelJS
            rows.forEach((row, rowIndex) => {
                const cells = row.querySelectorAll(".cell-th, .cell-td");
                cells.forEach((cell, colIndex) => {
                    const excelCell = worksheet.getCell(rowIndex + 3, colIndex + 1); //début à la ligne 3
                    excelCell.value = cell.textContent;

                    //Appliquer un style
                    excelCell.font = {name: "Times Roman", size: 11};
                    excelCell.border = {
                        top: {style: 'thin'},
                        left: {style: 'thin'},
                        bottom: {style: 'thin'},
                        right: {style: 'thin'},
                    }
                    excelCell.alignment = {wrapText: true, vertical: 'middle', horizontal: 'center'};
                    if (rowIndex === 0) {
                        excelCell.font = {bold: true, color: {argb: 'FFFFFFFF'} };
                        excelCell.fill = {
                            type: 'pattern',
                            pattern: 'solid',
                            fgColor: {argb: 'FF404040'},
                        };
                    }
                });
            });
            worksheet.columns.forEach(column => {
                // let maxWidth = 10;
                // column.eachCell({includeEmpty: true}, cell => {
                //     if (cell.value) {
                //         const cellLength = cell.value.toString().length;
                //         maxWidth = Math.max(maxWidth, cellLength + 2);
                //     }
                // })
                column.width = 17;
            });

            workbook.xlsx.writeBuffer().then((data) => {
                const blob = new Blob([data], {type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'});
                saveAs(blob, 'liste des membres.xlsx');
            })
        })

        //Exporter en pdf
        document.getElementById("BtnExportToPdf").addEventListener("click", function () {
            const table = document.getElementById("multi-filter-select");
            const rows = table.querySelectorAll(".ln-pdf");

            const pdfTable = [
                [
                    {text: "nom", bold: true, style: "headerstyle"},
                    {text: "sexe", bold: true, style: "headerstyle"},
                    {text: "adresse", bold: true, style: "headerstyle"},
                    {text: "téléphone", bold: true, style: "headerstyle"},
                    {text: "fonction", bold: true, style: "headerstyle"},
                    {text: "statut", bold: true, style: "headerstyle"},
                    {text: "gains", bold: true, style: "headerstyle"},
                ]
            ];
            rows.forEach(row => {
                const cells = row.querySelectorAll(".cell-td")
                const rowData = Array.from(cells).map(cell => cell.textContent);
                pdfTable.push(rowData);
            })
            const docDefinition = {
                defaultStyle: {
                    fontSize: 9,
                },
                pageOrientation: "landscape",
                content: [
                    {
                        table: {
                            widths: ['*'],
                            body: [
                                [{text: "LISTE DES MEMBRES DE L'AVEC {{ strtoupper($avec->designation) }}", style: "header"}],
                                [""],
                                [""],
                            ]
                        },
                        layout: 'noBorders',
                        fillColor: "#5264c0",
                    },
                    {text: "\n\n\n"},
                    {table: {
                            headerRows: 1,
                            widths: ["*", "*", "*", "*", "*", "*", "*"],
                            body: pdfTable,
                        }
                    }
                ],
                styles: {
                    header: {fontSize: 16, bold: true, color: "white", alignment:"center", margin: [0, 0, 0, 10]},
                    title: {fontSize: 12, bold:true, alignment: "center"},
                    paragraphe1: {fontSize: 9, bold: true},
                    paragraphe2: {fontSize: 9, margin: ["50%", 0, 0, 0]},
                    headerstyle: {
                        color: "black",
                        fillColor: "#89c8ff",
                        alignment: "center",
                    }
                }
            }
            pdfMake.createPdf(docDefinition).download('liste des membres.pdf');
        })
    </script>
@endsection
