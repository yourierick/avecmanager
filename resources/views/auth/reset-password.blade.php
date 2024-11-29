<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification d'email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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

        }
        p, button{
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }

        .requirement {
            font-size: 14px;
            flex: 1 0 50%;
            min-width: max-content;
            margin: 2px 0;
        }

        .requirement:before {
            content: '\2639';
            padding-right: 5px;
            font-size: 1.6em;
            position: relative;
            top: .15em;
        }

        .requirement:not(.valid) {
            color: #808080;
        }

        .requirement.valid {
            color: #4CAF50;
        }

        .requirement.valid:before {
            content: '\263A';
        }

        .requirement.error {
            color: red;
        }

        button[type=submit]:disabled {
            color: #808080;
            background-color: #f5f5f5;
            cursor: not-allowed;
            border-radius: 4px;
            border: 1px solid rgba(0, 0, 0, 0.12);
        }

        button[type=submit] {
            transition: .10s;
            border-radius: 4px;
            border: 1px solid rgba(0, 0, 0, 0.12);
            background-color: #2450a2;
            font-size:10pt;
            color: #fafafa;
        }

        button[type=submit]:not(:disabled):hover {
            border-color: transparent;
            background-color: #1f4793;
            color: #fafafa;
        }

        button[type=submit]:disabled {
            color: #808080;
            background-color: #f5f5f5;
            cursor: not-allowed;
        }

        #id_email:invalid {
            border-color: #900;
            background-color: #FDD;
        }

        #id_email:focus:invalid {
            outline: none;
        }
    </style>
</head>
<body>
    <div id="form_display">
        <div class="flex">
            <img class="logo-icon me-2" style="width: 60px; height: 40px" src="{{ asset('styles_dashboard/assets/img/favicon.png') }}" alt="logo">
            <span style="font-weight: 500; font-size: 14pt" class="text-muted">AVEC MANAGER</span>
        </div>
        <div class="dropdown-divider"></div>

        <section class="mt-1 shadow p-4">
            <form method="post" id="formulaire" action="{{ route('password.store') }}"
                  class="mt-6 space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <input id="email" placeholder="email" class="block mb-2 w-full form-control" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div>
                    <div>
                        <div class="mb-2">
                            <div class="form-group form-group-default">
                                <input id="password" class="form-control"
                                       type="password"
                                       name="password"
                                       required placeholder="nouveau mot de passe" autocomplete="new-password"/>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger"/>
                        </div>
                        <div class="mb-1">
                            <div class="form-group form-group-default">
                                <input id="password-confirmation" class="form-control"
                                       type="password" name="password_confirmation" required
                                       placeholder="confirmer le mot de passe"/>
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger"/>
                        </div>
                        <div class="password-requirements">
                            <p class="requirement error" id="match" style="display: none">Les mots de passe
                                doivent correspondre</p>
                        </div>
                    </div>
                    <div>
                        <p class="mb-2">Exigences du mot de passe</p>
                        <p class="small text-muted mb-2">Pour créer un nouveau mot de passe, vous devez
                            remplir toutes les exigences suivantes:</p>
                        <ul class="small text-muted pl-4 mb-0">
                            <li class="requirement" id="length">Minimum 8 caractères</li>
                            <li class="requirement" id="lowercase">Doit inclure une miniscule</li>
                            <li class="requirement" id="uppercase">Doit inclure une majuscule</li>
                            <li class="requirement" id="number">Doit inclure un chiffre</li>
                            <li class="requirement" id="characters">Doit inclure un caractère spécial:
                                #.-?!@$%^&*
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="flex items-center" style="margin-top: 10px">
                    <button type="submit" id="submit_form" class="btn-sm btn-submit" disabled>Réinitialiser le mot de passe</button>
                </div>
            </form>
        </section>
    </div>
</body>
<script>
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("password-confirmation");
    const matchPassword = document.getElementById("match");
    const form = document.getElementById("formulaire");
    const submit_form_btn = document.getElementById("submit_form")
    confirmPassword.addEventListener("blur", (event) => {
        const value = event.target.value

        if (value.length && value !== password.value) {
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
            value === confirmValue
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
</script>
</html>



