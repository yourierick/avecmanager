@extends('base')
@section('big_title')
    <div class="row mb-4">
        <div class="col-md-8">
            <span class="text-muted">PROJET REFERENCE: {{ $projet->code_reference }}</span>
            <br><span style="text-transform: uppercase">AVEC: {{ $avec->designation }}</span>
        </div>
        <div class="col-md-4">
            <div class="btn-group dropdown" style="float: right;">
                <button class="btn dropdown-toggle" type="button" style="background-color: whitesmoke; color: darkblue" data-bs-toggle="dropdown" aria-expanded="false">
                    Options
                </button>
                <ul class="dropdown-menu p-2" role="menu" style="background-color: #ffffff; border: 1px solid blue">
                    <li>
                        <a class="dropdown-item btn btn-outline-secondary perso" href="{{ route('report.rapport_analytique_de_avec', [$avec->id, $projet->id]) }}"><span class="bi-file-earmark-excel"> rapport analytique de l'avec</span></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
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
                                    <th style="background-color: #94DBFCFF">mois</th>
                                    <th style="background-color: #94DBFCFF">semaine</th>
                                    <th style="background-color: #94DBFCFF">semaine début</th>
                                    <th style="background-color: #94DBFCFF">semaine fin</th>
                                    <th style="background-color: #94DBFCFF">date de la réunion</th>
                                    <th style="background-color: #F7CD96FF">parts achetées</th>
                                    <th style="background-color: #F7CD96FF">cotisations solidarité</th>
                                    <th style="background-color: #F7CD96FF">amandes</th>
                                    <th style="background-color: #F7CD96FF">crédit</th>
                                    <th style="background-color: #F7CD96FF">remboursement</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
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
                                        <td>{{ $transaction->cycle_de_gestion->designation }}</td>
                                        <td>{{ $transaction->semaine }}</td>
                                        <td>{{ $transaction->semaine_debut->format("d/m/Y") }}</td>
                                        <td>{{ $transaction->semaine_fin->format("d/m/Y") }}</td>
                                        <td>{{ $transaction->date_de_la_reunion->format("d/m/Y") }}</td>
                                        <td style="background-color: #ffe6c8">{{ $transaction->parts_achetees }} Parts</td>
                                        <td style="background-color: #ffe6c8">{{ $transaction->cotisation }} FC</td>
                                        <td style="background-color: #ffe6c8">{{ $transaction->amande }} FC</td>
                                        <td style="background-color: #ffe6c8">{{ $transaction->credit }} FC</td>
                                        <td style="background-color: #faf2a2">{{ $transaction->credit_rembourse }} FC</td>
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
    <script src="{{ asset("js/personnal_scripts/rapport_transaction_avec_script.js") }}"></script>
    <script>
        //génération du document excel
        document.getElementById("BtnExportToExcel").addEventListener('click', async function () {
            //import ExcelJS et FileSaver
            const ExcelJS = window.ExcelJS;
            const saveAs = window.saveAs;

            //créer un classeur et une feuille
            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet("Transactions");

            //Ajouter un titre et définir son style
            worksheet.mergeCells("A1:K1");
            const titleCell = worksheet.getCell("A1");
            titleCell.value = "RAPPORT DES TRANSACTIONS de L'AVEC {{ strtoupper($avec->designation) }}\n";
            titleCell.font = {name: "Times Roman", size: 15, bold: true};
            titleCell.alignment = {vertical: "top", horizontal: "center"};
            titleCell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: {argb: 'FFFFFF00'},
            };

            worksheet.mergeCells("A2:K2");
            const titleContextCell = worksheet.getCell("A2");
            titleContextCell.value = "SITUATION GENERALE DE L'AVEC";
            titleContextCell.font = {name: "Times Roman", size: 11, bold: true, color: "FF009CFF"};
            titleContextCell.alignment = {vertical: "top", horizontal: "left"};
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

            worksheet.mergeCells("C3:K3");
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
                    const excelCell = worksheet.getCell(rowIndex + 4, colIndex + 1); //début à la ligne 3
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
                saveAs(blob, 'rapport des transactions.xlsx');
            })
        })

        //génération du document pdf
        function extractTableData() {
            const table = document.getElementById("multi-filter-select");
            const rows = table.querySelectorAll('.ln-pdf');
            const data = [];

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                data.push({
                    mois: cells[0].textContent,
                    semaine: cells[1].textContent,
                    debut: cells[2].textContent,
                    fin: cells[3].textContent,
                    date: cells[4].textContent,
                    parts: cells[5].textContent,
                    cotisations: cells[6].textContent,
                    amandes: cells[7].textContent,
                    credit: cells[8].textContent,
                    remboursement: cells[9].textContent,
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
                    {text: "parts achetées", bold: true, style: "headerstyle"},
                    {text: "cotisations solidarité", bold: true, style: "headerstyle"},
                    {text: "amandes", bold: true, style: "headerstyle"},
                    {text: "crédit", bold: true, style: "headerstyle"},
                    {text: "remboursement", bold: true, style: "headerstyle"}
                ]
            ]

            let currentMois = null;
            let rowSpan = 0;

            data.forEach((row, index) => {
                if (row.mois !== currentMois) {
                    currentMois = row.mois;
                    rowSpan = data.filter(d => d.mois === currentMois).length;
                    tableBody.push([{text: row.mois, rowSpan}, row.semaine, row.debut, row.fin,
                        row.date, row.parts, row.cotisations, row.amandes, row.credit, row.remboursement])
                }else {
                    tableBody.push([{}, row.semaine, row.debut, row.fin, row.date, row.parts,
                        row.cotisations, row.amandes, row.credit, row.remboursement])
                }
            });

            return tableBody;
        }

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
            const rawData = extractTableData();
            const tableData = prepareTableData(rawData);

            const docDefinition = {
                pageOrientation: "landscape",
                defaultStyle: {
                    fontSize: 9,
                },
                content: [
                    {
                        table: {
                            widths: ['*'],
                            body: [
                                [{text: "RAPPORT DES TRANSACTIONS DE L'AVEC {{ strtoupper($avec->designation) }}", style: "header"}],
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
                            widths: [200, 553],
                            body: [
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
                    {table: {widths: ["*", "*", "*", "*", "*", "*", "*", "*", "*", "*"], body: tableData,}}
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
            pdfMake.createPdf(docDefinition).download('rapport des transactions.pdf');
        })
    </script>
@endsection
