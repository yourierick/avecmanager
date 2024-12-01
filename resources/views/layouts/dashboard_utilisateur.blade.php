@extends('base_utilisateur')
@section('big_title')
    <span style="color: peru" class="bi-file-word-fill"> PROJET REFERENCE: {{ $projet->code_reference }}</span>
@endsection
@section('page_courant', 'vue du projet')
@section('style')
    <style>
        .list-timeline {
            margin: 0;
            padding: 5px 0;
            position: relative
        }

        .list-timeline:before {
            width: 1px;
            background: #ccc;
            position: absolute;
            left: 6px;
            top: 0;
            bottom: 0;
            height: 100%;
            content: ''
        }

        .list-timeline .list-timeline-item {
            margin: 0;
            padding: 0;
            padding-left: 24px !important;
            position: relative
        }

        .list-timeline .list-timeline-item:before {
            width: 12px;
            height: 12px;
            background: #fff;
            border: 2px solid #ccc;
            position: absolute;
            left: 0;
            top: 4px;
            content: '';
            border-radius: 100%;
            -webkit-transition: all .3 ease-in-out;
            transition: all .3 ease-in-out
        }

        .list-timeline .list-timeline-item[data-toggle=collapse] {
            cursor: pointer
        }

        .list-timeline .list-timeline-item.active:before,
        .list-timeline .list-timeline-item.show:before {
            background: #ccc
        }

        .list-timeline.list-timeline-light .list-timeline-item.active:before,
        .list-timeline.list-timeline-light .list-timeline-item.show:before,
        .list-timeline.list-timeline-light:before {
            background: #f8f9fa
        }

        .list-timeline .list-timeline-item.list-timeline-item-marker-middle:before {
            top: 50%;
            margin-top: -6px
        }

        .list-timeline.list-timeline-light .list-timeline-item:before {
            border-color: #f8f9fa
        }

        .list-timeline.list-timeline-grey .list-timeline-item.active:before,
        .list-timeline.list-timeline-grey .list-timeline-item.show:before,
        .list-timeline.list-timeline-grey:before {
            background: #e9ecef
        }

        .list-timeline.list-timeline-grey .list-timeline-item:before {
            border-color: #e9ecef
        }

        .list-timeline.list-timeline-grey-dark .list-timeline-item.active:before,
        .list-timeline.list-timeline-grey-dark .list-timeline-item.show:before,
        .list-timeline.list-timeline-grey-dark:before {
            background: #495057
        }

        .list-timeline.list-timeline-grey-dark .list-timeline-item:before {
            border-color: #495057
        }

        .list-timeline.list-timeline-primary .list-timeline-item.active:before,
        .list-timeline.list-timeline-primary .list-timeline-item.show:before,
        .list-timeline.list-timeline-primary:before {
            background: #55A79A
        }

        .list-timeline.list-timeline-primary .list-timeline-item:before {
            border-color: #55A79A
        }

        .list-timeline.list-timeline-primary-dark .list-timeline-item.active:before,
        .list-timeline.list-timeline-primary-dark .list-timeline-item.show:before,
        .list-timeline.list-timeline-primary-dark:before {
            background: #33635c
        }

        .list-timeline.list-timeline-primary-dark .list-timeline-item:before {
            border-color: #33635c
        }

        .list-timeline.list-timeline-primary-faded .list-timeline-item.active:before,
        .list-timeline.list-timeline-primary-faded .list-timeline-item.show:before,
        .list-timeline.list-timeline-primary-faded:before {
            background: rgba(85, 167, 154, .3)
        }

        .list-timeline.list-timeline-primary-faded .list-timeline-item:before {
            border-color: rgba(85, 167, 154, .3)
        }

        .list-timeline.list-timeline-info .list-timeline-item.active:before,
        .list-timeline.list-timeline-info .list-timeline-item.show:before,
        .list-timeline.list-timeline-info:before {
            background: #17a2b8
        }

        .list-timeline.list-timeline-info .list-timeline-item:before {
            border-color: #17a2b8
        }

        .list-timeline.list-timeline-success .list-timeline-item.active:before,
        .list-timeline.list-timeline-success .list-timeline-item.show:before,
        .list-timeline.list-timeline-success:before {
            background: #28a745
        }

        .list-timeline.list-timeline-success .list-timeline-item:before {
            border-color: #28a745
        }

        .list-timeline.list-timeline-warning .list-timeline-item.active:before,
        .list-timeline.list-timeline-warning .list-timeline-item.show:before,
        .list-timeline.list-timeline-warning:before {
            background: #ffc107
        }

        .list-timeline.list-timeline-warning .list-timeline-item:before {
            border-color: #ffc107
        }

        .list-timeline.list-timeline-danger .list-timeline-item.active:before,
        .list-timeline.list-timeline-danger .list-timeline-item.show:before,
        .list-timeline.list-timeline-danger:before {
            background: #dc3545
        }

        .list-timeline.list-timeline-danger .list-timeline-item:before {
            border-color: #dc3545
        }

        .list-timeline.list-timeline-dark .list-timeline-item.active:before,
        .list-timeline.list-timeline-dark .list-timeline-item.show:before,
        .list-timeline.list-timeline-dark:before {
            background: #343a40
        }

        .list-timeline.list-timeline-dark .list-timeline-item:before {
            border-color: #343a40
        }

        .list-timeline.list-timeline-secondary .list-timeline-item.active:before,
        .list-timeline.list-timeline-secondary .list-timeline-item.show:before,
        .list-timeline.list-timeline-secondary:before {
            background: #6c757d
        }

        .list-timeline.list-timeline-secondary .list-timeline-item:before {
            border-color: #6c757d
        }

        .list-timeline.list-timeline-black .list-timeline-item.active:before,
        .list-timeline.list-timeline-black .list-timeline-item.show:before,
        .list-timeline.list-timeline-black:before {
            background: #000
        }

        .list-timeline.list-timeline-black .list-timeline-item:before {
            border-color: #000
        }

        .list-timeline.list-timeline-white .list-timeline-item.active:before,
        .list-timeline.list-timeline-white .list-timeline-item.show:before,
        .list-timeline.list-timeline-white:before {
            background: #fff
        }

        .list-timeline.list-timeline-white .list-timeline-item:before {
            border-color: #fff
        }

        .list-timeline.list-timeline-green .list-timeline-item.active:before,
        .list-timeline.list-timeline-green .list-timeline-item.show:before,
        .list-timeline.list-timeline-green:before {
            background: #55A79A
        }

        .list-timeline.list-timeline-green .list-timeline-item:before {
            border-color: #55A79A
        }

        .list-timeline.list-timeline-red .list-timeline-item.active:before,
        .list-timeline.list-timeline-red .list-timeline-item.show:before,
        .list-timeline.list-timeline-red:before {
            background: #BE3E1D
        }

        .list-timeline.list-timeline-red .list-timeline-item:before {
            border-color: #BE3E1D
        }

        .list-timeline.list-timeline-blue .list-timeline-item.active:before,
        .list-timeline.list-timeline-blue .list-timeline-item.show:before,
        .list-timeline.list-timeline-blue:before {
            background: #00ADBB
        }

        .list-timeline.list-timeline-blue .list-timeline-item:before {
            border-color: #00ADBB
        }

        .list-timeline.list-timeline-purple .list-timeline-item.active:before,
        .list-timeline.list-timeline-purple .list-timeline-item.show:before,
        .list-timeline.list-timeline-purple:before {
            background: #b771b0
        }

        .list-timeline.list-timeline-purple .list-timeline-item:before {
            border-color: #b771b0
        }

        .list-timeline.list-timeline-pink .list-timeline-item.active:before,
        .list-timeline.list-timeline-pink .list-timeline-item.show:before,
        .list-timeline.list-timeline-pink:before {
            background: #CC164D
        }

        .list-timeline.list-timeline-pink .list-timeline-item:before {
            border-color: #CC164D
        }

        .list-timeline.list-timeline-orange .list-timeline-item.active:before,
        .list-timeline.list-timeline-orange .list-timeline-item.show:before,
        .list-timeline.list-timeline-orange:before {
            background: #e67e22
        }

        .list-timeline.list-timeline-orange .list-timeline-item:before {
            border-color: #e67e22
        }

        .list-timeline.list-timeline-lime .list-timeline-item.active:before,
        .list-timeline.list-timeline-lime .list-timeline-item.show:before,
        .list-timeline.list-timeline-lime:before {
            background: #b1dc44
        }

        .list-timeline.list-timeline-lime .list-timeline-item:before {
            border-color: #b1dc44
        }

        .list-timeline.list-timeline-blue-dark .list-timeline-item.active:before,
        .list-timeline.list-timeline-blue-dark .list-timeline-item.show:before,
        .list-timeline.list-timeline-blue-dark:before {
            background: #34495e
        }

        .list-timeline.list-timeline-blue-dark .list-timeline-item:before {
            border-color: #34495e
        }

        .list-timeline.list-timeline-red-dark .list-timeline-item.active:before,
        .list-timeline.list-timeline-red-dark .list-timeline-item.show:before,
        .list-timeline.list-timeline-red-dark:before {
            background: #a10f2b
        }

        .list-timeline.list-timeline-red-dark .list-timeline-item:before {
            border-color: #a10f2b
        }

        .list-timeline.list-timeline-brown .list-timeline-item.active:before,
        .list-timeline.list-timeline-brown .list-timeline-item.show:before,
        .list-timeline.list-timeline-brown:before {
            background: #91633c
        }

        .list-timeline.list-timeline-brown .list-timeline-item:before {
            border-color: #91633c
        }

        .list-timeline.list-timeline-cyan-dark .list-timeline-item.active:before,
        .list-timeline.list-timeline-cyan-dark .list-timeline-item.show:before,
        .list-timeline.list-timeline-cyan-dark:before {
            background: #008b8b
        }

        .list-timeline.list-timeline-cyan-dark .list-timeline-item:before {
            border-color: #008b8b
        }

        .list-timeline.list-timeline-yellow .list-timeline-item.active:before,
        .list-timeline.list-timeline-yellow .list-timeline-item.show:before,
        .list-timeline.list-timeline-yellow:before {
            background: #D4AC0D
        }

        .list-timeline.list-timeline-yellow .list-timeline-item:before {
            border-color: #D4AC0D
        }

        .list-timeline.list-timeline-slate .list-timeline-item.active:before,
        .list-timeline.list-timeline-slate .list-timeline-item.show:before,
        .list-timeline.list-timeline-slate:before {
            background: #5D6D7E
        }

        .list-timeline.list-timeline-slate .list-timeline-item:before {
            border-color: #5D6D7E
        }

        .list-timeline.list-timeline-olive .list-timeline-item.active:before,
        .list-timeline.list-timeline-olive .list-timeline-item.show:before,
        .list-timeline.list-timeline-olive:before {
            background: olive
        }

        .list-timeline.list-timeline-olive .list-timeline-item:before {
            border-color: olive
        }

        .list-timeline.list-timeline-teal .list-timeline-item.active:before,
        .list-timeline.list-timeline-teal .list-timeline-item.show:before,
        .list-timeline.list-timeline-teal:before {
            background: teal
        }

        .list-timeline.list-timeline-teal .list-timeline-item:before {
            border-color: teal
        }

        .list-timeline.list-timeline-green-bright .list-timeline-item.active:before,
        .list-timeline.list-timeline-green-bright .list-timeline-item.show:before,
        .list-timeline.list-timeline-green-bright:before {
            background: #2ECC71
        }

        .list-timeline.list-timeline-green-bright .list-timeline-item:before {
            border-color: #2ECC71
        }
    </style>
