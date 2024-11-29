function checkfrequentation(element) {
    let div_mouvement = document.getElementById("mouvement");
    let input_parts_achetees = document.getElementById("parts_achetees");
    let input_cotisation = document.getElementById("cotisation");
    if (element.value === "présent(e)") {
        input_parts_achetees.setAttribute("required", "true");
        input_cotisation.setAttribute("required", "true");
        div_mouvement.style.display = "unset";
    }else {
        input_parts_achetees.removeAttribute("required");
        input_cotisation.removeAttribute("required");
        div_mouvement.style.display = "none";
    }
}

function addamande(element) {
    let input_amande = document.getElementById("amande")
    let frequenceamande = document.getElementById("id_frequence_"+element.id) ? document.getElementById("id_frequence_"+element.id).value : 0;
    let total_amande = document.getElementById("id_total_"+element.id);

    if (element.checked) {
        total_amande.value = parseFloat(element.value) * parseFloat(frequenceamande);
    }else {
        total_amande.value = 0;
    }

    const inputs_amande_tot = document.querySelectorAll("input.amande_a_payer");
    let somme = 0;

    inputs_amande_tot.forEach(input=>{
        const valeur = parseFloat(input.value) || 0;
        somme += valeur;
    })

    input_amande.value = somme;
}

function addfrequenceamande(element) {
    let ident = element.id.substring(13)
    let regle_amande = document.getElementById(ident);
    let total_amande = document.getElementById("id_total_"+ident);
    let input_amande = document.getElementById("amande");

    if (regle_amande.checked) {
        total_amande.value = parseFloat(element.value) * parseFloat(regle_amande.value);

        const inputs_amande_tot = document.querySelectorAll("input.amande_a_payer");
        let somme = 0;

        inputs_amande_tot.forEach(input=>{
            const valeur = parseFloat(input.value) || 0;
            somme += valeur;
        })

        input_amande.value = somme;
    }

}

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

function checkmaxandminpret(element) {
    if (parseFloat(element.value) > parseFloat(element.max)) {
        if (element.value !== "") {
            element.value = parseFloat(element.min);
            $.notify({
                icon: 'icon-bell',
                title: 'Avecmanager',
                message: 'ce membre ne peut pas prendre ce prêt car supérieur à 3 fois son montant épargné',
            }, {
                type: 'warning',
                placement: {
                    from: "bottom",
                    align: "right"
                },
                time: 1000,
            });
        }
        calculer_taux_interet(element);
    }
    if (parseFloat(element.value) < parseFloat(element.min)) {
        if (element.value !== "") {
            element.value = parseFloat(element.min);
            $.notify({
                icon: 'icon-bell',
                title: 'Avecmanager',
                message: 'ce membre ne peut pas prendre ce prêt car supérieur à 3 fois son montant épargné',
            }, {
                type: 'warning',
                placement: {
                    from: "bottom",
                    align: "right"
                },
                time: 1000,
            });
        }
        calculer_taux_interet(element);
    }

    if (element.value === ""){
        let input_montant_a_rembourser = document.getElementById("id_montant_a_rembourser");
        let input_taux_interet = document.getElementById("id_taux_interet");
        let input_date_remboursement = document.getElementById('id_date_remboursement');

        input_date_remboursement.removeAttribute('required')

        input_montant_a_rembourser.value = 0;
        input_taux_interet.value = 0;
        element.value = 0;
    }
}

const dateInput = document.getElementById("id_date_remboursement");
dateInput.addEventListener("input", (event) => {
    const today = new Date();
    const dateinputed = new Date(event.target.value);

    if (isNaN(dateinputed.getTime())) {
        $.notify({
            icon: 'icon-bell',
            title: 'Avecmanager',
            message: 'la date est invalide',
        }, {
            type: 'warning',
            placement: {
                from: "bottom",
                align: "right"
            },
            time: 1000,
        });
        event.target.value = "";
        return;
    }
    const monthDifference = (today.getFullYear() - dateinputed.getFullYear()) * 12 + (today.getMonth() - dateinputed.getMonth());
    if (Math.abs(monthDifference) > 3) {
        $.notify({
            icon: 'icon-bell',
            title: 'Avecmanager',
            message: 'la date de remboursement ne doit pas dépassé trois mois dans le passé.',
        }, {
            type: 'warning',
            placement: {
                from: "bottom",
                align: "right"
            },
            time: 1000,
        });

        event.target.value = "";
    }
})

function check_div_retard_remboursement(element) {
    let div = document.getElementById("div_montant_reglement_en_retard");
    let input_amande = document.getElementById("amande");
    let input_montant_amande_retard_reglement = document.getElementById("montant_"+element.id);
    if (element.checked) {
        div.style.display = "flex";
    }else {
        input_amande.value = parseFloat(input_amande.value) - parseFloat(input_montant_amande_retard_reglement.value);
        input_montant_amande_retard_reglement.value = 0
        div.style.display = "none";
    }
}

function calculateamanderetardreglement(element) {
    let input_amande = document.getElementById("amande");

    const inputs_amande_tot = document.querySelectorAll("input.amande_a_payer");
    let somme = 0;

    inputs_amande_tot.forEach(input => {
        const valeur = parseFloat(input.value) || 0;
        somme += valeur;
    })

    input_amande.value = somme;
}



