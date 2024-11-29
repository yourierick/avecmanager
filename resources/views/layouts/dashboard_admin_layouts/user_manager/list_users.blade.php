@extends('base')
@section('big_title')
    <span class="bi-list-check" style="color: peru"> UTILISATEURS</span>
@endsection
@section('small_description', "panel de gestion des utilisateurs")
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">liste d'utilisateurs</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>nom</th>
                                    <th>sexe</th>
                                    <th>adresse</th>
                                    <th>email</th>
                                    <th>type de compte</th>
                                    <th>projet</th>
                                    <th>fonction</th>
                                    <th>statut</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>nom</th>
                                <th>sexe</th>
                                <th>adresse</th>
                                <th>email</th>
                                <th>type de compte</th>
                                <th>projet</th>
                                <th>fonction</th>
                                <th>statut</th>
                            </tr>
                            </tfoot>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td class="truncate">{{ $user -> nom }}</td>
                                        <td>{{ $user -> sexe }}</td>
                                        <td class="truncate">{{ $user -> adresse }}</td>
                                        <td>{{ $user -> email }}</td>
                                        <td>{{ $user -> droits }}</td>
                                        <td>{{ $user->projet ? $user->projet->code_reference : "-" }}</td>
                                        <td>{{ $user->fonction ?:"-" }}</td>
                                        <td>@if($user->statut === 1)activé @else désactivé @endif</td>
                                        <td>
                                            <a class="btn-sm btn-primary text-light"
                                               href="{{ route('user.profile_edit', $user->id) }}">voir</a>
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

