const password = document.getElementById("password");
const confirmPassword = document.getElementById("password-confirmation");
const matchPassword = document.getElementById("match");
const form = document.getElementById("formulaire");
const submit_form_btn = document.getElementById("submit_form")
confirmPassword.addEventListener("blur", (event) => {
    const value = event.target.value

    if (value.length && value != password.value) {
        matchPassword.style.display = "unset"
    } else {
        matchPassword.style.display = "none"
    }
})

const updateRequirement = (id, valid) => {
    const requirement = document.getElementById(id);
    if (valid) {
        requirement.classList.add("valid");
    } else {
        requirement.classList.remove("valid");
    }
};

password.addEventListener("input", (event) => {
    const value = event.target.value;
    updateRequirement('length', value.length >= 8)
    updateRequirement('lowercase', /[a-z]/.test(value))
    updateRequirement('uppercase', /[A-Z]/.test(value))
    updateRequirement('number', /\d/.test(value))
    updateRequirement('characters', /[#.?!@$%^&*-]/.test(value))
});

const handleFormValidation = () => {
    const value = password.value;
    const confirmValue = confirmPassword.value;
    if (
        value.length >= 8 &&
        /[a-z]/.test(value) &&
        /[A-Z]/.test(value) &&
        /\d/.test(value) &&
        /[#.?!@$%^&*-]/.test(value) &&
        value == confirmValue
    ) {
        submit_form_btn.removeAttribute("disabled");
        return true;
    }
    submit_form_btn.setAttribute("disabled", true);
    return false;
};

form.addEventListener("change", () => {
    handleFormValidation();
});

function previewImage() {
    const fileInput = document.getElementById('id_photo');
    const imagePreview = document.getElementById('imagePreview');

    const file = fileInput.files[0];
    if (file) {
        const imageUrl = URL.createObjectURL(file);

        // Créez un élément <img> pour afficher la prévisualisation
        imagePreview.src = imageUrl;
    }
}

var fileInput = document.getElementById('id_photo');
fileInput.addEventListener("change", previewImage);


let div_projet = document.getElementById('div_projet');
let div_fonction = document.getElementById('div_fonction');
let select_projet = document.getElementById('id_projet');
let select_fonction = document.getElementById('id_fonction');
let typecompte = document.getElementById('id_type');



document.addEventListener("DOMContentLoaded", function () {
    if (typecompte.value === 'utilisateur') {
        div_projet.style.display = "unset";
        div_fonction.style.display = "unset";
        select_projet.setAttribute('required', 'true');
        select_fonction.setAttribute('required', 'true');
    }
    if (typecompte.value === 'visiteur') {
        div_projet.style.display = "unset";
        select_projet.setAttribute('required', 'true');
    }
})
function typecomptechange() {
    if (typecompte.value === 'administrateur') {
        select_projet.removeAttribute('required');
        select_fonction.removeAttribute('required');
        div_projet.style.display = "none";
        div_fonction.style.display = "none";
    }else if(typecompte.value === "utilisateur") {
        div_projet.style.display = "unset";
        div_fonction.style.display = "unset";
        select_projet.setAttribute('required', 'true');
        select_fonction.setAttribute('required', 'true');
    }else if(typecompte.value === "visiteur") {
        div_projet.style.display = "unset";
        select_projet.setAttribute('required', 'true');
        div_fonction.style.display = "none";
        select_fonction.removeAttribute('required');
    }
}
