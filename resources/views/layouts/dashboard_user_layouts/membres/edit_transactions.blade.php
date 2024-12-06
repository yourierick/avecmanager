@extends('base_utilisateur')
@section("style")
    <style>
        .perso:hover{
            color: #000000;
            background-color: #d9d6d6;
            border-radius: 9px;
            transition: .5s ease;
        }
        .input-readonly[readonly] {
            background-color: transparent !important;
        }

        .multiline-label {
            display: block;
            width: 100px;
            word-wrap: break-word;
            white-space: normal;
        }
        select option[disabled] {
            color: #c5c5c5;
        }
    </style>
@endsection
@section('big_title')
    <div class="row mb-4">
        <div class="col-md-8">
            <span class="text-muted bi-file-word-fill">
                PROJET REFERENCE: {{ $transaction->projet->code_reference }}
            </span>
            <br><span style="color: #ee6900; text-transform: uppercase; font-weight: bold">
                AVEC: {{ $transaction->avec->designation }}
            </span>
        </div>
    </div>
@endsection
@section('page_courant', "membre de l'avec")
@section('content')
    <hr>
    <div class="row row-card-no-pd" style="background-color: transparent">
        <div class="col-sm-12 col-md-6">
            <div class="card card-stats card-round"  style="background-color: transparent">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <img src="/storage/{{ $transaction->membre->photo }}" style="width: 100px; height: 100px; border-radius: 50px" class="mb-2" alt="...">
                            </div>
                        </div>
                        <div class="col-7 col-stats m-0 p-0">
                            <div class="numbers">
                                <p class="m-0 text-primary" style="font-weight: bold">MEMBRE ID: {{ $transaction->membre->id }}</p>
                                <p class="m-0 text-secondary">{{ $transaction->membre->nom}}</p>
                                <p class="m-0">Sexe: {{ $transaction->membre->sexe}}</p>
                                <p class="m-0">Télephone: {{ $transaction->membre->numeros_de_telephone}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <h3>SITUATION DE DETTE DU MEMBRE</h3>
                    <p class="m-0 p-1" style="color: darkorange; font-weight: bold">
                        @php
                            $dette = $transaction->membre->credit + $transaction->membre->interets_sur_credit
                        @endphp
                        PRET: {{ $dette }} FC
                    </p>
                    <p class="m-0 p-1">
                        Date de remboursement: <span @if($transaction->membre->date_de_remboursement <= \Carbon\Carbon::today()) @class(['bx-flashing']) @endif>{{ $transaction->membre->date_de_remboursement ? $transaction->membre->date_de_remboursement->format('d/m/Y') : "" }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="p-4 shadow-lg">
        <h4>mouvement monétaire hebdomadaire du membre</h4>
        <form method="post" action="{{ route('gestionprojet.save_edition_transaction_membre', $transaction->id) }}" class="mt-6 space-y-6">
            @csrf
            @method('put')
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="id_mois">mois</label>
                    <select name="mois_id" id="id_mois" class="form-control" required>
                        <option selected disabled>--------------</option>
                        @foreach($cycle_de_gestion as $mois)
                            <option @if(old('mois_id', $transaction->cycle_de_gestion->id) == $mois->id) selected @endif value="{{ $mois->id }}">{{ $mois->designation }}</option>
                        @endforeach
                    </select>
                </div>
                <x-input-error :messages="$errors->get('mois_id')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="id_semaine">semaine</label>
                    <select name="semaine" id="id_semaine" class="form-control" required>
                        <option @if(old("semaine", $transaction->semaine) === "semaine 1") selected @endif value="{{ $transaction->semaine }}">semaine 1</option>
                        <option @if(old("semaine", $transaction->semaine) === "semaine 2") selected @endif value="{{ $transaction->semaine }}">semaine 2</option>
                        <option @if(old("semaine", $transaction->semaine) === "semaine 3") selected @endif value="{{ $transaction->semaine }}">semaine 3</option>
                        <option @if(old("semaine", $transaction->semaine) === "semaine 4") selected @endif value="{{ $transaction->semaine }}">semaine 4</option>
                        <option @if(old("semaine", $transaction->semaine) === "semaine 5") selected @endif value="{{ $transaction->semaine }}">semaine 5</option>
                    </select>
                </div>
                <x-input-error :messages="$errors->get('semaine')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="semaine_debut">semaine du</label>
                    <input id="semaine_debut" name="semaine_debut" type="date"
                           class="form-control"
                           value="{{ old('semaine_debut', $transaction->semaine_debut->format('Y-m-d')) }}" required
                    />
                </div>
                <x-input-error :messages="$errors->get('semaine_debut')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="semaine_fin">au</label>
                    <input id="semaine_fin" name="semaine_fin" type="date"
                           class="form-control"
                           value="{{ old('semaine_fin', $transaction->semaine_fin->format('Y-m-d')) }}" required
                    />
                </div>
                <x-input-error :messages="$errors->get('semaine_fin')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="date_de_la_reunion">date de la réunion</label>
                    <input id="date_de_la_reunion" name="date_de_la_reunion" type="date"
                           class="form-control"
                           value="{{ old('date_de_la_reunion', $transaction->date_de_la_reunion ? $transaction->date_de_la_reunion->format('Y-m-d') : '') }}" required
                    />
                </div>
                <x-input-error :messages="$errors->get('date_de_la_reunion')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="num_reunion">numéro de la réunion</label>
                    <input id="num_reunion" name="num_reunion" type="text"
                           class="form-control input-readonly"
                           value="{{ old("num_reunion", $transaction->num_reunion) }}" required
                           readonly
                           placeholder="numéro de la réunion"/>

                </div>
                <x-input-error :messages="$errors->get('num_reunion')" class="mt-2 text-danger"/>
            </div>
            <div class="mb-3">
                <div class="form-group form-group-default">
                    <label for="id_frequentation">fréquentation</label>
                    <select name="frequentation" onchange="checkfrequentation(this)" id="id_frequentation" class="form-control" required>
                        <option @if(old('frequentation', $transaction->frequentation) === "présent(e)") selected @endif value="présent(e)">présent(e)</option>
                        <option @if(old('frequentation', $transaction->frequentation) === "absent(e)") selected @endif value="absent(e)">absent(e)</option>
                    </select>
                </div>
                <x-input-error :messages="$errors->get('frequentation')" class="mt-2 text-danger"/>
            </div>
            <div id="mouvement">
                <div class="mb-3">
                    <div class="form-group form-group-default">
                        <label for="parts_achetees">parts achetées</label>
                        <input id="parts_achetees" name="parts_achetees" type="number"
                               class="form-control"
                               min="1"
                               max="{{ $transaction->avec->maximum_part_achetable }}"
                               onchange="checkmaxandmin(this)"
                               value="{{ old("parts_achetees", $transaction->parts_achetees) }}" required
                               placeholder="parts achetées par le membre"/>
                    </div>
                    <x-input-error :messages="$errors->get('parts_achetees')" class="mt-2 text-danger"/>
                </div>
                <div class="mb-3">
                    <div class="form-group form-group-default">
                        <label for="cotisation">cotisation solidarité</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">FC</span>
                            <input type="number" onchange="checkmaxandmin(this)" value="{{ old("cotisation", $transaction->cotisation) }}" min="{{ $transaction->avec->valeur_montant_solidarite }}" max="{{ $transaction->avec->valeur_montant_solidarite }}" id="cotisation" name="cotisation" placeholder=" cotisation solidaire du membre" required class="form-control" aria-label="cotisation">
                            <span class="input-group-text">.00</span>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('cotisation')" class="mt-2 text-danger"/>
                </div>
                <div class="mb-3">
                    <div class="form-group form-group-default m-0">
                        <label for="select_regle">règles de taxations de l'amande</label>
                        @if($transaction->membre->credit > 0)
                            <div class="row p-4">
                                <div class="col-md-3 col-xs-12">
                                    <div class="d-flex">
                                        <input type="checkbox" onchange="check_div_retard_remboursement(this)" value="0" class="mr-2" id="amande_reglement">
                                        <label style="text-transform: lowercase; font-size: 10pt!important; color: #15b01c!important" for="amande_reglement">rétard de réglement de dette</label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <div class="input-group mb-3" id="div_montant_reglement_en_retard" style="display: none">
                                        <span class="input-group-text">Montant de l'amande</span>
                                        <input type="number" onchange="calculateamanderetardreglement(this)" value="0" min="0" step="any" id="montant_amande_reglement" class="form-control ml-2 amande_a_payer">
                                        <span class="input-group-text">FC</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @foreach($regles_amande as $regle)
                            <div class="row p-4">
                                <div class="col-md-6 col-sm-12">
                                    <input type="checkbox" onchange="addamande(this)" class="mr-2" id="regle{{ $loop->iteration }}" name="regle_amande" value="{{ $regle->amande }}">
                                    <label style="text-transform: lowercase; font-size: 10pt!important; color: #15b01c!important" for="regle{{ $loop->iteration }}">{{ $regle->regle }}</label>
                                </div>
                                <div class="col-md-6 col-sm-12 d-flex">
                                    <div>
                                        <input onchange="addfrequenceamande(this)" min="1" value="1" class="form-control text-danger" id="id_frequence_regle{{ $loop->iteration }}" type="number" placeholder="fréquence de l'amande">
                                        <label for="id_frequence_regle{{ $loop->iteration }}">fois</label>
                                    </div>
                                    <div>
                                        <input disabled class="form-control amande_a_payer" value="0" id="id_total_regle{{ $loop->iteration }}" type="number">
                                        <label for="id_total_regle{{ $loop->iteration }}">FC</label>
                                    </div>
                                </div>
                            </div>
                            <hr class="w-100 p-0">
                        @endforeach
                    </div>
                    <div class="form-group form-group-default">
                        <label for="amande">amande</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">FC</span>
                            <input type="number" id="amande" name="amande" readonly value="{{ old("amande", $transaction->amande) }}" placeholder=" amande" class="input-readonly  ml-2 form-control text-danger" style="font-weight: bold" aria-label="amande">
                            <span class="input-group-text">.00</span>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('amande')" class="mt-2 text-danger"/>
                </div>
                @if($modulo_credit == 0 && $transaction->membre->credit == 0 || $modulo_credit == 0 && $transaction->membre->credit != 0 && $transaction->membre->date_de_remboursement > \Carbon\Carbon::today())
                    <div class="mb-3" id="div_pret">
                        <div class="form-group form-group-default">
                            <label for="credit">donner un prêt</label>
                            <div class="row">
                                <div class="input-group mb-3 col-xs-12 col-md-6">
                                    <span class="input-group-text">Crédit</span>
                                    @php
                                        $triple_epargne = ($transaction->membre->part_tot_achetees * $transaction->avec->valeur_part) * 3
                                    @endphp
                                    <input type="number" min="0" max="{{ $triple_epargne }}" onchange="checkmaxandminpret(this)"  oninput="calculer_taux_interet(this)" id="id_pret" value="{{ old("credit", $transaction->credit) }}" name="credit" placeholder=" donner un prêt au membre" class="form-control" style="text-align: right; font-weight: bold" aria-label="prêt">
                                    <span class="input-group-text">FC</span>
                                </div>
                                <div class="input-group mb-3 col-xs-12 col-md-6">
                                    <span class="input-group-text">Date de remboursement</span>
                                    <input type="date" id="id_date_remboursement" value="{{ old("date_de_remboursement", $transaction->date_de_remboursement ? $transaction->date_de_remboursement->format("Y-m-d"):'') }}" min="{{ \Carbon\Carbon::tomorrow()->format("Y-m-d") }}" name="date_de_remboursement" class="form-control ml-2" style="text-align: right; font-weight: bold" aria-label="date">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 col-xs-12 col-md-6">
                                    @php
                                        $interet = ($transaction->credit * $transaction->taux_interet)/100;
                                    @endphp
                                    <span class="input-group-text">Montant à rembourser</span>
                                    <input type="number" id="id_montant_a_rembourser" style="text-align: right; color: #00275b; font-weight: bold" readonly value="{{ old("montant_a_rembourser", $transaction->credit + $interet) }}" class="input-readonly form-control" name="montant_a_rembourser" aria-label="montant à rembourser">
                                    <span class="input-group-text">FC</span>
                                </div>
                                <div class="input-group mb-1 col-xs-12 col-md-6">
                                    <span class="input-group-text">Taux d'intérêt</span>
                                    <input type="number" id="id_taux_interet" style="text-align: right; color: orangered; font-weight: bold" readonly value="{{ old("taux_interet", $transaction->taux_interet) }}" name="taux_interet" class="input-readonly form-control" aria-label="taux">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('credit')" class="mt-2 text-danger"/>
                    </div>
                @endif

                @if($transaction->membre->credit != 0)
                    <div class="mb-3">
                        <div class="form-group form-group-default">
                            <label for="remboursement">remboursement du crédit</label>
                            <div class="input-group mb-3">
                                @php
                                    $max_remboursement = $transaction->membre->credit + $transaction->membre->interets_sur_credit;
                                @endphp
                                <span class="input-group-text">FC</span>
                                <input type="number" value="{{ old("remboursement", $transaction->credit_rembourse) }}" min="0" max="{{ $max_remboursement }}" name="remboursement" placeholder=" remboursement d'un prêt" class="form-control" aria-label="remboursement">
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('remboursement')" class="mt-2 text-danger"/>
                    </div>
                @endif
            </div>
            <div class="flex items-center mt-2 gap-4">
                <button class="btn btn-primary text-light" data-bs-toggle="modal" data-bs-target="#ModalSave" type="button"> Enregistrer
                </button>
                <div class="modal fade" id="ModalSave" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">
                                    <span class="fw-mediumbold"> Demande de</span>
                                    <span class="fw-light"> Confirmation</span>
                                </h5>
                                <button type="button" class="close" data-bs-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="modal-body">
                                <p class="small">
                                    vous êtes sur le point d'enregistrer la transaction, veuillez vérifier la conformité
                                    des informations avant l'enregistrement. Si c'est bon alors cliquez sur oui.
                                </p>
                                <button type="submit" class="btn btn-primary">
                                    oui
                                </button>
                                <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                                    vérifier
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section("scripts")
    <script>
        @if(session('success'))
            $(document).ready(function () {
                $.notify({
                    icon: 'icon-bell',
                    title: 'Avecmanager',
                    message: '{{ session('success') }}',
                }, {
                    type: 'secondary',
                    placement: {
                        from: "bottom",
                        align: "right"
                    },
                    time: 1000,
                });
            });
        @elseif(session('error'))
            $(document).ready(function () {
                $.notify({
                    icon: 'icon-bell',
                    title: 'Avecmanager',
                    message: '{{ session('error') }}',
                }, {
                    type: 'danger',
                    placement: {
                        from: "bottom",
                        align: "right"
                    },
                    time: 1000,
                });
            });
        @endif

        function calculer_taux_interet(element) {
            if (element.value !== 0) {
                $.ajax({
                    url: "../calcul_taux_interet/"+element.value+"/"+{{ $transaction->avec->id }},
                    type: "get",

                    success: function (data) {
                        let input_montant_a_rembourser = document.getElementById("id_montant_a_rembourser");
                        let input_taux_interet = document.getElementById("id_taux_interet");
                        let input_date_remboursement = document.getElementById('id_date_remboursement');
                        let interet = ((parseFloat(data.taux) * parseFloat(element.value))/100).toFixed(2);
                        input_montant_a_rembourser.value = (parseFloat(element.value) + parseFloat(interet)).toFixed(2);
                        input_taux_interet.value = data.taux;

                        input_date_remboursement.setAttribute("required", "true");
                    }, error: function (xhr, status, error) {
                        console.log(error)
                    }
                })
            }
        }
    </script>
    <script src="{{ asset("js/personnal_scripts/mouvement_monetaire.js") }}"></script>
@endsection
