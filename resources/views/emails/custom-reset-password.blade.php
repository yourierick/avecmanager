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
            <p>Vous recevez cet e-mail parce qu'on a reçu une requête de réinitialisation de mot de passe pour votre compte</p>
            <a href="{{ $url }}" class="btn btn-secondary">Réinitialiser le mot de passe</a>

            <p>Ce lien de réinitialisation de votre mot de passe expirera dans 60 minutes</p>
            <p>Si vous n'êtes pas à l'origine de cette requête, ignorez tout simplement cet e-mail</p>

            <p>Cordialement,</p>
            {{ config('app.name') }}
        </div>
    </body>
</html>
