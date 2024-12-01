@extends('base_guest')
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
            <span class="bi-file-word-fill" style="color: peru"> PROJET REFERENCE: {{ $projet->code_reference }}</span>
            <br><span style="text-transform: uppercase">AVEC: {{ $avec->designation }}</span>
        </div>
        <div class="col-md-4">
            <div class="btn-group dropdown" style="float: right;">
                <button class="btn dropdown-toggle" type="button" style="background-color: whitesmoke; color: darkblue" data-bs-toggle="dropdown" aria-expanded="false">
                    Options
                </button>
                <ul class="dropdown-menu p-2" role="menu" style="background-color: #ffffff; border: 1px solid blue">
                    <li>
                        <a class="dropdown-item btn btn-outline-secondary perso" href="{{ route("guest.report_transactions_du_membre", [$membre->id, $projet->id, $avec->id]) }}"><span class="bi-file-earmark-excel"> rélevé des transactions</span></a>
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
                    <p class="m-0 p-1 text-dark" style="text-align: center; font-weight: bold">
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
@endsection
