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

function changestatustask(element) {
    let status = 0;
    const id_tache = element.id;
    if (element.checked) {
        status = 1;
    }
    $.ajax({
        url: '../change_status_task/'+id_tache,
        type: 'PUT',
        data: {
            status: status,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (status === 1) {
                document.getElementById("statut_"+ id_tache).innerHTML = "réalisée";
            }else {
                document.getElementById("statut_"+ id_tache).innerHTML = "non réalisée";
            }

            $.notify({
                icon: 'icon-bell',
                title: 'Avecmanager',
                message: 'le statut a été mis à jour',
            }, {
                type: 'secondary',
                placement: {
                    from: "bottom",
                    align: "right"
                },
                time: 1000,
            });
        }, error: function (xhr) {
            $.notify({
                icon: 'icon-bell',
                title: 'Avecmanager',
                message: "une erreur s'est produite lors du traitement de la requête",
            }, {
                type: 'danger',
                placement: {
                    from: "bottom",
                    align: "right"
                },
                time: 1000,
            });
        }
    })
}