@endsection
@section('content')
    <div>
        <p style="font-weight: bold">Contexte du projet</p>
        <div class="ml-4">
            <p>{{ $projet->context }}</p>
        </div>
    </div>
    <div class="row row-card-no-pd" style="background-color: #f1f1f1">
        @if($current_user->fonction === "chef de projet" || $current_user->fonction === "coordinateur du projet" || $current_user->fonction === "assistant suivi et évaluation")
            <div class="col-sm-6 col-md-3">
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
                                    <p class="card-category">Equipe</p>
                                    <h4 class="card-title">{{ $equipedegestion->count() }}</h4>
                                    <a href="{{ route('gestionprojet.lists_personnel', $projet->id) }}" class="small-box-footer">voir plus <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
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
                                    <p class="card-category">Cycle</p>
                                    <h4 class="card-title">{{ $projet->cycle_de_gestion }} mois</h4>
                                    <span>....</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
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
                                    <p class="card-category">Axes</p>
                                    <h4 class="card-title">{{ $axes->count() }}</h4>
                                    <span>....</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="icon-people text-primary" style="font-size: 25pt"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">Avecs</p>
                                    <h4 class="card-title">{{ $avecs->count() }}</h4>
                                    <a href="{{ route('gestionprojet.list_avecs', $projet->id) }}" class="small-box-footer">voir plus <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($current_user->fonction === "superviseur")
            <div class="col-sm-6 col-md-3">
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
                                    <p class="card-category">Animateurs</p>
                                    <h4 class="card-title">{{ $equipedegestion->count() }}</h4>
                                    <a href="{{ route('gestionprojet.lists_personnel', $projet->id) }}" class="small-box-footer">voir plus <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
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
                                    <p class="card-category">Cycle</p>
                                    <h4 class="card-title">{{ $projet->cycle_de_gestion }} mois</h4>
                                    <span>....</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
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
                                    <p class="card-category">Axes</p>
                                    <h4 class="card-title">{{ $axes->count() }}</h4>
                                    <span>....</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="icon-people text-primary" style="font-size: 25pt"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">Avecs</p>
                                    <h4 class="card-title">{{ $avecs->count() }}</h4>
                                    <a href="{{ route('gestionprojet.list_avecs', $projet->id) }}" class="small-box-footer">voir plus <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-sm-6 col-md-4">
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
                                    <p class="card-category">Cycle</p>
                                    <h4 class="card-title">{{ $projet->cycle_de_gestion }} mois</h4>
                                    <span>....</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
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
                                    <p class="card-category">Axes</p>
                                    <h4 class="card-title">{{ $axes->count() }}</h4>
                                    <span>....</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="icon-people text-primary" style="font-size: 25pt"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">Avecs</p>
                                    <h4 class="card-title">{{ $avecs->count() }}</h4>
                                    <a href="{{ route('gestionprojet.list_avecs', $projet->id) }}" class="small-box-footer">voir plus <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <br><div class="row row-card-no-pd mt--2">
        <div class="col-12 col-sm-6 col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-info fw-bold">{{ $total_abandons }}</h3>
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5><b>Abandon(s)</b></h5>
                            <p class="text-muted small">situation d'abandons dans les avecs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-success fw-bold">{{ $total_investissement }}</h3>
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5><b>Total des parts achetées</b></h5>
                            <p class="text-muted small">investissement total dans les avecs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-primary fw-bold">{{ $total_interet }} FC</h3>
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5><b>Total intérêt</b></h5>
                            <p class="text-muted small">intérêt total généré par les avec</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-primary fw-bold">{{ $hommes }} hommes/{{ $hommes + $femmes }} personnes</h6>
                    <h6 class="text-primary fw-bold">{{ $femmes }} femmes/{{ $hommes + $femmes }} personnes</h6>
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5><b>Prise en compte genre</b></h5>
                            <p class="text-muted small"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br><div class="row row-card">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Situation générale d'épargnes et d'intérêts</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="multipleLineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Prise en compte genre</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas
                            id="pieChart"
                            style="width: 50%; height: 50%"
                        ></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="shadow-lg p-4 bg-white">
        <h5 class="text-uppercase text-letter-spacing-xs fs-5 my-0 text-primary font-weight-bold">
            prévision des tâches
        </h5>
        <p class="text-sm text-dark mt-0 mb-5">il y a un temps et un endroit pour toute chose.</p>
        <!-- Days -->
        <div class="row">
            <div class="col-md-4" id="Friday, Nov 13th">
                <h4 style="text-transform: capitalize" class="mt-0 mb-3 text-dark op-8 font-weight-bold">
                    Hier, le {{ Carbon\Carbon::parse($date2)->format('d-m-Y') }}
                </h4>

                <ul class="list-timeline list-timeline-primary">
                    @if(!$taches2->isEmpty())
                        @foreach($taches2 as $tache)
                            <li id="task_{{ $tache->id }}" data-value1="{{ $tache->heure_debut }}" data-value2="{{ $tache->heure_fin }}" class="list-timeline-item p-0 pb-3 pb-lg-4 d-flex flex-wrap flex-column" data-toggle="collapse" data-target="#day-1-item-2">
                                <p class="my-0 text-dark show flex-fw text-sm text-uppercase"><span class="text-primary op-8 infinite animated flash" data-animate="flash" data-animate-infinite="1" data-animate-duration="3.5" style="animation-duration: 3.5s;">{{ $tache->heure_debut }}</span> - {{ $tache->heure_fin }}</p>
                                <div class="d-flex">
                                    @php
                                        $heureFin = $tache->heure_fin;
                                        $heureFinCarbon = \Carbon\Carbon::createFromTimeString($heureFin);

                                        $heureCarbon = \Carbon\Carbon::now();
                                        $tacheDate = \Carbon\Carbon::parse($tache->date)->toDateString();
                                        $dateToday = \Carbon\Carbon::now()->toDateString();
                                    @endphp
                                    @if($dateToday > $tacheDate)
                                        @if($tache->statut)
                                            <i class='bx bx-task text-primary' style="font-size: 17pt"></i>
                                        @else
                                            <i class='bx bx-task-x text-danger' style="font-size: 17pt"></i>
                                        @endif
                                    @elseif($dateToday === $tacheDate)
                                        @if($heureCarbon->greaterThan($heureFinCarbon))
                                            @if($tache->statut)
                                                <i class='bx bx-task text-primary' style="font-size: 17pt"></i>
                                            @else
                                                <i class='bx bx-task-x text-danger' style="font-size: 17pt"></i>
                                            @endif
                                        @else
                                            <i class='bx bx-task text-muted' style="font-size: 17pt"></i>
                                        @endif
                                    @else
                                        <i class='bx bx-task text-muted' style="font-size: 17pt"></i>
                                    @endif
                                    <p class="my-0 collapse flex-fw text-xs text-dark op-8 show" id="day-1-item-2">
                                        {{ $tache->tache }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li class="list-timeline-item p-0 pb-3 pb-lg-4 d-flex flex-wrap flex-column" data-toggle="collapse" data-target="#day-1-item-2">
                            <p class="my-0 text-dark show flex-fw text-sm text-uppercase"><span class="text-primary op-8 infinite animated flash" data-animate="flash" data-animate-infinite="1" data-animate-duration="3.5" style="animation-duration: 3.5s;"></p>
                            <p class="my-0 collapse flex-fw text-xs text-dark op-8 show" id="day-1-item-2"> Aucune tâche prévue<span class="text-primary"></span></p>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="col-md-4" id="Friday, Nov 13th">
                <h4 style="text-transform: capitalize" class="mt-0 mb-3 text-dark op-8 font-weight-bold">
                    Aujourd'hui, le {{ Carbon\Carbon::parse($date)->format('d-m-Y') }}
                </h4>

                <ul class="list-timeline list-timeline-primary">
                    @if(!$taches->isEmpty())
                        @foreach($taches as $tache)
                            <li id="task_{{ $tache->id }}" data-value1="{{ $tache->heure_debut }}" data-value2="{{ $tache->heure_fin }}" class="task list-timeline-item p-0 pb-3 pb-lg-4 d-flex flex-wrap flex-column" data-toggle="collapse" data-target="#day-1-item-2">
                                <p class="my-0 text-dark show flex-fw text-sm text-uppercase"><span class="text-primary op-8 infinite animated flash" data-animate="flash" data-animate-infinite="1" data-animate-duration="3.5" style="animation-duration: 3.5s;">{{ $tache->heure_debut }}</span> - {{ $tache->heure_fin }}</p>
                                <div class="d-flex">
                                    @php
                                        $heureFin = $tache->heure_fin;
                                        $heureFinCarbon = \Carbon\Carbon::createFromTimeString($heureFin);

                                        $heureCarbon = \Carbon\Carbon::now();
                                        $tacheDate = \Carbon\Carbon::parse($tache->date)->toDateString();
                                        $dateToday = \Carbon\Carbon::now()->toDateString();
                                    @endphp
                                    @if($dateToday > $tacheDate)
                                        @if($tache->statut)
                                            <i class='bx bx-task text-primary' style="font-size: 17pt"></i>
                                        @else
                                            <i class='bx bx-task-x text-danger' style="font-size: 17pt"></i>
                                        @endif
                                    @elseif($dateToday === $tacheDate)
                                        @if($heureCarbon->greaterThan($heureFinCarbon))
                                            @if($tache->statut)
                                                <i class='bx bx-task text-primary' style="font-size: 17pt"></i>
                                            @else
                                                <i class='bx bx-task-x text-danger' style="font-size: 17pt"></i>
                                            @endif
                                        @else
                                            <i class='bx bx-task text-muted' style="font-size: 17pt"></i>
                                        @endif
                                    @else
                                        <i class='bx bx-task text-muted' style="font-size: 17pt"></i>
                                    @endif
                                    <p class="my-0 collapse flex-fw text-xs text-dark op-8 show" id="day-1-item-2">
                                        {{ $tache->tache }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li class="list-timeline-item p-0 pb-3 pb-lg-4 d-flex flex-wrap flex-column" data-toggle="collapse" data-target="#day-1-item-2">
                            <p class="my-0 text-dark show flex-fw text-sm text-uppercase"><span class="text-primary op-8 infinite animated flash" data-animate="flash" data-animate-infinite="1" data-animate-duration="3.5" style="animation-duration: 3.5s;"></p>
                            <p class="my-0 collapse flex-fw text-xs text-dark op-8 show" id="day-1-item-2"> Aucune tâche prévue<span class="text-primary"></span></p>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="col-md-4" id="Friday, Nov 13th">
                <h4 style="text-transform: capitalize" class="mt-0 mb-3 text-dark op-8 font-weight-bold">
                    Demain, le {{ Carbon\Carbon::parse($date3)->format('d-m-Y') }}
                </h4>
                <ul class="list-timeline list-timeline-primary">
                    @if(!$taches3->isEmpty())
                        @foreach($taches3 as $tache)
                            <li id="task_{{ $tache->id }}" data-value1="{{ $tache->heure_debut }}" data-value2="{{ $tache->heure_fin }}" class="list-timeline-item p-0 pb-3 pb-lg-4 d-flex flex-wrap flex-column" data-toggle="collapse" data-target="#day-1-item-2">
                                <p class="my-0 text-dark show flex-fw text-sm text-uppercase"><span class="text-primary op-8 infinite animated flash" data-animate="flash" data-animate-infinite="1" data-animate-duration="3.5" style="animation-duration: 3.5s;">{{ $tache->heure_debut }}</span> - {{ $tache->heure_fin }}</p>
                                <div class="d-flex">
                                    @php
                                        $heureFin = $tache->heure_fin;
                                        $heureFinCarbon = \Carbon\Carbon::createFromTimeString($heureFin);

                                        $heureCarbon = \Carbon\Carbon::now();
                                        $tacheDate = \Carbon\Carbon::parse($tache->date)->toDateString();
                                        $dateToday = \Carbon\Carbon::now()->toDateString();
                                    @endphp
                                    @if($dateToday > $tacheDate)
                                        @if($tache->statut)
                                            <i class='bx bx-task text-primary' style="font-size: 17pt"></i>
                                        @else
                                            <i class='bx bx-task-x text-danger' style="font-size: 17pt"></i>
                                        @endif
                                    @elseif($dateToday == $tacheDate)
                                        @if($heureCarbon->greaterThan($heureFinCarbon))
                                            @if($tache->statut)
                                                <i class='bx bx-task text-primary' style="font-size: 17pt"></i>
                                            @else
                                                <i class='bx bx-task-x text-danger' style="font-size: 17pt"></i>
                                            @endif
                                        @else
                                            <i class='bx bx-task text-muted' style="font-size: 17pt"></i>
                                        @endif
                                    @else
                                        <i class='bx bx-task text-muted' style="font-size: 17pt"></i>
                                    @endif
                                    <p class="my-0 collapse flex-fw text-xs text-dark op-8 show" id="day-1-item-2">
                                        {{ $tache->tache }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li class="list-timeline-item p-0 pb-3 pb-lg-4 d-flex flex-wrap flex-column" data-toggle="collapse" data-target="#day-1-item-2">
                            <p class="my-0 text-dark show flex-fw text-sm text-uppercase"><span class="text-primary op-8 infinite animated flash" data-animate="flash" data-animate-infinite="1" data-animate-duration="3.5" style="animation-duration: 3.5s;"></p>
                            <p class="my-0 collapse flex-fw text-xs text-dark op-8 show" id="day-1-item-2"> Aucune tâche prévue<span class="text-primary"></span></p>
                        </li>
                    @endif
                </ul>
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

        const hommes = @json($hommes);
        const femmes = @json($femmes);

        var myPieChart = new Chart(pieChart, {
            type: "pie",
            data: {
                datasets: [
                    {
                        data: [`${hommes}`,`${femmes}`],
                        backgroundColor: ["#1d7af3","#fdaf4b"],
                        borderWidth: 0,
                    },
                ],
                labels: ["Hommes", "Femmes"],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: "bottom",
                    labels: {
                        fontColor: "rgb(154, 154, 154)",
                        fontSize: 11,
                        usePointStyle: true,
                        padding: 20,
                    },
                },
                pieceLabel: {
                    render: "percentage",
                    fontColor: "white",
                    fontSize: 14,
                },
                tooltips: false,
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 20,
                        bottom: 20,
                    },
                },
            },
        });

        const labels = @json($labels);
        const valuesInteret = @json($valuesinterets);
        const values = @json($values);

        var myMultipleLineChart = new Chart(multipleLineChart, {
            type: "line",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "parts achetées",
                        borderColor: "#1d7af3",
                        pointBorderColor: "#FFF",
                        pointBackgroundColor: "#1d7af3",
                        pointBorderWidth: 2,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 1,
                        pointRadius: 4,
                        backgroundColor: "rgba(0,102,255,0.4)",
                        fill: true,
                        borderWidth: 2,
                        data: values,
                    },
                    {
                        label: "intérêts générés",
                        borderColor: "#d78700",
                        pointBorderColor: "#FFF",
                        pointBackgroundColor: "#d78700",
                        pointBorderWidth: 2,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 1,
                        pointRadius: 4,
                        backgroundColor: "rgba(234,141,2,0.4)",
                        fill: true,
                        borderWidth: 2,
                        data: valuesInteret,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: "top",
                },
                tooltips: {
                    bodySpacing: 4,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10,
                },
                layout: {
                    padding: { left: 15, right: 15, top: 15, bottom: 15 },
                },
            },
        });
    </script>
    <script src="{{ asset("js/personnal_scripts/dash_script.js") }}"></script>
@endsection
