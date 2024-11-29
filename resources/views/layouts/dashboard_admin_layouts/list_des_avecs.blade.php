@extends('base')
@section('big_title')
    <span class="bi-list" style="color: peru"> PROJET REFERENCE: {{ $projet->code_reference }}</span>
@endsection
@section('content')
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
                <div class="modal-body">
                    <p class="small">
                        voulez-vous vraiment supprimer cette avec ? notez que cette action est irréversible
                    </p>
                    <form action="{{ route("projet.supprimer_avec") }}" method="post">
                        @csrf
                        @method("delete")
                        <input type="hidden" value="" name="avec_id" id="avec_id">
                        <button type="submit" id="addRowButton" class="btn btn-label-danger">
                            oui
                        </button>
                        <button type="button" class="btn btn-label-primary" data-bs-dismiss="modal">
                            non
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex">
                    <h4 class="card-title">liste des avecs</h4>
                    @if($current_user->fonction == "superviseur" || $current_user->fonction == "chef de projet")
                        <a href="{{ route('gestionprojet.ajouter_une_avec', $projet->id) }}" class="btn btn-secondary" style="margin-left: 10%"><span class="bi-plus-circle-fill"> ajouter une avec</span></a>
                    @endif
                    <button id="BtnExportToExcel" class="btn text-primary"><img style="width: 25px; height: 25px" src="{{ asset("assets/excel.png") }}" alt=""> Export</button>
                    <button id="BtnExportToPdf" class="btn text-primary"><img style="width: 25px; height: 25px" src="{{ asset("assets/pdf.png") }}" alt=""> Export</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                                <tr class="ligne">
                                    <th class="cell-th">n°</th>
                                    <th class="cell-th">code de l'avec</th>
                                    <th class="cell-th">désignation</th>
                                    <th class="cell-th">axes</th>
                                    <th class="cell-th">membres</th>
                                    <th class="cell-th">hommes</th>
                                    <th class="cell-th">femmes</th>
                                    <th class="cell-th">superviseur</th>
                                    <th class="cell-th">animateur</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>n°</th>
                                <th>code de l'avec</th>
                                <th>désignation</th>
                                <th>axes</th>
                                <th>nombre des membres</th>
                                <th>désagrégation hommes</th>
                                <th>désagrégation femmes</th>
                                <th>superviseur</th>
                                <th>animateur</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($avecs as $avec)
                                <tr class="ligne ln-pdf">
                                    <td class="cell-td">{{ $loop->iteration }}</td>
                                    <td class="cell-td">{{ $avec->code }}</td>
                                    <td class="cell-td">{{ $avec->designation }} mois</td>
                                    <td class="cell-td">{{ $avec->axe->designation }}</td>
                                    <td class="cell-td">{{ $avec->membres->where("statut", "!=", "abandon")->count() }}</td>
                                    <td class="cell-td">{{ $avec->membres->where("statut", "!=", "abandon")->where("sexe", "homme")->count() }}</td>
                                    <td class="cell-td">{{ $avec->membres->where("statut", "!=", "abandon")->where("sexe", "femme")->count() }}</td>
                                    <td class="cell-td">{{ $avec->superviseur->nom }}</td>
                                    <td class="cell-td">{{ $avec->animateur->nom }}</td>
                                    <td class="d-flex">
                                        <a class="btn-sm text-primary" href="{{ route("projet.afficher_une_avec", $avec->id) }}"><span class="bi-eye"></span></a>
                                        <button class="btn-sm text-danger" onclick="loadidavec(this)" value="{{ $avec->id }}" data-bs-toggle="modal" data-bs-target="#ModalSup"><span class="bi-trash-fill"></span></button>
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

        //Exporter en excel
        document.getElementById("BtnExportToExcel").addEventListener('click', async function () {
            //import ExcelJS et FileSaver
            const ExcelJS = window.ExcelJS;
            const saveAs = window.saveAs;

            //créer un classeur et une feuille
            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet("liste des avecs");

            //Ajouter un titre et définir son style
            worksheet.mergeCells("A1:I1");
            const titleCell = worksheet.getCell("A1");
            titleCell.value = "LISTE DES AVECS PROJET {{ strtoupper($projet->code_reference) }}\n";
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
                saveAs(blob, 'liste des avecs.xlsx');
            })
        })

        //Exporter en pdf
        document.getElementById("BtnExportToPdf").addEventListener("click", function () {
            const table = document.getElementById("multi-filter-select");
            const rows = table.querySelectorAll(".ln-pdf");

            const pdfTable = [
                [
                    {text: "n°", bold: true, style: "headerstyle"},
                    {text: "code de l'avec", bold: true, style: "headerstyle"},
                    {text: "désignation", bold: true, style: "headerstyle"},
                    {text: "axes", bold: true, style: "headerstyle"},
                    {text: "membres", bold: true, style: "headerstyle"},
                    {text: "hommes", bold: true, style: "headerstyle"},
                    {text: "femmes", bold: true, style: "headerstyle"},
                    {text: "superviseur", bold: true, style: "headerstyle"},
                    {text: "animateur", bold: true, style: "headerstyle"},
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
                                [{text: "LISTE DES AVECS PROJET {{ strtoupper($projet->code_reference) }}", style: "header"}],
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
                            widths: ["*", "*", "*", "*", "*", "*", "*", "*", "*"],
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
            pdfMake.createPdf(docDefinition).download('liste des avecs.pdf');
        })

        function loadidavec(element) {
            let input_id = document.getElementById("avec_id");
            input_id.value = element.value
        }
    </script>
@endsection
