@extends('base')
@section('big_title')
    <span style="color: peru" class="bi-file-word-fill"> DASHBOARD</span>
@endsection
@section('small_description', "espace d'administration")
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
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div
                                class="icon-big text-center icon-primary bubble-shadow-small"
                            >
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">visiteurs</p>
                                <h4 class="card-title">{{ $visiteurs->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div
                                class="icon-big text-center icon-info bubble-shadow-small"
                            >
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">utilisateurs</p>
                                <h4 class="card-title">{{ $utilisateurs->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div
                                class="icon-big text-center icon-success bubble-shadow-small"
                            >
                                <i class="fas fa-luggage-cart"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">projets</p>
                                <h4 class="card-title">{{ $projet_count }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div
                                class="icon-big text-center icon-secondary bubble-shadow-small"
                            >
                                <i class="far fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">adminitrateurs</p>
                                <h4 class="card-title">{{ $administrateurs->count() }}</h4>
                            </div>
                        </div>
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
                    @if(!($taches->isEmpty()))
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
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset("js/personnal_scripts/dash_script.js") }}"></script>
@endsection
