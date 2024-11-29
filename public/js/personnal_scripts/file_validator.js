function file_validate(input)
{
    const file = input.files[0];

    if (file) {
        const maxSize = 200 * 1024;
        const allowedExtensions = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!allowedExtensions.includes(file.type)) {
            $.notify({
                icon: 'icon-bell',
                title: 'Avecmanager',
                message: 'Seuls les fichiers .jpg, .jpeg et .png sont autorisés',
            }, {
                type: 'danger',
                placement: {
                    from: "bottom",
                    align: "right"
                },
                time: 1000,
            });
            input.value = '';
            return false
        }

        if (file.size > maxSize) {
            $.notify({
                icon: 'icon-bell',
                title: 'Avecmanager',
                message: 'le fichier dépasse la taille maximale autorisée de 200 Ko',
            }, {
                type: 'danger',
                placement: {
                    from: "bottom",
                    align: "right"
                },
                time: 1000,
            });

            input.value = '';
            return false
        }
        return true;
    }
}
