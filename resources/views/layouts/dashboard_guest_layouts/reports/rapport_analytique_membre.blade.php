@extends('base_guest')
@section('big_title')
    <div class="row mb-4">
        <div class="col-md-8">
            <span class="text-muted">PROJET REFERENCE: {{ $projet->code_reference }}</span>
            <br><span style="color: #ee6900; text-transform: uppercase; font-weight: bold">AVEC: {{ $avec->designation }}</span>
        </div>
    </div>
@endsection
@section('small_description', 'rapport analytique des transactions du membre')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">analyse des transactions</div>
            <p class="text-muted">@if ($membre->sexe === "homme") Monsieur @else Madame @endif {{ $membre->nom }}</p>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="monthlySavingsChart"></canvas>
            </div>
            <div>
                <p>
                    l'analyse de la tendance se basant sur les données ci-haut des épargnes de @if ($membre->sexe === "homme") monsieur @else madame @endif {{ $membre->nom }};
                    indique @if ($trendPercentage > 0) une hausse @elseif($trendPercentage < 0) une baisse @else que la tendance sur les épargnes reste stable @endif entre le début et la fin de la période observée, soit un taux de variation de {{number_format($trendPercentage, 1) }} % actuellement.
                    Notons que, @if ($membre->sexe === "homme") monsieur @else madame @endif {{ $membre->nom }} a acheté à ce jour un total de <span style="background-color: yellow; padding: 3px; border-radius: 2px">{{ $totalAmount }}</span> parts, ce qui nous fait une moyenne de {{ $averageAmount }} parts
                    par mois, avec un maximum des parts achetées de {{ $maxAmount }} parts. On constate par ailleurs que @if ($membre->sexe === "homme") monsieur @else madame @endif a généré à ce jour un intérêt
                    d'environ <span style='background-color: #70d911; padding: 3px; border-radius: 2px'>{{ $totalAmountInterets }}</span> FC.
                </p>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>
    <script>
        let labels = @json($labels);
        let values = @json($values);
        let valuesinterets = @json($valuesinterets);

        //tendance des épargnes
        let trend = @json($trend);
        let trendProjection = @json($trendProjection);
        let trendPercentage = @json($trendPercentage); //variation de la tendance

        if (labels.length > 0 && values.length > 0 && labels.length === values.length) {
            const ctx = document.getElementById("monthlySavingsChart").getContext("2d");
            new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "PA",
                            borderColor: "#1d7af3",
                            pointBorderColor: "#FFF",
                            pointBackgroundColor: "#1d7af3",
                            pointBorderWidth: 2,
                            pointHoverRadius: 4,
                            pointHoverBorderWidth: 1,
                            pointRadius: 4,
                            backgroundColor: "rgba(0,0,255,0.2)",
                            fill: true,
                            borderWidth: 1,
                            data: values,
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return `parts achetées: ${context.raw} parts`;
                                    }
                                }
                            }
                        },
                        {
                            label: "TDP",
                            borderColor: 'rgba(130, 110, 120, 1)',
                            pointBackgroundColor: "yellow",
                            borderDash: [5, 5],
                            borderWidth: 0.5,
                            data: trendProjection,
                            backgroundColor: 'rgba(0, 0, 0, 0.3)',
                            fill: true,
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return `variation de la tendance: ${context.raw}`;
                                    }
                                }
                            }
                        },
                        {
                            label: "IG",
                            borderColor: "#2CC417FF",
                            pointBorderColor: "#FFF",
                            pointBackgroundColor: "#12E21DFF",
                            pointBorderWidth: 2,
                            pointHoverRadius: 4,
                            pointHoverBorderWidth: 1,
                            pointRadius: 4,
                            backgroundColor: "rgba(0,255,0,0.2)",
                            fill: true,
                            borderWidth: 1,
                            data: valuesinterets,
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return `intérêt généré: ${context.raw} FC`;
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
                    plugins: {
                        annotation: {
                            annotations: {
                                trendPercentageLabel: {
                                    type: 'label',
                                    content: `taux de variation: ${trendPercentage.toFixed(2)}%`,
                                    position: 'end',
                                    yAdjust: -10,
                                    xAdjust: 50,
                                    backgroundColor: 'rgba(255, 255, 255, 0.8)',
                                    borderColor: 'red',
                                    borderWidth: 1,
                                    padding: 5,
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                }
                            }
                        }
                    }
                },
            });
        }
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
