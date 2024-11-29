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
        <div class="mb-4 text-sm text-dark">
            {{ __("Vous avez oublié votre mot de passe? Pas de problème. Laissez-nous connaitre votre adresse email, on vous enverra un lien qui vous permettra d'en choisir un nouveau.") }}
        </div>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label class="text-dark" style="font-weight: bold" for="email" :value="__('Email')" />
                <input id="email" class="block mt-1 w-full form-control" type="email" name="email" placeholder="veuillez renseigner votre adresse email" value="{{ old('email') }}" required autofocus>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <button class="btn btn-secondary">
                    {{ __('Lien de réinitialisation') }}
                </button>
            </div>
        </form>
    </div>
</body>
</html>
