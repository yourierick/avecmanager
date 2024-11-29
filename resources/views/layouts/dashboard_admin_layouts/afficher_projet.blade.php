@extends('base')
@section('big_title')
    <span style="color: peru" class="bi-file-word-fill">PROJET REFERENCE: {{ $projet->code_reference }}</span>
@endsection
@section('page_courant', 'vue du projet')
@section('content')
    <form action="{{ route('projet.traitement', $projet->id) }}" method="post" style="margin-bottom: 20px">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-xs-12 col-md-6">
                @if($projet->statut === "en attente")
                    <button name="action" class="btn btn-primary text-light fs-5" value="donner_le_go" type="submit">donner le go</button>
                    <button data-bs-toggle="modal" data-bs-target="#ModalCloture" class="btn btn-warning text-light fs-5" type="button">clôturer le projet</button>
                    <div class="modal fade" id="ModalCloture" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">
                                        <span class="fw-mediumbold"> Demande de</span>
                                        <span class="fw-light"> confirmation</span>
                                    </h5>
                                    <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="modal-body">
                                    <p class="small">
                                        voulez-vous vraiment clôturer ce projet ?
                                    </p>
                                    <div>
                                        <button type="submit" id="addRowButton" name="action" value="terminer" class="btn btn-label-danger">
                                            oui
                                        </button>
                                        <button type="button" class="btn btn-label-primary" data-bs-dismiss="modal">
                                            non
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if($projet->statut === "en cours")
                    <button name="action" class="btn btn-info text-light fs-5" value="mettre_en_attente" type="submit">mettre en attente</button>
                @endif
            </div>
            <div class="col-xs-12 col-md-6">
                @if($projet->statut === "en attente" || $projet->statut === "clôturé")
                    <button data-bs-toggle="modal" data-bs-target="#ModalSup" style="float: right"  class="btn btn-danger text-light fs-5" type="button">supprimer le projet</button>
                    <div class="modal fade" id="ModalSup" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">
                                        <span class="fw-mediumbold"> Demande de</span>
                                        <span class="fw-light"> confirmation</span>
                                    </h5>
                                    <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="modal-body">
                                    <p class="small">
                                        voulez-vous vraiment supprimer ce projet ?
                                    </p>
                                    <div>
                                        <button type="submit" id="addRowButton" name="action" value="supprimer" class="btn btn-label-danger">
                                            oui
                                        </button>
                                        <button type="button" class="btn btn-label-primary" data-bs-dismiss="modal">
                                            non
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>
    <div class="row row-card-no-pd">
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
                                <p class="card-category">Personnel</p>
                                <h4 class="card-title">{{ $equipedegestion->count() }}</h4>
                                <a href="{{ route('projet.list_du_personnel_projet', $projet->id) }}" class="small-box-footer">plus <i class="fa fa-arrow-circle-right"></i></a>
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
                                <a href="{{ route('projet.list_des_avecs', $projet->id) }}" class="small-box-footer">plus <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-card-no-pd mt--2">
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
                    <h3 class="text-success fw-bold">{{ $total_investissement }} Parts achetées</h3>
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
    <div class="mt-3" style="text-align: justify">
        <p style="font-size: 14pt; font-weight: bold" class="text-muted text-primary">Contexte</p>
        <div class="ml-4"><p>{{ $projet->context }}</p></div>
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
        const values = @json($values);
        const valuesInteret = @json($valuesinterets)

        var myMultipleLineChart = new Chart(multipleLineChart, {
            type: "line",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "investissement",
                        borderColor: "#1d7af3",
                        pointBorderColor: "#FFF",
                        pointBackgroundColor: "#1d7af3",
                        pointBorderWidth: 2,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 1,
                        pointRadius: 4,
                        backgroundColor: "transparent",
                        fill: true,
                        borderWidth: 2,
                        data: values,
                    },
                    {
                        label: "intérêts",
                        borderColor: "#59d05d",
                        pointBorderColor: "#FFF",
                        pointBackgroundColor: "#59d05d",
                        pointBorderWidth: 2,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 1,
                        pointRadius: 4,
                        backgroundColor: "transparent",
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
@endsection
