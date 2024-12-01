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
            <span style="color: peru" class="bi-file-word-fill"> PROJET REFERENCE: {{ $projet->code_reference }}</span>
        </div>
        <div class="col-md-4">
            <div class="btn-group dropdown" style="float: right;">
                <button class="btn dropdown-toggle" type="button" style="background-color: whitesmoke; color: darkblue" data-bs-toggle="dropdown" aria-expanded="false">
                    Options
                </button>
                <ul class="dropdown-menu p-2" role="menu" style="background-color: #ffffff; border: 1px solid blue">
                    <li>
                        <span>Rapports</span>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item btn btn-outline-secondary perso" href="{{ route('guest.releve_des_transactions_caisse_solidarite', [$avec->id, $projet->id]) }}"><span class="bi-file-earmark-excel-fill text-warning"> rélevé caisse solidarité</span></a>
                        <a class="dropdown-item btn btn-outline-secondary perso" href="{{ route('guest.report_transactions_de_avec', [$avec->id, $projet->id]) }}"><span class="bi-file-earmark-excel-fill text-primary"> rapports des transactions</span></a>
                        <a class="dropdown-item btn btn-outline-secondary perso" href="{{ route('guest.situation_generale_de_avec', [$avec->id, $projet->id]) }}"><span class="bi-search"> situation générale de l'avec</span></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('page_courant', "vue de l'avec")
