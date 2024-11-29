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

$(document).ready(function (){
    let id_dept = document.getElementById('lbl_dept_id').innerHTML;
    let id_qualite = document.getElementById('lbl_qualite_id').innerHTML;
    $.ajax({
        url: '/qualites_loader/' + id_dept,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#id_qualite').empty();
            $('#id_qualite').append('<option value="">Sélectionner une fonction</option>');
            $.each(data, function(key, value){
                $('#id_qualite').append('<option value="' + value.id + '">' + value.designation + '</option>');
                if (value.id === parseInt(id_qualite)) {
                    $('#id_qualite').val(value.id); // Sélectionnez l'option
                }
            })
        }, error: function (xhr, status, error){
            console.log("Erreur: " + error);
            alert("une erreur est survenue lors du traitement de le requête: "+ error);
        }
    })

    $('#id_dept').change(function() {
        var id_dept = $(this).val();
        if (id_dept) {
            $.ajax({
                url: '/qualites_loader/' + id_dept,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#id_qualite').empty();
                    $('#id_qualite').append('<option value="">Sélectionner une fonction</option>');
                    $.each(data, function(key, value){
                        $('#id_qualite').append('<option value="' + value.id + '">' + value.designation + '</option>');
                    })
                }, error: function (xhr, status, error){
                    console.log("Erreur: " + error);
                    alert("une erreur est survenue lors du traitement de le requête: "+ error);
                }
            })
        }else {
            $('#id_qualite').empty();
            $('#id_qualite').append('<option value="">Sélectionner une fonction</option>');
        }
    })
})
