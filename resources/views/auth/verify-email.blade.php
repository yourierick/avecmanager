<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification d'email</title>
    <link rel="icon" href="{{ asset('styles_dashboard/assets/img/favicon.png') }}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{ asset("css_bootstrap/vendor/bootstrap/css/bootstrap.min.css") }}"/>
    <script defer src="{{ asset('assets/plugins/fontawesome/js/all.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-3.4.1.js') }}"></script>
    <style>
        @import "https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700";
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 8px;
        }
        #form_display {
            display: flex;
            flex-direction: column;
            width: 500px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
            background-color: #efefef;
        }
        p, button{
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div id="form_display">
        <div class="flex">
            <img class="logo-icon me-2" style="width: 60px; height: 40px" src="{{ asset('styles_dashboard/assets/img/favicon.png') }}" alt="logo">
            <span style="font-weight: bold; font-size: 14pt" class="text-muted">AVEC MANAGER</span>
        </div>
        <hr style="border: 2px solid #095ba9; width: 100%">
        <div class="text-dark">
            <p>{{ __("Nous sommes content de vous voir! avant de commencer, pouvez-vous vérifier votre adresse mail en cliquant sur le lien qu'on vient de partager sur votre boite? si vous ne l'avez pas reçu, nous serons heureux de vous le renvoyer") }}</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="font-medium text-sm text-dark">
                {{ __('Un nouveau lien de vérification a été envoyé à votre adresse mail renseignée lors de la création de votre compte') }}
            </div>
        @endif

        <div style="display: flex; flex-direction: row; gap: 3px">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div>
                    <button class="btn btn-primary">Renvoyer</button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger">
                    {{ __('Déconnexion') }}
                </button>
            </form>
        </div>
    </div>
</body>
</html>