@section('content')
    <div class="row row-card-no-pd mb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <p class="text-primary">INFORMATIONS GENERALES DE L'AVEC</p>
                </div>
            </div>
            <div class="dropdown-divider"></div>
            <div class="row">
                <div class="col-12">
                    <p class="mb-0 mt-0"><span style="font-weight: bold">Nom de l'AVEC: </span><span style="text-transform: uppercase">{{ $avec->designation }}</span></p>
                    <p class="mb-0 mt-0"><span style="font-weight: bold">Axe de location: </span><span style="text-transform: uppercase">{{ $avec->axe->designation }}</span></p>
                    <p class="mb-0 mt-0"><span style="font-weight: bold">Animateur en charge: </span><span style="text-transform: uppercase">{{ $avec->animateur->nom }}</span></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-card-no-pd mt-0">
        <div class="col-sm-12 col-md-4">
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
                                <p class="card-category">Valeur d'une part</p>
                                <h4 class="card-title">{{ $avec->valeur_part }} FC</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
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
                                <p class="card-category">Maximum des parts achetables</p>
                                <h4 class="card-title">{{ $avec->maximum_part_achetable }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
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
                                <p class="card-category">Cotisation solidarité</p>
                                <h4 class="card-title">{{ $avec->valeur_montant_solidarite }} FC</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <h4 class="card-title">Tous les membres de l'avec</h4>
                        <button id="BtnExportToExcel" class="btn text-primary"><img style="width: 25px; height: 25px" src="{{ asset("assets/excel.png") }}" alt=""> Export</button>
                        <button id="BtnExportToPdf" class="btn text-primary"><img style="width: 25px; height: 25px" src="{{ asset("assets/pdf.png") }}" alt=""> Export</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table-sm w-100 table-striped table-hover">
                            <thead>
                            <tr class="ligne">
                                <th class="cell-th" style="background-color: #a2bcfc">nom</th>
                                <th class="cell-th" style="background-color: #a2bcfc">sexe</th>
                                <th class="cell-th" style="background-color: #a2bcfc">adresse</th>
                                <th class="cell-th" style="background-color: #a2bcfc">telephone</th>
                                <th class="cell-th" style="background-color: #a2bcfc">fonction</th>
                                <th class="cell-th" style="background-color: #a2bcfc">statut</th>
                                <th class="cell-th" style="background-color: #a2bcfc">gains</th>
                                <th style="background-color: #a2bcfc">photo</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>nom</th>
                                <th>sexe</th>
                                <th>adresse</th>
                                <th>telephone</th>
                                <th>fonction</th>
                                <th>statut</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($membres as $membre)
                                <tr class="ligne ln-pdf">
                                    <td class="cell-td">{{ $membre->nom }}</td>
                                    <td class="cell-td">{{ $membre->sexe }}</td>
                                    <td class="cell-td">{{ $membre->adresse }}</td>
                                    <td class="cell-td">{{ $membre->numeros_de_telephone }}</td>
                                    <td class="cell-td">{{ $membre->fonction ? $membre->fonction->fonction: "" }}</td>
                                    <td @class(["badge", "cell-td", "ml-2", "text-white", "bg-danger"=>$membre->statut === "abandon", "bg-success"=>$membre->statut === "actif", "bg-warning"=>$membre->statut === "inactif"])>{{ $membre->statut }}</td>
                                    <td class="cell-td">{{ $membre->gains }} FC</td>
                                    <td>
                                        <div class="avatar">
                                            <img src="/storage/{{ $membre->photo }}" alt="..." class="avatar-img rounded-circle">
                                        </div>
                                    </td>
                                    <td class="d-flex">
                                        <a class="btn-sm text-success" href="{{ route('guest.display_membre', $membre->id) }}"><span class="bi-eye"></span></a>
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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Règles de taxation des amandes dans l'avec</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select-regles_amandes" class="display table-sm w-100 table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="background-color: #c5c5c5">Enoncé de la règle</th>
                                <th style="background-color: #c5c5c5">Amande</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Enoncé de la règle</th>
                                <th>Amande</th>
                            </tr>
                            </tfoot>
                            <tbody>
                                @foreach($regles_de_taxation_des_amandes as $regle)
                                    <tr>
                                        <td>{{ $regle->regle }}</td>
                                        <td>{{ $regle->amande }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Règles de taxation des intérêts dans l'avec</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select-regles_interets" class="display table-sm w-100 table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="background-color: #c5c5c5">Enoncé de la règle</th>
                                <th style="background-color: #c5c5c5">montant minimum</th>
                                <th style="background-color: #c5c5c5">montant maximum</th>
                                <th style="background-color: #c5c5c5">taux d'intérêt</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Enoncé de la règle</th>
                                <th>montant minimum</th>
                                <th>montant maximum</th>
                                <th>taux d'intérêt</th>
                            </tr>
                            </tfoot>
                            <tbody>
                                @foreach($regles_de_taxation_des_interets as $regle)
                                    <tr>
                                        <td title="{{ $regle->enonce_regle }}">{{ Str::limit($regle->enonce_regle, 50) }}</td>
                                        <td>{{ $regle->valeur_min }}</td>
                                        <td>{{ $regle->valeur_max }}</td>
                                        <td>{{ $regle->taux_interet }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Cas d'octroi de l'assistance solidarité</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select-cas" class="display table-sm w-100 table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="background-color: #c5c5c5">cas</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>cas</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($cas_octroi_soutien as $cas)
                                <tr>
                                    <td title="{{ $cas->cas }}">{{ Str::limit($cas->cas, 150) }}</td>
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
            $("#multi-filter-select-regles_amandes").DataTable({
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
            $("#multi-filter-select-regles_interets").DataTable({
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
            $("#multi-filter-select-cas").DataTable({
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

        //Exporter en excel
        document.getElementById("BtnExportToExcel").addEventListener('click', async function () {
            //import ExcelJS et FileSaver
            const ExcelJS = window.ExcelJS;
            const saveAs = window.saveAs;

            //créer un classeur et une feuille
            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet("liste des membres");

            //Ajouter un titre et définir son style
            worksheet.mergeCells("A1:G1");
            const titleCell = worksheet.getCell("A1");
            titleCell.value = "LISTE DES MEMBRES DE L'AVEC {{ strtoupper($avec->designation) }}\n";
            titleCell.font = {name: "Times Roman", size: 12, bold: true, color: {argb: 'FFFFFFFF'}};
            titleCell.alignment = {vertical: "top", horizontal: "center", wrapText: true};
            titleCell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: {argb: 'FF000024'},
            };

            //Ajouter les données du tableau HTML
            const table = document.getElementById("multi-filter-select");
            const rows = table.querySelectorAll(".ligne");

            //Ajouter les lignes du tableau dans ExcelJS
            rows.forEach((row, rowIndex) => {
                const cells = row.querySelectorAll(".cell-th, .cell-td");
                cells.forEach((cell, colIndex) => {
                    const excelCell = worksheet.getCell(rowIndex + 3, colIndex + 1); //début à la ligne 3
                    excelCell.value = cell.textContent;

                    //Appliquer un style
                    excelCell.font = {name: "Times Roman", size: 11};
                    excelCell.border = {
                        top: {style: 'thin'},
                        left: {style: 'thin'},
                        bottom: {style: 'thin'},
                        right: {style: 'thin'},
                    }
                    excelCell.alignment = {wrapText: true, vertical: 'middle', horizontal: 'center'};
                    if (rowIndex === 0) {
                        excelCell.font = {bold: true, color: {argb: 'FFFFFFFF'} };
                        excelCell.fill = {
                            type: 'pattern',
                            pattern: 'solid',
                            fgColor: {argb: 'FF404040'},
                        };
                    }
                });
            });
            worksheet.columns.forEach(column => {
                // let maxWidth = 10;
                // column.eachCell({includeEmpty: true}, cell => {
                //     if (cell.value) {
                //         const cellLength = cell.value.toString().length;
                //         maxWidth = Math.max(maxWidth, cellLength + 2);
                //     }
                // })
                column.width = 17;
            });

            workbook.xlsx.writeBuffer().then((data) => {
                const blob = new Blob([data], {type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'});
                saveAs(blob, 'liste des membres.xlsx');
            })
        })

        //Exporter en pdf
        document.getElementById("BtnExportToPdf").addEventListener("click", function () {
            const table = document.getElementById("multi-filter-select");
            const rows = table.querySelectorAll(".ln-pdf");

            const pdfTable = [
                [
                    {text: "nom", bold: true, style: "headerstyle"},
                    {text: "sexe", bold: true, style: "headerstyle"},
                    {text: "adresse", bold: true, style: "headerstyle"},
                    {text: "téléphone", bold: true, style: "headerstyle"},
                    {text: "fonction", bold: true, style: "headerstyle"},
                    {text: "statut", bold: true, style: "headerstyle"},
                    {text: "gains", bold: true, style: "headerstyle"},
                ]
            ];
            rows.forEach(row => {
                const cells = row.querySelectorAll(".cell-td")
                const rowData = Array.from(cells).map(cell => cell.textContent);
                pdfTable.push(rowData);
            })
            const docDefinition = {
                defaultStyle: {
                    fontSize: 9,
                },
                pageOrientation: "landscape",
                content: [
                    {
                        table: {
                            widths: ['*'],
                            body: [
                                [{text: "LISTE DES MEMBRES DE L'AVEC {{ strtoupper($avec->designation) }}", style: "header"}],
                                [""],
                                [""],
                            ]
                        },
                        layout: 'noBorders',
                        fillColor: "#5264c0",
                    },
                    {text: "\n\n\n"},
                    {table: {
                            headerRows: 1,
                            widths: ["*", "*", "*", "*", "*", "*", "*"],
                            body: pdfTable,
                        }
                    }
                ],
                styles: {
                    header: {fontSize: 16, bold: true, color: "white", alignment:"center", margin: [0, 0, 0, 10]},
                    title: {fontSize: 12, bold:true, alignment: "center"},
                    paragraphe1: {fontSize: 9, bold: true},
                    paragraphe2: {fontSize: 9, margin: ["50%", 0, 0, 0]},
                    headerstyle: {
                        color: "black",
                        fillColor: "#89c8ff",
                        alignment: "center",
                    }
                }
            }
            pdfMake.createPdf(docDefinition).download('liste des membres.pdf');
        })
    </script>
@endsection
