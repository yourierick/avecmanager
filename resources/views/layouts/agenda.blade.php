@extends("base_utilisateur")
@section('token')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('big_title')
    <div class="modal fade" id="SupRowModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                        Voulez-vous vraiment supprimer cette tâche?
                    </p>
                    <form action="{{ route('agenda.delete_task') }}" method="post">
                        @csrf
                        @method('delete')

                        <input type="hidden" name="tache_id" value="" id="tache_id">
                        <button type="submit" id="addRowButton" class="btn btn-primary">
                            oui
                        </button>
                        <button type="button" class="btn btn-label-danger" data-bs-dismiss="modal">
                            non
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-8">
            <span class="bi-file-word-fill" style="color: peru">PROJET REFERENCE: {{ $projet ? $projet->code_reference:"AUCUN" }}</span>
        </div>
        <div class="col-md-4">
            <div class="btn-group dropdown" style="float: right;">
                <button class="btn dropdown-toggle" type="button" style="background-color: whitesmoke; color: darkblue" data-bs-toggle="dropdown" aria-expanded="false">
                    Options
                </button>
                <ul class="dropdown-menu p-2" role="menu" style="background-color: #ffffff; border: 1px solid blue">
                    <li>
                        <a class="dropdown-item btn btn-outline-secondary perso" data-bs-toggle="modal" data-bs-target="#addRowModal" href="#"><span class="bi-plus-circle-fill text-success"></span> ajouter une tâche</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <form action="{{ route('agenda.add_task') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group form-group-default">
                                        <label for="id_tache">tâche</label>
                                        <textarea name="tache" id="id_tache" class="form-control" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group form-group-default">
                                        <label for="id_date">date</label>
                                        <input name="date" type="date" id="id_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group form-group-default">
                                        <label for="id_time">heure début</label>
                                        <input name="heure_debut" type="time" id="id_time" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group form-group-default">
                                        <label for="id_time2">heure fin</label>
                                        <input name="heure_fin" type="time" id="id_time2" class="form-control">
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
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex">
                    <h4 class="card-title">mes tâches</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display w-100 table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>n°</th>
                                <th>tâches</th>
                                <th>date</th>
                                <th>heure début</th>
                                <th>heure fin</th>
                                <th>statut</th>
                                <th>observation</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>n°</th>
                                <th>tâches</th>
                                <th>date</th>
                                <th>heure début</th>
                                <th>heure fin</th>
                                <th>statut</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($agenda as $tache)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td title="{{ $tache->tache }}">{{ Str::limit($tache->tache, 40) }}</td>
                                    <td>{{ $tache->date->format('d-m-Y') }}</td>
                                    <td>{{ $tache->heure_debut }}</td>
                                    <td>{{ $tache->heure_fin }}</td>
                                    <td id="statut_{{ $tache->id }}">{{ $tache->statut ? "réalisée": "non réalisée" }}</td>
                                    <td>
                                        <input id="{{ $tache->id }}" onchange="changestatustask(this)" type="checkbox" class="form-control" @if($tache->statut) checked @endif>
                                    </td>
                                    <td>
                                        <button class="btn text-danger" value="{{ $tache->id }}" onclick="loadidtache(this)" data-bs-toggle="modal" data-bs-target="#SupRowModal" href="#"><span class="bi-trash-fill"></span></button>
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

        function loadidtache(element) {
            let input_id = document.getElementById("tache_id");
            input_id.value = element.value
        }
    </script>
    <script src="{{ asset("js/personnal_scripts/agenda_script.js") }}"></script>
@endsection
