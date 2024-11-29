function checkmaxandmin(element) {
    if (parseFloat(element.value) > parseFloat(element.max)) {
        element.value = parseFloat(element.min);
        $.notify({
            icon: 'icon-bell',
            title: 'Avecmanager',
            message: 'le montant entré est supérieur au maximum',
        }, {
            type: 'danger',
            placement: {
                from: "bottom",
                align: "right"
            },
            time: 1000,
        });
        element.focus();
    }
    if (parseFloat(element.value) < parseFloat(element.min)) {
        element.value = parseFloat(element.min);
        $.notify({
            icon: 'icon-bell',
            title: 'Avecmanager',
            message: 'le montant entré est inférieur au minimum',
        }, {
            type: 'danger',
            placement: {
                from: "bottom",
                align: "right"
            },
            time: 1000,
        });
        element.focus();
    }
}
