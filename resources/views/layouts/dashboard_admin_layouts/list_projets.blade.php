@extends('base')
@section('big_title')
    <span style="color: peru" class="bi-file-word-fill"> PROJETS</span>
@endsection
@section('content')
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">liste des projets</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table w-100 table-striped table-hover">
                            <thead>
                            <tr>
                                <th>n°</th>
                                <th>code de référence</th>
                                <th>durée du cycle</th>
                                <th>date de début</th>
                                <th>date de fin</th>
                                <th>statut</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>n°</th>
                                <th>code de référence</th>
                                <th>durée du cycle</th>
                                <th>date de début</th>
                                <th>date de fin</th>
                                <th>statut</th>
                            </tr>
                            </tfoot>
                            <tbody>
                                @foreach($projets as $projet)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $projet->code_reference }}</td>
                                        <td>{{ $projet->cycle_de_gestion }} mois</td>
                                        <td>{{ $projet->date_de_debut->format('d-m-Y') }}</td>
                                        <td>{{ $projet->date_de_fin->format('d-m-Y') }}</td>
                                        <td>
                                            {{ $projet->statut }}
                                        </td>
                                        <td>
                                            <div class="form-button-action" style="gap: 8px">
                                                @if($projet->statut !== "en cours")
                                                    <a href="{{ route('projet.configuration', $projet->id) }}" title="configurer" class="">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('projet.afficher_projet', $projet->id) }}" title="afficher" class="text-success">
                                                    <i class="fa fa-eye"></i>
                                                </a>
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
        $(document).ready(function () {
            $("#multi-filter-select").DataTable({
                pageLength: 5,
                initComplete: function () {
                    this.api()
                        .columns()
                        .every(function () {
                            var column = this;
                            var select = $(
                                '<select class="form-select small"><option value=""></option></select>'
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
    </script>
@endsection
