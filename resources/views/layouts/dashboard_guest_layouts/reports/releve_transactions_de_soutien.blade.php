@extends('base_guest')
@section('big_title')
    <div class="row mb-4">
        <div class="col-md-8">
            <span class="text-muted">PROJET REFERENCE: {{ $projet->code_reference }}</span>
            <br><span style="color: #ee6900; text-transform: uppercase; font-weight: bold">AVEC: {{ $avec->designation }}</span>
        </div>
    </div>
@endsection
@section('small_description', "rélevé des transactions d'assistance solidarité")
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
                                <th>n°</th>
                                <th style="background-color: #94DBFCFF">bénéficiaire</th>
                                <th style="background-color: #94DBFCFF">date</th>
                                <th style="background-color: #94DBFCFF">situation</th>
                                <th style="background-color: #94DBFCFF">montant</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>n°</th>
                                <th>bénéficiaire</th>
                                <th>date</th>
                                <th>situation</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($transactions as $transaction)
                                <tr class="ligne ln-pdf">
                                    <td class="cell-pdf">{{ $loop->iteration }}</td>
                                    <td class="cell-pdf">{{ $transaction->membre->nom ?? "membre non trouvé" }}</td>
                                    <td class="cell-pdf">{{ $transaction->created_at->format("d/m/Y") }}</td>
                                    <td class="cell-pdf">{{ $transaction->cas }}</td>
                                    <td class="cell-pdf">{{ $transaction->montant }} FC</td>
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
            worksheet.mergeCells("A1:E1");
            const titleCell = worksheet.getCell("A1");
            titleCell.value = "RELEVE DES TRANSACTIONS D'ASSISTANCE DE L'AVEC {{ strtoupper($avec->designation) }}\n";
            titleCell.font = {name: "Times Roman", size: 12, bold: true, color: {argb: 'FFFFFFFF'}};
            titleCell.alignment = {vertical: "top", horizontal: "center", wrapText: true};
            titleCell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: {argb: 'FF000024'},
            };

            worksheet.mergeCells("A2:E2");
            const titleContextCell = worksheet.getCell("A2");
            titleContextCell.value = "Situation générale de l'AVEC";
            titleContextCell.font = {name: "Times Roman", size: 11, bold: true, color: "FF009CFF"};
            titleContextCell.alignment = {vertical: "top", horizontal: "center"};
            titleContextCell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: {argb: 'FFDDDDDD'},
            };

            worksheet.mergeCells("A3:B3");
            const contextCell = worksheet.getCell("A3");
            contextCell.value = "Parts totales achetées: {{ $partsTotAchetees }}\n"+
                "En caisse: {{ $montantencaisse }} FC\nIntérêt total généré: {{ $montantinteret }} FC\nCaisse sociale: {{ $montantsolidarite }} FC\n"+
                "Amandes: {{ $montantamande }} FC";
            contextCell.font = {name: "Times Roman", size: 11};
            contextCell.alignment = {vertical: "middle", horizontal: "left", wrapText: true};

            worksheet.mergeCells("C3:E3");
            const contextCell2 = worksheet.getCell("C3");
            contextCell2.value = "Membres: {{ $totalMembres }}\nHommes: {{ $hommes }}\nFemmes: {{ $femmes }}\n"+
                "Actifs: {{ $actifs }}\nInactifs: {{ $inactifs }}\nAbandon: {{ $abandons }}";
            contextCell2.font = {name: "Times Roman", size: 11};
            contextCell2.alignment = {vertical: "middle", horizontal: "left", wrapText: true};

            worksheet.getRow(3).height = 100;


            //Ajouter une image
            //const img = await fetch('chemin de l image')
            //const imgBlob = await img.blob();
            //const imageId = workbook.addImage({buffer: await imgBlob.arrayBuffer(), extension: 'png', });
            //worksheet.addImage(ImageId, {
            //tl: {col: 0, row: 1}, //top-left corner
            //ext: {width: 150, height: 50}, //Taille
            //});

            //Ajouter les données du tableau HTML
            const table = document.getElementById("multi-filter-select");
            const rows = table.querySelectorAll(".ligne");

            //Ajouter les lignes du tableau dans ExcelJS
            rows.forEach((row, rowIndex) => {
                const cells = row.querySelectorAll("th, td");
                cells.forEach((cell, colIndex) => {
                    if (colIndex !== 5) {
                        const excelCell = worksheet.getCell(rowIndex + 4, colIndex + 1); //début à la ligne 4
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
        document.getElementById("BtnExportToPdf").addEventListener("click", function () {
            var data = {
                partsTotAchetees: @json($partsTotAchetees),
                montantencaisse: @json($montantencaisse),
                montantinteret: @json($montantinteret),
                montantsolidarite: @json($montantsolidarite),
                montantamande: @json($montantamande),
                totalMembres: @json($totalMembres),
                hommes: @json($hommes),
                femmes: @json($femmes),
                actifs: @json($actifs),
                inactifs: @json($inactifs),
                abandons: @json($abandons),
            }
            const table = document.getElementById("multi-filter-select");
            const rows = table.querySelectorAll(".ln-pdf");

            const pdfTable = [
                [
                    {text: "n°", bold: true, style: "headerstyle"},
                    {text: "bénéficiaire", bold: true, style: "headerstyle"},
                    {text: "date", bold: true, style: "headerstyle"},
                    {text: "situation", bold: true, style: "headerstyle"},
                    {text: "montant", bold: true, style: "headerstyle"},
                ]
            ];
            rows.forEach(row => {
                const cells = row.querySelectorAll(".cell-pdf")
                const rowData = Array.from(cells).map(cell => cell.textContent);
                pdfTable.push(rowData);
            })
            const docDefinition = {
                defaultStyle: {
                    fontSize: 9,
                },
                content: [
                    {
                        table: {
                            widths: ['*'],
                            body: [
                                [{text: "RAPPORT DES TRANSACTIONS DE SOUTIEN DE L'AVEC {{ strtoupper($avec->designation) }}", style: "header"}],
                                [""],
                                [""],
                            ]
                        },
                        layout: 'noBorders',
                        fillColor: "#5264c0",
                    },
                    {
                        text: [
                            {text: "\n\nSuperviseur: ", style: "paragraphe1"},
                            {text: "{{ $avec->superviseur->nom }}", style: "paragraphe2"},
                        ]
                    },
                    {
                        text: [
                            {text: "Animateur: ", style: "paragraphe1"},
                            {text: "{{ $avec->animateur->nom }}\n\n\n", style: "paragraphe2"},
                        ]
                    },
                    {
                        table: {
                            widths: ['*', '*'],
                            body: [
                                [{text: "SITUATION GENERALE DE L'AVEC", style: "title"}, ""],
                                ["", ""],
                                ["", ""],
                                [{text: "1. Parts totales achetées: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `${data.partsTotAchetees} Parts`],
                                [{text: "2. En caisse: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `${data.montantencaisse} FC`],
                                [{text: "3. Intérêt total généré: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `${data.montantinteret} FC`],
                                [{text: "4. Caisse sociale: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `${data.montantsolidarite} FC`],
                                [{text: "5. Amandes: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `${data.montantamande} FC`],
                                ["", ""],
                                [{text: "6. Membres: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `${data.totalMembres} Personnes`],
                                [{text: "7. Hommes: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `${data.hommes} Hommes`],
                                [{text: "8. Femmes: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `${data.femmes} Femmes`],
                                [{text: "9. Actifs: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `${data.actifs} Actifs`],
                                [{text: "10. Inactifs: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `${data.inactifs} Inactifs`],
                                [{text: "11. Abandons: ", style: "paragraphe1", margin: [10, 0, 10, 0]}, `${data.abandons} Abandons`],
                                ["", ""],
                                ["", ""],
                            ]
                        },
                        layout: 'noBorders',
                        fillColor: "#e1e1e1",
                    },
                    {text: "\n\n\n"},
                    {table: {
                        headerRows: 1,
                            widths: ["*", "*", "*", "*", "*"],
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
            pdfMake.createPdf(docDefinition).download('rapport des transactions de soutien.pdf');
        })
    </script>
@endsection
