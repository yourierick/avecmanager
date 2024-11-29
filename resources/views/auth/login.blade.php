<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@yield('AVECMANAGER')</title>
        <!-- Meta -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="{{ asset('styles_dashboard/assets/img/favicon.png') }}" type="image/x-icon"/>
        <meta name="description" content="Administration Dashboard">
        <meta name="author" content="Ir. ERICK BITANGALO">
        <link rel="stylesheet" href="{{ asset("css_bootstrap/bootstrap.css") }}">
        <link rel="stylesheet" href="{{ asset("login_css/style_index.css") }}">
    </head>
    <body>
        <div class="page">
            <div class="container">
                <div class="left">
                    <div class="login mb-1" style="font-size: 24pt">Connexion</div>
                    <div class="eula mt-1">
                        En vous connectant, vous acceptez les conditions de confidentialité et d'intégrité. <br>
                        @if (session('error_message'))
                            <span style="font-size: 10pt; text-align: center" class="mt-2 text-danger">{{ session('error_message') }}</span>
                        @endif
                        <x-input-error style="font-size: 10pt" :messages="$errors->get('error_msg')" class="mt-2 text-danger" />
                    </div>
                </div>
                <div class="right">
                    <form method="post" action="{{ route('login') }}" class="form">
                        @csrf
                        <label for="email">email</label>
                        <input type="email" id="email" name="email" class="mb-1">
                        <x-input-error style="font-size: 9pt" :messages="$errors->get('email')" class="mt-2 text-danger" />
                        <hr style="height: 3px; margin: 0;">
                        <label for="password">mot de passe</label>
                        <input type="password" id="password" name="password">
                        <x-input-error style="font-size: 9pt" :messages="$errors->get('password')" class="mt-2 text-danger" />
                        <br>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" id="forgot_password">mot de passe oublié?</a>
                        @endif
                        <div id="btn_container w-100 d-flex">
                            <button class="btn btn-secondary text-light" type="submit">se connecter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script src="{{ asset("login_css/login_scripts.js") }}"></script>
</html>

