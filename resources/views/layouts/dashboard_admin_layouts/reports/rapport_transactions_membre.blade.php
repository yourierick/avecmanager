@extends('base')
@section('big_title')
    <div class="row mb-4">
        <div class="col-md-8">
            <span class="bi-file-word-fill" style="color: peru"> PROJET REFERENCE: {{ $projet->code_reference }}</span>
            <br><span>AVEC: {{ $avec->designation }}</span>
        </div>
        <div class="col-md-4">
            <div class="btn-group dropdown" style="float: right;">
                <button class="btn dropdown-toggle" type="button" style="background-color: whitesmoke; color: darkblue" data-bs-toggle="dropdown" aria-expanded="false">
                    Options
                </button>
                <ul class="dropdown-menu p-2" role="menu" style="background-color: #ffffff; border: 1px solid blue">
                    <li>
                        <a class="dropdown-item btn btn-outline-secondary perso" href="{{ route('report.rapport_analytique_du_membre', [$membre->id, $projet->id, $avec->id]) }}"><span class="bi-file-earmark-excel"> rapport analytique du membre</span></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('small_description', 'les avecs')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <button id="BtnExportToExcel" class="btn text-primary"><img style="width: 25px; height: 25px" src="{{ asset("assets/excel.png") }}" alt=""> Export</button>
                <button id="BtnExportToPdf" class="btn text-primary"><img style="width: 25px; height: 25px" src="{{ asset("assets/pdf.png") }}" alt=""> Export</button>
            </div>
            <div class="card">
                <div class="card-header d-flex">
                    <h4 class="card-title">transactions</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                            <tr class="ligne">
                                <th class="cell-th">n°</th>
                                <th class="cell-th" style="background-color: #94DBFCFF">mois</th>
                                <th class="cell-th" style="background-color: #94DBFCFF">semaine</th>
                                <th class="cell-th" style="background-color: #94DBFCFF">semaine début</th>
                                <th class="cell-th" style="background-color: #94DBFCFF">semaine fin</th>
                                <th class="cell-th" style="background-color: #94DBFCFF">date de la réunion</th>
                                <th class="cell-th" style="background-color: #d9dce5">fréquentation</th>
                                <th class="cell-th" style="background-color: #F7CD96FF">parts achetées</th>
                                <th class="cell-th" style="background-color: #F7CD96FF">cotisations solidarité</th>
                                <th class="cell-th" style="background-color: #F7CD96FF">amandes</th>
                                <th class="cell-th" style="background-color: #F7CD96FF">crédit</th>
                                <th class="cell-th" style="background-color: #F7CD96FF">taux d'intérêt</th>
                                <th class="cell-th" style="background-color: #F7CD96FF">date de remboursement</th>
                                <th class="cell-th" style="background-color: #F7CD96FF">remboursement</th>
                                @if($current_user->fonction === "superviseur" || $current_user->fonction === "chef de projet")
                                    <th></th>
                                @endif
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>n°</th>
                                <th>mois</th>
                                <th>semaine</th>
                                <th>semaine début</th>
                                <th>semaine fin</th>
                                <th>date de la réunion</th>
                            </tr>
                            </tfoot>
                            <tbody>
                                @foreach($transactions as $transaction)
                                    <tr class="ligne ln-pdf">
                                        <td class="cell-td">{{ $loop->iteration }}</td>
                                        <td class="cell-td ln-pdf-td">{{ $transaction->cycle_de_gestion->designation }}</td>
                                        <td class="cell-td ln-pdf-td">{{ $transaction->semaine }}</td>
                                        <td class="cell-td ln-pdf-td">{{ $transaction->semaine_debut->format("d/m/Y") }}</td>
                                        <td class="cell-td ln-pdf-td">{{ $transaction->semaine_fin->format("d/m/Y") }}</td>
                                        <td class="cell-td ln-pdf-td">{{ $transaction->date_de_la_reunion->format("d/m/Y") }}</td>
                                        <td class="cell-td ln-pdf-td">{{ $transaction->frequentation }}</td>
                                        <td class="cell-td ln-pdf-td">{{ $transaction->parts_achetees }} Parts</td>
                                        <td class="cell-td ln-pdf-td">{{ $transaction->cotisation }} FC</td>
                                        <td class="cell-td ln-pdf-td">{{ $transaction->amande }} FC</td>
                                        <td class="cell-td ln-pdf-td">{{ $transaction->credit }} FC</td>
                                        <td class="cell-td ln-pdf-td">{{ $transaction->taux_interet }} %</td>
                                        <td class="cell-td ln-pdf-td">{{ $transaction->date_de_remboursement ? $transaction->date_de_remboursement->format('d/m/Y') : "" }}</td>
                                        <td class="cell-td ln-pdf-td">{{ $transaction->credit_rembourse }} FC</td>
                                        @if($current_user->fonction === "superviseur" || $current_user->fonction === "chef de projet")
                                            <td>
                                                @if(\Carbon\Carbon::parse($transaction->date_de_la_reunion)->diffInDays() <= 7)
                                                    <a class="text-danger" data-bs-toggle="modal" data-bs-target="#ModalSup{{ $transaction->id }}" href="#"><span class="bi-trash-fill"></span></a>
                                                    <div class="modal fade" id="ModalSup{{ $transaction->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header border-0">
                                                                    <h5 class="modal-title">
                                                                        <span class="fw-mediumbold"> demande de</span>
                                                                        <span class="fw-light"> confirmation</span>
                                                                    </h5>
                                                                    <button type="button" class="close" data-bs-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p class="small">
                                                                        voulez-vous vraiment supprimer cette transaction ?
                                                                    </p>
                                                                    <form action="{{ route("gestionprojet.supprimer_transaction", $transaction->id) }}" method="post">
                                                                        @csrf
                                                                        @method("delete")
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
                                                @endif
                                            </td>
                                        @endif
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
            const worksheet = workbook.addWorksheet("Transactions");

            //Ajouter un titre et définir son style
            worksheet.mergeCells("A1:N1");
            const titleCell = worksheet.getCell("A1");
            titleCell.value = "RELEVE DES TRANSACTIONS DE {{ $membre->nom }} DE L'AVEC {{ strtoupper($avec->designation) }}\n";
            titleCell.font = {name: "Times Roman", size: 12, bold: true, color: {argb: 'FFFFFFFF'}};
            titleCell.alignment = {vertical: "top", horizontal: "center", wrapText: true};
            titleCell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: {argb: 'FF000024'},
            };

            worksheet.mergeCells("A2:N2");
            const titleContextCell = worksheet.getCell("A2");
            titleContextCell.value = "Identification du membre";
            titleContextCell.font = {name: "Times Roman", size: 11, bold: true, color: "FF009CFF"};
            titleContextCell.alignment = {vertical: "top", horizontal: "center"};
            titleContextCell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: {argb: 'FFDDDDDD'},
            };

            //Ajouter une image
            try {
                const imgPath = "/storage/{{ $membre->photo }}";
                const extension = imgPath.split('.').pop().toLowerCase();

                console.log(imgPath);
                console.log(extension);
                const img = await fetch(imgPath);
                const imgBlob = await img.blob();
                const imageId = workbook.addImage({buffer: await imgBlob.arrayBuffer(), extension: extension, });
                worksheet.addImage(imageId, {
                    tl: {col: 0, row: 3}, //top-left corner
                    ext: {width: 150, height: 150}, //Taille
                });
            }catch (error) {

            }

            worksheet.mergeCells("D4:F4");
            const contextCell = worksheet.getCell("D4");
            contextCell.value = "Nom: ";
            contextCell.font = {name: "Times Roman", size: 11};
            contextCell.alignment = {vertical: "middle", horizontal: "left", wrapText: true};
            worksheet.mergeCells("D5:F5");
            const contextCell2 = worksheet.getCell("D5");
            contextCell2.value = "Sexe: ";
            contextCell2.font = {name: "Times Roman", size: 11};
            contextCell2.alignment = {vertical: "middle", horizontal: "left", wrapText: true};
            worksheet.mergeCells("D6:F6");
            const contextCell3 = worksheet.getCell("D6");
            contextCell3.value = "Téléphone: ";
            contextCell3.font = {name: "Times Roman", size: 11};
            contextCell3.alignment = {vertical: "middle", horizontal: "left", wrapText: true};
            worksheet.mergeCells("D7:F7");
            const contextCell4 = worksheet.getCell("D7");
            contextCell4.value = "Adresse: ";
            contextCell4.font = {name: "Times Roman", size: 11};
            contextCell4.alignment = {vertical: "middle", horizontal: "left", wrapText: true};

            worksheet.mergeCells("G4:N4");
            const contextCell5 = worksheet.getCell("G4");
            contextCell5.value = "{{ $membre->nom }}";
            contextCell5.font = {name: "Times Roman", size: 11};
            contextCell5.alignment = {vertical: "middle", horizontal: "left", wrapText: true};
            worksheet.mergeCells("G5:N5");
            const contextCell6 = worksheet.getCell("G5");
            contextCell6.value = "{{ $membre->sexe }}";
            contextCell6.font = {name: "Times Roman", size: 11};
            contextCell6.alignment = {vertical: "middle", horizontal: "left", wrapText: true};
            worksheet.mergeCells("G6:N6");
            const contextCell7 = worksheet.getCell("G6");
            contextCell7.value = "{{ $membre->numeros_de_telephone }}";
            contextCell7.font = {name: "Times Roman", size: 11};
            contextCell7.alignment = {vertical: "middle", horizontal: "left", wrapText: true};
            worksheet.mergeCells("G7:N7");
            const contextCell8 = worksheet.getCell("G7");
            contextCell8.value = "{{ $membre->adresse }}";
            contextCell8.font = {name: "Times Roman", size: 11};
            contextCell8.alignment = {vertical: "middle", horizontal: "left", wrapText: true};

            worksheet.mergeCells("D9:F9");
            const contextCell9 = worksheet.getCell("D9");
            contextCell9.value = "Parts totales achetées: ";
            contextCell9.font = {name: "Times Roman", size: 11};
            contextCell9.alignment = {vertical: "middle", horizontal: "left", wrapText: true};
            worksheet.mergeCells("D10:F10");
            const contextCell10 = worksheet.getCell("D10");
            contextCell10.value = "Crédit: ";
            contextCell10.font = {name: "Times Roman", size: 11};
            contextCell10.alignment = {vertical: "middle", horizontal: "left", wrapText: true};
            worksheet.mergeCells("D11:F11");
            const contextCell11 = worksheet.getCell("D11");
            contextCell11.value = "Gains: ";
            contextCell11.font = {name: "Times Roman", size: 11};
            contextCell11.alignment = {vertical: "middle", horizontal: "left", wrapText: true};

            worksheet.mergeCells("G9:N9");
            const contextCell12 = worksheet.getCell("G9");
            contextCell12.value = "{{ $parts }}";
            contextCell12.font = {name: "Times Roman", size: 11};
            contextCell12.alignment = {vertical: "middle", horizontal: "left", wrapText: true};
            worksheet.mergeCells("G10:N10");
            const contextCell13 = worksheet.getCell("G10");
            contextCell13.value = "{{ $credit }} FC";
            contextCell13.font = {name: "Times Roman", size: 11};
            contextCell13.alignment = {vertical: "middle", horizontal: "left", wrapText: true};
            worksheet.mergeCells("G11:N11");
            const contextCell14 = worksheet.getCell("G11");
            contextCell14.value = "{{ $membre->gains }} FC";
            contextCell14.font = {name: "Times Roman", size: 11};
            contextCell14.alignment = {vertical: "middle", horizontal: "left", wrapText: true};


            //Ajouter les données du tableau HTML
            const table = document.getElementById("multi-filter-select");
            const rows = table.querySelectorAll(".ligne");

            //Ajouter les lignes du tableau dans ExcelJS
            rows.forEach((row, rowIndex) => {
                const cells = row.querySelectorAll(".cell-th, .cell-td");
                cells.forEach((cell, colIndex) => {
                    const excelCell = worksheet.getCell(rowIndex + 13, colIndex + 1); //début à la ligne 13
                    excelCell.value = cell.textContent;

                    //Appliquer un style
                    excelCell.font = {name: "Times Roman", size: 11};
                    excelCell.border = {
                        top: {style: 'thin'},
                        left: {style: 'thin'},
                        bottom: {style: 'thin'},
                        right: {style: 'thin'},
                    }
                    excelCell.alignment = {wrapText: true, vertical: 'top', horizontal: 'center'};
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
                saveAs(blob, 'rapport des transactions de soutien.xlsx');
            })
        })

        //Exporter en pdf
        //génération du document pdf
        function extractTableData() {
            const table = document.getElementById("multi-filter-select");
            const rows = table.querySelectorAll('.ln-pdf');
            const data = [];

            rows.forEach(row => {
                const cells = row.querySelectorAll('.ln-pdf-td');
                data.push({
                    mois: cells[0].textContent,
                    semaine: cells[1].textContent,
                    debut: cells[2].textContent,
                    fin: cells[3].textContent,
                    date: cells[4].textContent,
                    frequent: cells[5].textContent,
                    parts: cells[6].textContent,
                    cotisations: cells[7].textContent,
                    amandes: cells[8].textContent,
                    credit: cells[9].textContent,
                    taux: cells[10].textContent,
                    dateremboursement: cells[11].textContent,
                    remboursement: cells[12].textContent,
                });
            });

            return data;
        }

        function prepareTableData(data) {
            const tableBody = [
                [
                    {text: "mois", bold: true, style: "headerstyle"},
                    {text: "semaine", bold: true, style: "headerstyle"},
                    {text: "semaine début", bold: true, style: "headerstyle"},
                    {text: "semaine fin", bold: true, style: "headerstyle"},
                    {text: "date de la réunion", bold: true, style: "headerstyle"},
                    {text: "fréquentation", bold: true, style: "headerstyle"},
                    {text: "parts achetées", bold: true, style: "headerstyle"},
                    {text: "cotisations solidarité", bold: true, style: "headerstyle"},
                    {text: "amandes", bold: true, style: "headerstyle"},
                    {text: "crédit", bold: true, style: "headerstyle"},
                    {text: "taux d'intérêt", bold: true, style: "headerstyle"},
                    {text: "date de remboursement", bold: true, style: "headerstyle"},
                    {text: "remboursement", bold: true, style: "headerstyle"},
                ]
            ]

            let currentMois = null;
            let rowSpan = 0;

            data.forEach((row, index) => {
                if (row.mois !== currentMois) {
                    currentMois = row.mois;
                    rowSpan = data.filter(d => d.mois === currentMois).length;
                    tableBody.push([{text: row.mois, rowSpan}, row.semaine, row.debut, row.fin, row.date,
                        row.frequent, row.parts, row.cotisations, row.amandes, row.credit, row.taux, row.dateremboursement,
                        row.remboursement])
                }else {
                    tableBody.push([{}, row.semaine, row.debut, row.fin, row.date, row.frequent, row.parts, row.cotisations,
                        row.amandes, row.credit, row.taux, row.dateremboursement, row.remboursement])
                }
            });

            return tableBody;
        }

        document.getElementById("BtnExportToPdf").addEventListener("click", function () {
            const rawData = extractTableData();
            const tableData = prepareTableData(rawData);

            const docDefinition = {
                pageOrientation: "landscape",
                defaultStyle: {
                    fontSize: 8,
                },
                content: [
                    {
                        table: {
                            widths: ['*'],
                            body: [
                                [{text: "RAPPORT DES TRANSACTIONS DE {{ strtoupper($membre->nom) }} DE L'AVEC {{ strtoupper($avec->designation) }}", style: "header"}],
                                [""],
                                [""],
                            ]
                        },
                        layout: 'noBorders',
                        fillColor: "#5264c0",
                    },
                    {
                        table: {
                            widths: ['*'],
                            body: [
                                    [{text: "Identification et Situation du Membre", style: "title"}],
                                [""],
                                [""],
                            ]
                        },
                        layout: 'noBorders',
                        fillColor: "#e1e1e1",
                    },
                    {
                        table: {
                            widths: [100, 653],
                            body: [
                                [{text: "1. Nom: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `{{ $membre->nom }}`],
                                [{text: "2. Sexe: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `{{ $membre->sexe }}`],
                                [{text: "3. Téléphone: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `{{ $membre->numeros_de_telephone }}`],
                                [{text: "4. Adresse: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `{{ $membre->adresse }}`],
                                ["", ""],
                                [{text: "5. Parts: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `{{ $parts }}`],
                                [{text: "6. Crédit: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `{{ $credit }} FC`],
                                [{text: "7. Gains: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `{{ $membre->gains }} FC`],
                                ["", ""],
                                ["", ""],
                            ]
                        },
                        layout: 'noBorders',
                        fillColor: "#e1e1e1",
                    },
                    {text: "\n\n\n"},
                    {table: {widths: [50, 40, 50, 50, 50, 50, 50, 50, 50, 40, 50, 50, 63, 55], body: tableData,}}
                ],
                styles: {
                    header: {fontSize: 14, bold: true, color: "white", alignment:"center", margin: [0, 0, 0, 0]},
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
            pdfMake.createPdf(docDefinition).download('rapport des transactions de {{ $membre->nom }}.pdf');
        })
    </script>
@endsection
