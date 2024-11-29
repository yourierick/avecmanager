<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="with=device-width, initial-scale=1.0">
        <title>Vérifier votre adresse e-mail</title>

        <style>
            .container {
                justify-content: center;
                align-items: center;
                border: 1px solid #ccc;
            }
            .logo {
                display: flex;
                justify-content: center;
                align-items: center;
            }
        </style>
        <link rel="stylesheet" href="{{ asset("css_bootstrap/vendor/bootstrap/css/bootstrap.min.css") }}"/>
    </head>
    <body>
        <header class="logo" style="margin-bottom: 10px">
            <img src="https://drive.google.com/uc?export=view&id=1WZ35adSEfPFLw9aa1eOx0HCjgAu4orVp" alt="logo" style="max-width: 80px!important; height: 50px">
            <h3>AVEC MANAGER 1.0</h3>
        </header>
        <div class="container" style="padding: 10px">
            <h1>Bonjour !</h1>
            <p>Veuillez, s'il vous plait cliquer sur ce lien en-dessous pour vérifier votre adresse e-mail</p>
            <a href="{{ $url }}" class="btn btn-secondary">Vérifier l'adresse e-mail</a>

            <p>Si vous ne reconnaissez pas cette requête, aucune autre action n'est nécessaire</p>

            <p>Cordialement,</p>
            {{ config('app.name') }}
        </div>
    </body>
</html>
