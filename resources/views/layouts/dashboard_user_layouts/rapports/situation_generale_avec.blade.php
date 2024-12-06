@extends('base_utilisateur')
@section('big_title')
    <div class="row mb-4">
        <div class="col-md-8">
            <span class="text-muted bi-file-word-fill"> PROJET REFERENCE: {{ $projet->code_reference }}</span>
            <br><span style="color: #ee6900; text-transform: uppercase; font-weight: bold">AVEC: {{ $avec->designation }}</span>
        </div>
    </div>
@endsection
@section('small_description', "situation générale de l'avec")
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">situation générale de l'avec</div>
            <p class="text-muted">AVEC: <span style="font-weight: bold">{{ $avec->code }}</span></p>
        </div>
        <div class="card-body">
            <div class="row row-card">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">statut des membres</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="statutChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Désagrégation genre</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas
                                    id="genderChart"
                                    style="width: 50%; height: 50%"
                                ></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-card-no-pd mb-3">
                <div class="col-sm-12 col-md-3">
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
                                        <p class="card-category">En caisse</p>
                                        <h4 class="card-title">
                                            @php
                                                $montant = $caisse_epargne ? $caisse_epargne->montant : 0;
                                            @endphp
                                            {{ $montant }} FC
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3">
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
                                        <p class="card-category">Solidarité</p>
                                        <h4 class="card-title">
                                            @php
                                                $montant = $caisse_solidarite ? $caisse_solidarite->montant : 0;
                                            @endphp
                                            {{ $montant }} FC
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fa fa-coins text-warning" style="font-size: 25pt"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Intérêts</p>
                                        <h4 class="card-title">
                                            {{ $montant_interet }} FC
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="bi-cash-coin text-danger" style="font-size: 25pt"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Amandes</p>
                                        <h4 class="card-title">
                                            @php
                                                $montant = $caisse_amande ? $caisse_amande->montant : 0;
                                            @endphp
                                            {{ $montant }} FC
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card card-primary bg-primary-gradient">
                        <div class="card-body pb-0">
                            <h2 class="mb-2">
                                @php
                                    $montant = $montantTotalEpargne * $avec->valeur_part;
                                @endphp
                                {{ $montant }} FC
                            </h2>
                            <p>Total épargné à ce jour</p>
                            <div class="pull-in sparkline-fix chart-as-background">
                                <div id="lineChart4"><canvas width="321" height="70" style="display: inline-block; width: 321.328px; height: 70px; vertical-align: top;"></canvas></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-black">
                        <div class="card-body pb-0">
                            <h2 class="mb-2">{{ $montantTotalcredit }} FC</h2>
                            <p>Crédit total à rembourser</p>
                            <div class="pull-in sparkline-fix chart-as-background">
                                <div id="lineChart5"><canvas width="321" height="70" style="display: inline-block; width: 321.328px; height: 70px; vertical-align: top;"></canvas></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-success bg-success2">
                        <div class="card-body pb-0">
                            <h2 class="mb-2">{{ $interetTotalcredit }} FC</h2>
                            <p>Intérêt total sur crédit à récevoir</p>
                            <div class="pull-in sparkline-fix chart-as-background">
                                <div id="lineChart6"><canvas width="321" height="70" style="display: inline-block; width: 321.328px; height: 70px; vertical-align: top;"></canvas></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>
    <script>
        const ctx = document.getElementById("statutChart").getContext("2d");
        let total = {{ $totalMembres }};
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["actifs", "inactifs", "abandons"],
                datasets: [
                    {
                        label: "situation d'activité des membres",
                        data: [{{ $actifs }}, {{ $inactifs }}, {{ $abandons }}],
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return `${context.raw} membres sur ${total}`;
                                }
                            }
                        }
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: "top",
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuad'
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
                scales : {
                    y: {
                        beginAtZero: true
                    }
                },

            },
        });

        const ctx2 = document.getElementById("genderChart").getContext("2d");
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ["hommes", "femmes"],
                datasets: [
                    {
                        label: "genre",
                        data: [{{ $hommes }}, {{ $femmes }}],
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return `${context.raw}`;
                                }
                            }
                        }
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: "top",
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuad'
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
                scales : {
                    y: {
                        beginAtZero: true
                    }
                },

            },
        });

        let sparklinepartsowners = @json(array_column($parts_ht_des_membres, 'name'));
        let sparklinepartsvalues = @json(array_column($parts_ht_des_membres, 'value'));
        $("#lineChart4").sparkline(sparklinepartsvalues, {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "rgba(255, 255, 255, .5)",
            fillColor: "rgba(255, 255, 255, .15)",
            tooltipFormatter: function(sp, options, fields) {
                var index = fields.x;
                var name = sparklinepartsowners[index];
                var value = fields.y;

                return name + ": " + value + " parts";
            }
        });

        let sparklinecreditsowners = @json(array_column($credit_des_membres, 'name'));
        let sparklinecreditvalues = @json(array_column($credit_des_membres, 'value'));
        $("#lineChart5").sparkline(sparklinecreditvalues, {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "rgba(255, 255, 255, .5)",
            fillColor: "rgba(255, 255, 255, .15)",
            tooltipFormatter: function(sp, options, fields) {
                var index = fields.x;
                var name = sparklinecreditsowners[index];
                var value = fields.y;

                return name + ": " + value + " FC";
            }
        });

        let sparklineinteretowners = @json(array_column($interets_sur_credit_des_membres, 'name'));
        let sparklineinteretvalues = @json(array_column($interets_sur_credit_des_membres, 'value'));
        $("#lineChart6").sparkline(sparklineinteretvalues, {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "rgba(255, 255, 255, .5)",
            fillColor: "rgba(255, 255, 255, .15)",
            tooltipFormatter: function(sp, options, fields) {
                var index = fields.x;
                var name = sparklineinteretowners[index];
                var value = fields.y;

                return name + ": " + value + " FC";
            }
        });

        @if(session('success'))
        $(document).ready(function() {
            $.notify({
                icon: 'icon-bell',
                title: 'Avecmanager',
                message: '{{ session('success') }}',
            },{
                type: 'secondary',
                placement: {
                    from: "bottom",
                    align: "right"
                },
                time: 1000,
            });
        });
        @elseif(session('error'))
        $(document).ready(function() {
            $.notify({
                icon: 'icon-bell',
                title: 'Avecmanager',
                message: '{{ session('error') }}',
            },{
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
