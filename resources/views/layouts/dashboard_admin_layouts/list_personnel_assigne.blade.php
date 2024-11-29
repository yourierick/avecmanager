@extends('base')
@section('big_title')
    <span style="color: peru">PROJET REFERENCE: {{ $projet->code_reference }}</span>
@endsection
@section('small_description', 'Personnel assigné au projet')
@section('page_courant', 'liste du personnel')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">liste du personnel</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>n°</th>
                                <th>nom</th>
                                <th>fonction</th>
                                <th>statut du compte</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>n°</th>
                                <th>nom</th>
                                <th>fonction</th>
                                <th>statut du compte</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($personnel as $agent)
                                <tr>
                                    <td>{{ $loop->iteration }}
                                    </td>
                                    <td>{{ $agent->nom }}</td>
                                    <td>{{ $agent->fonction }}
                                    </td>
                                    <td class="cell">
                                        @if($agent->statut === 0)
                                            désactivé
                                        @else
                                            activé
                                        @endif
                                    </td>
                                    <td><a class="btn-sm btn-primary w-100" style="color: whitesmoke" href="{{ route('user.profile_edit', $agent->id) }}">gérer</a>
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
    </script>
@endsection
