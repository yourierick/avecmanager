<?php

namespace App\Http\Controllers;

use App\Models\Avec;
use App\Models\AxesProjet;
use App\Models\CaisseAmande;
use App\Models\CaisseEpargne;
use App\Models\CaisseInteret;
use App\Models\CaisseSolidarite;
use App\Models\CasOctroiSoutien;
use App\Models\ComiteAvec;
use App\Models\CycleDeGestion;
use App\Models\Membre;
use App\Models\ProjetAvec;
use App\Models\ReglesDeTaxationDesAmandes;
use App\Models\ReglesDeTaxationDesInterets;
use App\Models\SoutienCaisseSolidarite;
use App\Models\Transactions;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EquipeDeGestionController extends Controller
{
    public function list_du_personnel_projet($projet_id, Request $request): View
    {
        if ($request->user()->fonction === "chef de projet" || $request->user()->fonction === "assistant suivi et évaluation" || $request->user()->fonction === "coordinateur du projet") {
            $personnel = User::where('projet_id', $projet_id)->get();
        } else {
            $personnel = User::where('projet_id', $projet_id)->where('fonction', 'animateur')->where("superviseur_id", $request->user()->id)->get();
        }
        $projet = ProjetAvec::find($projet_id);

        $breadcrumbs = [
            ['url'=>url('user_dashboard'), 'label'=>'Accueil'],
            ['url'=>url('lists_personnel', $projet_id), 'label'=>'Personnel'],
        ];

        return view('layouts.dashboard_user_layouts.list_personnel_assigne', ['current_user' => $request->user(),
            'personnel' => $personnel, 'projet' => $projet, 'breadcrumbs'=>$breadcrumbs]);
    }

    public function list_avecs($projet_id, Request $request): View
    {
        if ($request->user()->fonction === "chef de projet" || $request->user()->fonction === "assistant suivi et évaluation" || $request->user()->fonction === "coordinateur du projet") {
            $avecs = Avec::with(["animateur", "superviseur", "membres", "axe"])->where('projet_id', $projet_id)->get();
        } elseif ($request->user()->fonction === "superviseur") {
            $avecs = Avec::with(["animateur", "superviseur", "membres", "axe"])->where('projet_id', $projet_id)->where('superviseur_id', $request->user()->id)->get();
        } else {
            $avecs = Avec::with(["animateur", "superviseur", "membres", "axe"])->where('projet_id', $projet_id)->where('animateur_id', $request->user()->id)->get();
        }

        $breadcrumbs = [
            ['url'=>url('user_dashboard'), 'label'=>'Accueil'],
            ['url'=>url('list_avecs', $projet_id), 'label'=>'Avecs'],
        ];

        $projet = ProjetAvec::find($projet_id);
        return view('layouts.dashboard_user_layouts.list_des_avecs', ['current_user' => $request->user(),
            'projet' => $projet, "avecs" => $avecs, "breadcrumbs"=>$breadcrumbs]);
    }

    public function ajouter_un_animateur($projet_id, Request $request): View
    {
        $projet = ProjetAvec::find($projet_id);
        $superviseurs = User::where('projet_id', $projet_id)->where("fonction", "superviseur")->get();
        $breadcrumbs = [
            ['url'=>url('user_dashboard'), 'label'=>'Accueil'],
            ['url'=>url('list_personnel_projet', $projet_id), 'label'=>'Personnel'],
            ['url'=>url('ajouter_un_animateur', $projet_id), 'label'=>'Nouvel animateur'],
        ];
        return view('layouts.dashboard_user_layouts.ajouter_un_animateur', ['current_user' => $request->user(),
            'projet' => $projet, "superviseurs" => $superviseurs, "breadcrumbs"=>$breadcrumbs]);
    }

    public function save_animateur($projet_id, Request $request)
    {
        $request->validate([
            'photo' => ['max:5000'],
            'nom' => ['required', 'string', 'max:255'],
            'sexe' => ['required'],
            'adresse' => ['required'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'telephone' => ['required', 'max:10', Rule::unique(User::class)],
            'password' => ['required'],
            'fonction' => ['required'],
            'superviseur_id' => ['required'],
        ]);

        $imagePath = "";
        if ($request->hasFile('photo')) {
            /** @var UploadedFile $photo */
            $image = $request->photo;
            $imagePath = $image->store('medias/profiles', 'public');
        }

        $animateur = User::create([
            'photo' => $imagePath,
            'nom' => $request->get("nom"),
            'sexe' => $request->get("sexe"),
            'adresse' => $request->get("adresse"),
            'telephone' => $request->get("telephone"),
            'email' => $request->get("email"),
            'password' => Hash::make($request->get("password")),
            'droits' => "utilisateur",
            "fonction" => $request->get("fonction"),
            "superviseur_id" => $request->get("superviseur_id"),
            "projet_id" => $projet_id,
        ]);

        return redirect()->back()->with('success', "l'animateur a été créé");
    }

    public function ajouter_une_avec($projet_id, Request $request): View
    {
        $projet = ProjetAvec::find($projet_id);
        $current_user = $request->user();
        if ($current_user->fonction === "superviseur") {
            $animateurs = User::where('projet_id', $projet_id)->where("fonction", "animateur")->where("superviseur_id", $current_user->id)->get();
        }elseif ($current_user->fonction === "chef de projet") {
            $animateurs = [];
        }else {
            $animateurs = $current_user;
        }
        $axes = AxesProjet::where('projet_id', $projet_id)->get();

        $breadcrumbs = [
            ['url'=>url('user_dashboard'), 'label'=>'Accueil'],
            ['url'=>url('list_avecs', $projet_id), 'label'=>'Avecs'],
            ['url'=>url('ajouter_une_avec', $projet_id), 'label'=>'Nouvel avec'],
        ];

        return view('layouts.dashboard_user_layouts.ajouter_une_avec', compact("projet",
        "current_user", "animateurs", "axes", "breadcrumbs"));
    }

    public function load_animateurs($superviseur_id)
    {
        $animateurs = User::where("superviseur_id", $superviseur_id)->select("id", "nom")->get();
        return response()->json(["animateurs"=>$animateurs]);
    }

    public function save_avec($projet_id, Request $request)
    {
        $request->validate([
            "designation" => ['required'],
            "axe_id" => ['required'],
            "valeur_part" => ['required'],
            "maximum_part_achetable" => ['required'],
            "valeur_montant_solidarite" => ['required'],
            "animateur_id" => ['required'],
            "superviseur_id" => ['required'],
        ]);
        $projet = ProjetAvec::find($projet_id);

        $faker = Faker::create();
        do {
            $code = $projet->code_reference . '/AVEC/ID/' . $faker->numerify('######');
            $codeExiste = Avec::where('code', $code)->exists();
        } while ($codeExiste);

        $avec = Avec::create([
            "projet_id" => $projet_id,
            "code" => $code,
            "designation" => $request->get('designation'),
            "axe_id" => $request->get('axe_id'),
            "valeur_part" => $request->get('valeur_part'),
            "maximum_part_achetable" => $request->get('maximum_part_achetable'),
            "valeur_montant_solidarite" => $request->get('valeur_montant_solidarite'),
            "animateur_id" => $request->get('animateur_id'),
            "superviseur_id" => $request->get('superviseur_id'),
        ]);

        $caisse_epargne = CaisseEpargne::create([
            "projet_id" => $projet->id,
            "avec_id" => $avec->id,
        ]);

        $caisse_solidarite = CaisseSolidarite::create([
            "projet_id" => $projet->id,
            "avec_id" => $avec->id,
        ]);
        $caisse_amande = CaisseAmande::create([
            "projet_id" => $projet->id,
            "avec_id" => $avec->id,
        ]);

        return redirect()->back()->with('success', "l'avec a été créée");

    }


    public function edit_avec_configuration($avec_id, Request $request):RedirectResponse
    {
        $request->validate([
            "designation" => ['required'],
            "axe_id" => ['required'],
            "valeur_part" => ['required'],
            "maximum_part_achetable" => ['required'],
            "valeur_montant_solidarite" => ['required'],
            "animateur_id" => ['required'],
        ]);

        $avec = Avec::with(["animateur", "superviseur"])->find($avec_id);
        $avec->designation = $request->get("designation");
        $avec->axe_id = $request->get("axe_id");
        $avec->valeur_part = $request->get("valeur_part");
        $avec->maximum_part_achetable = $request->get("maximum_part_achetable");
        $avec->valeur_montant_solidarite = $request->get("valeur_montant_solidarite");
        $avec->animateur_id = $request->get("animateur_id");

        $avec->update();

        return redirect()->back()->with("success", "les informations ont été mises à jour");
    }

    public function afficher_avec($avec_id, Request $request): View
    {
        $avec = Avec::with(["animateur", "superviseur", "axe", "membres"])->find($avec_id);
        $projet = ProjetAvec::find($avec->projet_id);

        $animateurs = User::where('projet_id', $projet->id)->where('superviseur_id', $avec->superviseur_id)->get();
        $axes = AxesProjet::where('projet_id', $projet->id)->get();

        $comite = ComiteAvec::where('avec_id', $avec_id)->get();
        $membres = Membre::with(["fonction", "transactions"])->where('avec_id', $avec_id)->get();
        $regles_de_taxation_des_interets = ReglesDeTaxationDesInterets::where('avec_id', $avec_id)->get();
        $regles_de_taxation_des_amandes = ReglesDeTaxationDesAmandes::where('avec_id', $avec_id)->get();
        $cas_octroi_soutien = CasOctroiSoutien::where('avec_id', $avec_id)->get();

        $breadcrumbs = [
            ['url'=>url('user_dashboard'), 'label'=>'Accueil'],
            ['url'=>url('list_avecs', $projet->id), 'label'=>'Avecs'],
            ['url'=>url('afficher_avec', $avec_id), 'label'=>"Vue de l'avec"],
        ];

        return view('layouts.dashboard_user_layouts.afficher_avec',
            ['current_user' => $request->user(), 'avec' => $avec, 'projet' => $projet, 'comite' => $comite,
                'regles_de_taxation_des_interets' => $regles_de_taxation_des_interets,
                'regles_de_taxation_des_amandes' => $regles_de_taxation_des_amandes, 'membres' => $membres,
                "cas_octroi_soutien"=>$cas_octroi_soutien, "animateurs"=>$animateurs, "axes"=>$axes,
                "breadcrumbs"=>$breadcrumbs]);
    }

    public function ajouter_un_membre($avec_id, Request $request): View
    {
        $avec = Avec::with(["animateur", "superviseur"])->find($avec_id);
        $projet = ProjetAvec::find($avec->projet_id);

        $breadcrumbs = [
            ['url'=>url('list_avecs', $projet->id), 'label'=>'Avecs'],
            ['url'=>url('afficher_avec', $avec_id), 'label'=>"Vue"],
            ['url'=>url('ajouter_un_membre', $avec_id), 'label'=>"Ajouter un membre"],
        ];

        return view('layouts.dashboard_user_layouts.ajouter_un_membre', ['current_user' => $request->user(),
            "avec" => $avec, "projet"=>$projet, "breadcrumbs"=>$breadcrumbs]);
    }

    public function save_membre($avec_id, Request $request): RedirectResponse
    {
        $request->validate([
            "nom" => ['required'],
            "sexe" => ['required'],
            "adresse" => ['required'],
            "numeros_de_telephone" => ['required']
        ]);

        $imagePath = "";
        if ($request->hasFile('photo')) {
            /** @var UploadedFile $photo */
            $image = $request->photo;
            $imagePath = $image->store('medias/profiles', 'public');
        }

        $membre = Membre::create([
            "avec_id" => $avec_id,
            "photo" => $imagePath,
            "nom" => $request->get('nom'),
            "sexe" => $request->get('sexe'),
            "adresse" => $request->get('adresse'),
            "numeros_de_telephone" => $request->get('numeros_de_telephone'),
        ]);

        return redirect()->back()->with("success", "le membre a été ajouté");
    }

    public function ajouter_comite($avec_id, Request $request): RedirectResponse
    {
        $request->validate([
            "fonction" => ['required'],
            "membre_id" => ['required'],
        ]);

        $scan = ComiteAvec::where('avec_id', $avec_id)->where('membre_id', $request->get("membre_id"))->get();

        if (!$scan->count()) {
            if ($request->get('fonction') === "président(e)" || $request->get('fonction') === "secrétaire" || $request->get('fonction') === "trésorier(e)") {
                $scan = ComiteAvec::where("avec_id", $avec_id)->where("fonction", $request->get('fonction'))->get();
                if ($scan->count()) {
                    return redirect()->back()->with("error", "cette avec a déjà un ".$request->get('fonction'));
                } else {
                    ComiteAvec::create([
                        "avec_id" => $avec_id,
                        "membre_id" => $request->get("membre_id"),
                        "fonction" => $request->get("fonction"),
                    ]);

                    return redirect()->back()->with("success", "enregistré");
                }
            } else {
                ComiteAvec::create([
                    "avec_id" => $avec_id,
                    "membre_id" => $request->get("membre_id"),
                    "fonction" => $request->get("fonction"),
                ]);

                return redirect()->back()->with("success", "enregistré");
            }
        } else {
            return redirect()->back()->with("success", "ce membre a déjà une fonction");
        }
    }

    public function ajouter_regle_de_taxation_interet($avec_id, Request $request): RedirectResponse
    {
        $request->validate([
            "enonce_regle" => ['required'],
            "valeur_min" => ['required'],
            "valeur_max" => ['required'],
            "taux_interet" => ['required'],
        ]);

        ReglesDeTaxationDesInterets::create([
            "avec_id" => $avec_id,
            "enonce_regle" => $request->get("enonce_regle"),
            "valeur_min" => $request->get("valeur_min"),
            "valeur_max" => $request->get("valeur_max"),
            "taux_interet" => $request->get("taux_interet"),
        ]);

        return redirect()->back()->with("success", "la règle a été créée");
    }

    public function ajouter_regle_de_taxation_amande($avec_id, Request $request): RedirectResponse
    {
        $request->validate([
            "regle" => ['required'],
            "amande" => ['required'],
        ]);

        ReglesDeTaxationDesAmandes::create([
            "avec_id" => $avec_id,
            "regle" => $request->get("regle"),
            "amande" => $request->get("amande"),
        ]);

        return redirect()->back()->with("success", "la règle a été créé");
    }

    public function supprimer_regle_de_taxation_interet(Request $request): RedirectResponse
    {
        $regle_id = $request->get('regle_id');
        $regle = ReglesDeTaxationDesInterets::find($regle_id);
        $regle->delete();
        return redirect()->back()->with("success", "la règle a été supprimé");
    }

    public function supprimer_regle_de_taxation_amande(Request $request): RedirectResponse
    {
        $regle_id = $request->get('regle_id');
        $regle = ReglesDeTaxationDesAmandes::find($regle_id);
        $regle->delete();
        return redirect()->back()->with("success", "la règle a été supprimé");
    }

    public function supprimer_un_membre_avec(Request $request): RedirectResponse
    {
        $membre_id = $request->get('membre_id');
        $membre = Membre::find($membre_id);
        $transactions = Transactions::where("membre_id", $membre_id)->get();
        foreach ($transactions as $transaction) {
            $transaction->delete();
        }
        $membre->delete();

        return redirect()->back()->with("success", "le membre, ainsi que toutes les transactions effectuées par celui-ci
         ont été supprimé");
    }


    #MEMBRES
    public function afficher_un_membre($membre_id, Request $request): View
    {
        $membre = Membre::find($membre_id);
        $avec = Avec::with(["animateur", "superviseur", "membres"])->find($membre->avec_id);
        $projet = ProjetAvec::find($avec->projet_id);
        $fonction = ComiteAvec::where('membre_id', $membre_id)->first();

        $caisse_amande = CaisseAmande::where('avec_id', $avec->id)->first();
        $transactionsCount = Transactions::where('avec_id', $avec->id)->get()->count();

        $alert_remboursement = null;
        if ($membre->date_de_remboursement) {
            $now = Carbon::today();
            $date_remboursement_carbon = Carbon::parse($membre->date_de_remboursement);

            if ($now->greaterThan($date_remboursement_carbon)) {
                $alert_remboursement = $membre->nom. " a dépassé la date de remboursement de son crédit qui devait être le ".$membre->date_de_remboursement;
            }
        }

        $breadcrumbs = [
            ['url'=>url('list_avecs', $projet->id), 'label'=>'Avecs'],
            ['url'=>url('afficher_avec', $avec->id), 'label'=>"Vue"],
            ['url'=>url('afficher_un_membre', $membre_id), 'label'=>"Membre"],
        ];

        return view('layouts.dashboard_user_layouts.membres.afficher_membre',
            ['current_user' => $request->user(), 'avec' => $avec, 'projet' => $projet, 'membre' => $membre,
                "interets"=>$avec->interets, "caisse_amande"=>$caisse_amande, "membre_fonction"=>$fonction,
            "transactionsCount"=>$transactionsCount, "breadcrumbs"=>$breadcrumbs])->with("alert_remboursement", $alert_remboursement);
    }

    public function editer_un_membre($membre_id, Request $request): RedirectResponse
    {
        $request->validate([
            "nom" => ['required'],
            "statut" => ['required'],
            "sexe" => ['required'],
            "adresse" => ['required'],
            "numeros_de_telephone" => ['required']
        ]);

        $membre = Membre::find($membre_id);

        if ($request->get("statut") === "abandon") {
            return redirect()->route("gestionprojet.gestion_cas_abandon_membre", [$membre->id, $membre->avec_id]);
        }else {
            $imagePath = $membre->photo;
            if ($request->hasFile('photo')) {
                /** @var UploadedFile $photo */
                $image = $request->photo;
                if ($imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $image->store('medias/profiles', 'public');
            }

            $membre->photo = $imagePath;
            $membre->statut = $request->get('statut');
            $membre->nom = $request->get('nom');
            $membre->sexe = $request->get('sexe');
            $membre->adresse = $request->get('adresse');
            $membre->numeros_de_telephone = $request->get('numeros_de_telephone');

            $membre->update();

            return redirect()->back()->with("success", "le profile du membre a été mis à jour avec succès");
        }
    }


    public function gestion_cas_abandon_membre($membre_id, $avec_id, Request $request):View
    {
        $membre = Membre::find($membre_id);
        $avec = Avec::with(["animateur", "superviseur"])->find($avec_id);
        $projet = ProjetAvec::find($avec->projet_id);
        $current_user = $request->user();
        $cycle_de_gestion = CycleDeGestion::where("projet_id", $projet->id)->get();

        $breadcrumbs = [
            ['url'=>url('afficher_avec', $avec->id), 'label'=>"Vue"],
            ['url'=>url('afficher_un_membre', $membre_id), 'label'=>"Membre"],
            ['url'=>url('gestion_cas_abandon_membre', [$membre_id, $avec->id]), 'label'=>"Cas d'abandon"],
        ];

        return view("layouts.dashboard_user_layouts.membres.gestion_cas_abandon_membre", compact("avec",
        "membre", "current_user", 'projet', "cycle_de_gestion", "breadcrumbs"));
    }


    public function gestion_cas_abandon_membre_treatment($membre_id, $avec_id, Request $request):RedirectResponse
    {
        $request->validate([
            "mois_id"=>["required"],
            "semaine"=>["required"],
        ], [
            "mois_id.required"=>"ce champs est obligatoire",
            "semaine.required"=>"ce champs est obligatoire",
        ]);

        $membre = Membre::find($membre_id);
        $avec = Avec::with(["membres"])->find($avec_id);
        $projet = ProjetAvec::find($avec->projet_id);
        $caisseepargne = CaisseEpargne::where("avec_id", $avec_id)->first();
        if (is_null($caisseepargne)) {
            $caisse_epargne = CaisseEpargne::create([
                "projet_id" => $projet->id,
                "avec_id" => $avec->id,
            ]);
            $caisseepargne = $caisse_epargne;
        }
        $caisseamande = CaisseAmande::where("avec_id", $avec_id)->first();
        if (is_null($caisseamande)) {
            $caisse_amande = CaisseAmande::create([
                "projet_id" => $projet->id,
                "avec_id" => $avec->id,
            ]);
            $caisseamande = $caisse_amande;
        }
        $transactions = Transactions::where("membre_id", $membre_id)->get();

        $montantaremettre = 0;
        if ($request->has("calculpart")) {
            $dette = $membre->credit + $membre->interets_sur_credit;

            $total_des_parts_ht = 0;
            $montanttotalmembre = $membre->part_tot_achetees * $avec->valeur_part;
            foreach ($avec->membres as $membre_avec) {
                if ($membre_avec->statut != "abandon") {
                    $total_des_parts_ht += $membre_avec->part_tot_achetees;
                }
            }

            $gains_interets = 0;
            $gains_amandes = 0;
            $montantcaisseamande = $caisseamande ? $caisseamande->montant : 0;
            if ($request->has("calculinteret")) {
                if ($total_des_parts_ht != 0) {
                    $gains_interets = round((($avec->interets + $membre->interets_sur_credit)/$total_des_parts_ht) * $membre->part_tot_achetees, 1);
                    $gains_amandes = round(($montantcaisseamande/$total_des_parts_ht) * $membre->part_tot_achetees, 1);
                }
            }

            $montantaremettre = (($membre->part_tot_achetees * $avec->valeur_part) + $gains_interets + $gains_amandes) - $dette;

            if ($montantaremettre < 0) {
                return redirect()->route('gestionprojet.afficher_un_membre', $membre->id)->with("error", "le statut
                abandon ne peut être enregistré pour ce membre car ses parts ne suffisent pas à payer ses dettes");
            }else {
                $caisseepargne->montant += $membre->credit;
                if ($montantaremettre > $caisseepargne->montant) {
                    return redirect()->route('gestionprojet.afficher_un_membre', $membre->id)->with("error", "pas suffisamment
                    d'argent dans la caisse pour rembourser le membre, son statut ne sera donc pas modifié");
                }else {
                    $caisseepargne->montant -= $montanttotalmembre;
                    if ($membre->interets_sur_credit > 0) {
                        $caisseinteret = CaisseInteret::where("avec_id", $avec->id)->where("mois_id", $request->mois_id)->where("semaine", $request->semaine)->first();
                        if (!is_null($caisseinteret)) {
                            $caisseinteret->montant += $membre->interets_sur_credit;
                            $caisseinteret->save();
                        }else {
                            CaisseInteret::create([
                                "projet_id" => $projet->id,
                                "avec_id" => $avec->id,
                                "mois_id" => $request->mois_id,
                                "semaine" => $request->semaine,
                                "montant"=>$membre->interets_sur_credit,
                            ]);
                        }
                    }
                    $avec->interets += $membre->interets_sur_credit - $gains_interets;
                    $caisseamande->montant -= $gains_amandes;

                    $membre->gains = $montantaremettre;
                    $membre->statut = "abandon";
                    $fonction = ComiteAvec::where('membre_id', $membre_id)->first();
                    if (!is_null($fonction)) {
                        $fonction->delete();
                    }
                    $membre->date_de_remboursement = null;

                    $membre->save();
                    $avec->save();
                    $caisseamande->save();
                    $caisseepargne->save();

                    foreach ($transactions as $transaction) {
                        $transaction->statut_du_membre = 'abandon';
                        $transaction->save();
                    }
                }
                return redirect()->route("gestionprojet.afficher_un_membre", $membre->id)->with("success", "le statut du membre a été modifié");
            }
        }else {
            return redirect()->back()->with("error", "aucune action");
        }
    }

    public function transactions_hebdomadaire($membre_id, Request $request): View
    {
        $membre = Membre::find($membre_id);
        $avec = Avec::with(["animateur", "superviseur"])->find($membre->avec_id);
        $projet = ProjetAvec::find($avec->projet_id);
        $cycle_de_gestion = CycleDeGestion::where("projet_id", $projet->id)->get();
        $regles_amande = ReglesDeTaxationDesAmandes::where("avec_id", $avec->id)->get();
        $current_user = $request->user();

        //nombre des reunions actuel
        $n_reunions = Transactions::where('membre_id', $membre_id)->get()->count() + 1;
        $modulo_credit = $n_reunions % 4;

        $breadcrumbs = [
            ['url'=>url('afficher_avec', $avec->id), 'label'=>"Vue"],
            ['url'=>url('afficher_un_membre', $membre_id), 'label'=>"Membre"],
            ['url'=>url('transactions_hebdomadaire', $membre_id), 'label'=>"Nouvelle transaction"],
        ];

        return view('layouts.dashboard_user_layouts.membres.transactions', compact("membre", "avec",
        "projet", "cycle_de_gestion", "regles_amande", "current_user", "breadcrumbs", "n_reunions", "modulo_credit"));
    }


    public function save_transactions_hebdomadaire($membre_id, Request $request)
    {
        $request->validate([
            "mois_id" => ["required"],
            "semaine" => ["required"],
            "semaine_debut" => ["required"],
            "semaine_fin" => ["required"],
            "date_de_la_reunion" => ["required"],
            "num_reunion" => ["required"],
            "frequentation" => ["required"],
        ]);


        $membre = Membre::find($membre_id);
        $avec = Avec::with(["animateur", "superviseur"])->find($membre->avec_id);
        $projet = ProjetAvec::find($avec->projet_id);
        $caisse_epargne = CaisseEpargne::where('avec_id', $avec->id)->first();

        if ($request->get("frequentation") === "présent(e)") {
            $request->validate([
                "parts_achetees" => ["required"],
                "cotisation" => ["required"],
            ]);

            $scan = Transactions::where('projet_id', $projet->id)->where('avec_id', $avec->id)->where('membre_id', $membre_id)->where('mois_id', $request->get("mois_id"))->where('semaine', $request->get('semaine'))->first();
            if (is_null($scan)) {
                $caisse_epargne->montant = $caisse_epargne->montant + ($avec->valeur_part * $request->parts_achetees);

                $caisse_solidarite = CaisseSolidarite::where('avec_id', $avec->id)->first();
                if (!is_null($caisse_solidarite)) {
                    $caisse_solidarite->montant = $caisse_solidarite->montant + $request->cotisation;
                } else {
                    $caisse_solidarite = CaisseSolidarite::create([
                        "projet_id" => $projet->id,
                        "avec_id" => $avec->id,
                    ]);
                    $caisse_solidarite->montant = $caisse_solidarite->montant + $request->cotisation;
                }

                $caisse_amande = CaisseAmande::where('avec_id', $avec->id)->first();
                if (!is_null($caisse_amande)) {
                    $caisse_amande->montant = $caisse_amande->montant + $request->amande;
                } else {
                    $caisse_amande = CaisseAmande::create([
                        "projet_id" => $projet->id,
                        "avec_id" => $avec->id,
                    ]);
                    $caisse_amande->montant = $caisse_amande->montant + $request->amande;
                }

                $membre->part_tot_achetees += $request->parts_achetees;
                if ($request->has("credit")) {
                    if ($request->has("credit")) {
                        $request->validate([
                            "credit"=>['lte:'. $caisse_epargne->montant]
                        ], [
                            "credit.lte"=>"pas suffisament d'argent dans la caisse pour donner ce prêt"
                        ]);
                    }
                    if ($request->get('credit') != 0) {
                        $caisse_epargne->montant -= $request->credit;
                        $interet = ($request->taux_interet * $request->credit) / 100;
                        $membre->credit += $request->credit;
                        $membre->interets_sur_credit += $interet;
                        $membre->date_de_remboursement = $request->get('date_de_remboursement');
                    }
                }

                if ($request->has('remboursement')) {
                    if ($request->has("remboursement")) {
                        $request->validate([
                            "remboursement"=>['lte:'. $membre->credit + $membre->interets_sur_credit]
                        ], [
                            "remboursement.lte"=>"le montant remboursé est supérieur à l'emprunt du membre"
                        ]);
                    }
                    $interet_genere = 0;
                    if ($request->get('remboursement') != 0) {
                        $caisse_interet = CaisseInteret::where("avec_id", $avec->id)->where("mois_id", $request->mois_id)->where("semaine", $request->semaine)->first();
                        if (is_null($caisse_interet)) {
                            $caisse_interet = CaisseInteret::create([
                                "projet_id" => $projet->id,
                                "avec_id" => $avec->id,
                                "mois_id" => $request->mois_id,
                                "semaine" => $request->semaine,
                            ]);
                        }
                        if ($membre->interets_sur_credit >= $request->remboursement) {
                            $membre->interets_sur_credit -= $request->remboursement;
                            $interet = $request->remboursement;
                            $caisse_interet->montant = $caisse_interet->montant + $interet;
                            $interet_genere = $request->remboursement;
                        } else {
                            #étant donné que le remboursement d'une dette commence par le paiement des intérêts pour après payer le montant emprunté,
                            #cette clause permet de comparer si le montant remboursé est supérieur à la valeur des intérêts alors retrancher le montant restant sur
                            #le champs crédit du membre après avoir rétranché la première partie sur le champs intérêts_sur_credit du membre.

                            $difference = $request->remboursement - $membre->interets_sur_credit;
                            $caisse_interet->montant = $caisse_interet->montant + $membre->interets_sur_credit;
                            $membre->interets_sur_credit = 0;
                            $membre->credit -= $difference;
                            if ($membre->credit == 0) {
                                $membre->date_de_remboursement = null;
                            }
                            $caisse_epargne->montant += $difference;
                            $interet_genere = $membre->interets_sur_credit;
                        }
                    }
                }
                $transaction = Transactions::create([
                    "projet_id" => $projet->id,
                    "avec_id" => $avec->id,
                    "membre_id" => $membre->id,
                    "statut_du_membre"=>$membre->statut,
                    "mois_id" => $request->get('mois_id'),
                    "semaine" => $request->get('semaine'),
                    "semaine_debut" => $request->get('semaine_debut'),
                    "semaine_fin" => $request->get('semaine_fin'),
                    "date_de_la_reunion" => $request->get('date_de_la_reunion'),
                    "num_reunion" => $request->get('num_reunion'),
                    "frequentation" => $request->get('frequentation'),
                    "parts_achetees" => $request->get('parts_achetees', 0),
                    "cotisation" => $request->get('cotisation', 0),
                    "amande" => $request->get('amande', 0),
                    "credit" => $request->get('credit', 0),
                    "taux_interet" => $request->get('taux_interet', 0),
                    "date_de_remboursement"=>$request->get('date_de_remboursement'),
                    "credit_rembourse" => $request->get('remboursement', 0),
                    "interet_genere" => $interet_genere,
                ]);

                $membre->update();
                if (isset($caisse_interet)){
                    $caisse_interet->update();
                }
                if (isset($caisse_solidarite)){
                    $caisse_solidarite->update();
                }
                if (isset($caisse_amande)){
                    $caisse_amande->update();
                }
                if (isset($caisse_epargne)){
                    $caisse_epargne->update();
                }

                return redirect()->route("gestionprojet.afficher_un_membre", $membre->id)->with("success", "la transaction a été effectué");
            } else {
                return redirect()->back()->with("error", "cette transaction a déjà été effectué");
            }
        }else {
            $scan = Transactions::where('projet_id', $projet->id)->where('avec_id', $avec->id)->where('membre_id', $membre_id)->where('mois_id', $request->get("mois_id"))->where('semaine', $request->get('semaine'))->first();
            if (is_null($scan)) {
                $transaction = Transactions::create([
                    "projet_id" => $projet->id,
                    "avec_id" => $avec->id,
                    "membre_id" => $membre->id,
                    "mois_id" => $request->get('mois_id'),
                    "semaine" => $request->get('semaine'),
                    "semaine_debut" => $request->get('semaine_debut'),
                    "semaine_fin" => $request->get('semaine_fin'),
                    "date_de_la_reunion" => $request->get('date_de_la_reunion'),
                    "num_reunion" => $request->get('num_reunion'),
                    "frequentation" => $request->get('frequentation'),
                    "parts_achetees" => $request->get('parts_achetees', 0),
                    "cotisation" => $request->get('cotisation', 0),
                    "amande" => $request->get('amande', 0),
                    "credit" => $request->get('credit', 0),
                    "credit_rembourse" => $request->get('remboursement', 0),
                    "statut_du_membre" => "actif",
                ]);
            }
            return redirect()->back()->with("success", "la transaction a été enregistré");
        }
    }

    public function calcul_taux_interet($pret, $avec_id)
    {
        $regle = ReglesDeTaxationDesInterets::where("avec_id", $avec_id)->where("valeur_min", "<=", $pret)->where("valeur_max", ">=", $pret)->first();
        if ($regle) {
            $taux = $regle->taux_interet;
        } else {
            $taux = 1;
        }
        return response()->json(["taux" => $taux]);
    }

    public function load_semaines($mois_id, $membre_id, $avec_id)
    {
        $check_semaine1 = Transactions::where("avec_id", $avec_id)->where("membre_id", $membre_id)->where("mois_id", $mois_id)->where("semaine", "semaine 1")->first();
        $check_semaine2 = Transactions::where("avec_id", $avec_id)->where("membre_id", $membre_id)->where("mois_id", $mois_id)->where("semaine", "semaine 2")->first();
        $check_semaine3 = Transactions::where("avec_id", $avec_id)->where("membre_id", $membre_id)->where("mois_id", $mois_id)->where("semaine", "semaine 3")->first();
        $check_semaine4 = Transactions::where("avec_id", $avec_id)->where("membre_id", $membre_id)->where("mois_id", $mois_id)->where("semaine", "semaine 4")->first();
        $check_semaine5 = Transactions::where("avec_id", $avec_id)->where("membre_id", $membre_id)->where("mois_id", $mois_id)->where("semaine", "semaine 5")->first();
        $week_list = [$check_semaine1, $check_semaine2, $check_semaine3, $check_semaine4, $check_semaine5];

        $semaine = "";
        for ($i=0; $i<5; $i++) {
            if (is_null($week_list[$i])) {
                $semaine = "semaine ".$i+1;
                break;
            }
        }
        return response()->json(["semaine" => $semaine]);
    }

    public function supprimer_transaction(Request $request): RedirectResponse
    {
        $transaction_id = $request->get('transaction_id');
        $transaction = Transactions::find($transaction_id);
        $membre = Membre::find($transaction->membre_id);
        $avec = Avec::with(["animateur", "superviseur"])->find($transaction->avec_id);
        $caisse_epargne = CaisseEpargne::where("avec_id", $avec->id)->first();
        $caisse_amande = CaisseAmande::where("avec_id", $avec->id)->first();
        $caisse_solidarite = CaisseSolidarite::where("avec_id", $avec->id)->first();

        $membre->part_tot_achetees -= $transaction->parts_achetees;
        $caisse_amande->montant -= $transaction->amande;
        $caisse_solidarite->montant -= $transaction->cotisation;


        if ($transaction->credit) {
            $interet = ($transaction->credit * $transaction->taux_interet)/100;
            $membre->interets_sur_credit -= $interet;
            $membre->credit -= $transaction->credit;

            if ($membre->credit == 0) {
                $membre->date_de_remboursement = null;
            }

            $caisse_epargne->montant += $transaction->credit;
        }

        $membre->save();
        $caisse_amande->save();
        $caisse_solidarite->save();
        $caisse_epargne->save();

        $transaction->delete();

        return redirect()->back()->with("success", "la transaction a été supprimé");
    }

    public function supprimer_fonction_membre($membre_id)
    {
        $fonction = ComiteAvec::where('membre_id', $membre_id)->first();
        $fonction->delete();

        return redirect()->back()->with("success", "la fonction de ce membre lui a été rétiré");
    }

    public function ajouter_cas_octroi_soutien($avec_id, Request $request): RedirectResponse
    {
        $request->validate([
            "cas" => ['required'],
        ]);

        CasOctroiSoutien::create([
            "avec_id" => $avec_id,
            "cas" => $request->get("cas"),
        ]);

        return redirect()->back()->with("success", "le cas a été créé");
    }

    public function supprimer_cas_octroi_soutien(Request $request): RedirectResponse
    {
        $cas_id = $request->get('cas_id');
        $cas = CasOctroiSoutien::find($cas_id);
        $cas->delete();
        return redirect()->back()->with("success", "le cas a été supprimé");
    }

    public function assister_un_membre($avec_id, $projet_id, Request $request):View
    {
        $avec = Avec::with(["animateur", "superviseur"])->find($avec_id);
        $projet = ProjetAvec::find($projet_id);
        $cas = CasOctroiSoutien::where("avec_id", $avec_id)->get();
        $membres = Membre::where("avec_id", $avec_id)->where("statut", "actif")->get();
        $current_user = $request->user();
        $caissesolidarite = CaisseSolidarite::where("avec_id", $avec_id)->first();
        $montanttotal = $caissesolidarite ? $caissesolidarite->montant : 0;

        $breadcrumbs = [
            ['url'=>url('afficher_avec', $avec->id), 'label'=>"Vue"],
            ['url'=>url('releve_transactions_caisse_solidarite', [$avec_id, $projet_id]), 'label'=>"Rélevé"],
            ['url'=>url('assister_un_membre', [$avec_id, $projet_id]), 'label'=>"Assister un membre"],
        ];

        return view('layouts.dashboard_user_layouts.donner_une_assistance', compact("avec", "projet",
            "membres", "current_user", "cas", "montanttotal", "breadcrumbs"));
    }

    public function save_transaction_assistance($avec_id, $projet_id, Request $request):RedirectResponse
    {
        $caissesolidarite = CaisseSolidarite::where("avec_id", $avec_id)->first();
        $montantcaissesolidarite = $caissesolidarite ? $caissesolidarite->montant : 0;
        $request->validate([
            "cas"=>["required"],
            "montant"=>["required", 'lte:'.$montantcaissesolidarite],
            "beneficiaire"=>["required"],
        ], [
            "montant.lte"=>"le montant doit être inférieur ou égal au montant total dans la caisse",
            "cas.required"=>"ce champs est obligatoire",
            "beneficiaire.required"=>"ce champs est obligatoire",
            "montant.required"=>"ce champs est obligatoire",
        ]);

        SoutienCaisseSolidarite::create([
            "avec_id"=>$avec_id,
            "beneficiaire"=>$request->get('beneficiaire'),
            "cas"=>$request->get("cas"),
            "montant"=>$request->get("montant"),
        ]);

        $caissesolidarite->montant -= $request->get("montant");
        $caissesolidarite->save();

        return redirect()->back()->with("success", "la transaction a été effectué");
    }

    public function supprimer_transaction_caisse_solidarite($avec_id, Request $request):RedirectResponse
    {
        $transaction_id = $request->get('transaction_id');
        $transaction = SoutienCaisseSolidarite::find($transaction_id);
        $caissesolidarite = CaisseSolidarite::where("avec_id", $avec_id)->first();
        $caissesolidarite->montant += $transaction->montant;
        $caissesolidarite->save();
        $transaction->delete();

        return redirect()->back()->with("success", "la transaction a été supprimé");
    }

    public function edit_transaction($transaction_id, Request $request):View {
        $transaction = Transactions::with('membre', 'cycle_de_gestion', 'avec', 'projet')->find($transaction_id);
        $current_user = $request->user();
        $cycle_de_gestion = CycleDeGestion::where('projet_id', $transaction->projet_id)->get();
        $regles_amande = ReglesDeTaxationDesAmandes::where("avec_id", $transaction->avec_id)->get();

        //nombre des reunions actuel
        $n_reunions = Transactions::where('membre_id', $transaction->membre_id)->get()->count();
        $modulo_credit = $n_reunions % 4;

        $breadcrumbs = [
            ['url'=>url('afficher_avec', $transaction->avec->id), 'label'=>"Vue"],
            ['url'=>url('afficher_un_membre', $transaction->membre->id), 'label'=>"Membre"],
            ['url'=>url('transactions_hebdomadaire', $transaction->membre->id), 'label'=>"Nouvelle transaction"],
        ];

        return view("layouts.dashboard_user_layouts.membres.edit_transactions", compact("transaction",
            "current_user", "cycle_de_gestion", "regles_amande", "breadcrumbs", "modulo_credit"));
    }

    public function save_edition_transaction_membre($transaction_id, Request $request):RedirectResponse
    {
        $transaction = Transactions::with("avec", "projet", "membre")->find($transaction_id);
        $membre_id = $transaction->membre->id;
        $avec = $transaction->avec;
        $projet = $transaction->projet;
        $request->validate([
            "mois_id"=>["required"],
            "semaine_debut"=>["required"],
            "semaine_fin"=>["required"],
            "date_de_la_reunion"=>["required"],
            "frequentation"=>["required"],
            "parts_achetees"=>["required", "min:1", 'numeric', 'lte:'.$transaction->avec->maximum_part_achetable],
            "cotisation"=>["required", 'numeric', function ($attribute, $value, $fail) use ($avec) {
                if ($value != $avec->valeur_montant_solidarite) {
                    $fail("le montant de cotisation doit être égal à ".$avec->valeur_montant_solidarite);
                }
            }],
        ], [
            "mois_id.required"=>"ce champs est obligatoire",
            "semaine_debut.required"=>"ce champs est obligatoire",
            "semaine_fin.required"=>"ce champs est obligatoire",
            "date_de_la_reunion.required"=>"ce champs est obligatoire",
            "frequentation.required"=>"ce champs est obligatoire",
            "parts_achetees.required"=>"ce champs est obligatoire",
            "parts_achetees.min"=>"ce champs doit avoir le minimum de 1 comme valeur",
            "parts_achetees.numeric"=>"seules les valeurs numériques sont acceptées",
            "parts_achetees.lte"=>"la valeur des parts achetées doit être inférieure ou égale à ".$avec->maximum_part_achetable,
            "cotisation.required"=>"ce champs est obligatoire",
            "cotisation.numeric"=>"seules les valeurs numériques sont acceptées",
        ]);

        $request->validate([
            'semaine' => ['required', function ($attribute, $value, $fail) use ($membre_id, $transaction_id, $request) {
                $exists = Transactions::where('membre_id', $membre_id)->where('mois_id',
                    $request->input('mois_id'))->where('semaine', $value)->where('id', '!=', $transaction_id)->exists();
                if ($exists) {
                    $fail('cette semaine existe déjà pour le mois sélectionné pour ce membre.');
                }
            }]
        ]);

        $caisse_epargne = CaisseEpargne::where('avec_id', $transaction->avec->id)->first();
        $caisse_amande = CaisseAmande::where('avec_id', $transaction->avec->id)->first();
        $caisse_solidarite = CaisseSolidarite::where('avec_id', $transaction->avec->id)->first();
        $caisse_interet = CaisseInteret::where("avec_id", $transaction->avec->id)->where("mois_id",
            $transaction->mois_id)->where("semaine", $transaction->semaine)->first();

        $membre = $transaction->membre;

        //suppression de la transaction pour le membre et les caisses avant d'appliquer les nouvelles valeurs editées par l'utilisateur
        $membre->part_tot_achetees = $membre->part_tot_achetees - $transaction->parts_achetees;
        $membre->credit = $membre->credit - $transaction->credit;
        $interets_sur_credit = ($transaction->credit * $transaction->taux_interet)/100;
        $membre->interets_sur_credit = $membre->interets_sur_credit - $interets_sur_credit;
        $membre->date_de_remboursement = null;
        $interet_genere = 0;


        $montant_achete = $transaction->parts_achetees * $transaction->avec->valeur_part;
        $caisse_epargne->montant = $caisse_epargne->montant - $montant_achete - ($transaction->credit_rembourse - $transaction->interet_genere) + $transaction->credit;
        $caisse_amande->montant = $caisse_amande->montant - $transaction->amande;
        $caisse_solidarite->montant = $caisse_solidarite->montant - $transaction->cotisation;

        if(!is_null($caisse_interet)) {
            $caisse_interet->montant = $caisse_interet->montant - $transaction->interet_genere;
        }

        if ($request->frequentation === "présent(e)") {
            //mise à jour des informations dans les tables connexes
            $membre->part_tot_achetees += $request->input('parts_achetees');
            $interet = ($request->input('taux_interet', 0) * $request->input('credit', 0)) / 100;

            $caisse_epargne->montant = $caisse_epargne->montant + ($request->input('parts_achetees') * $transaction->avec->valeur_part);
            $caisse_amande->montant = $caisse_amande->montant + $request->input('amande', 0);
            $caisse_solidarite->montant = $caisse_solidarite->montant + $request->input('cotisation', 0);

            if ($request->has("credit")) {
                $request->validate([
                    "credit"=>['lte:'. $caisse_epargne->montant]
                ]);
            }

            if ($request->has("credit")) {
                if ($request->get('credit') != 0) {
                    $caisse_epargne->montant = $caisse_epargne->montant - $request->credit;
                    $interet = ($request->taux_interet * $request->credit) / 100;

                    $membre->credit = $membre->credit + $request->credit;
                    $membre->interets_sur_credit = $membre->interets_sur_credit + $interet;
                    $membre->date_de_remboursement = $request->get('date_de_remboursement');
                }
            }

            if ($request->has('remboursement')) {
                if ($request->has("remboursement")) {
                    $request->validate([
                        "remboursement"=>['lte:'. $membre->credit + $membre->interets_sur_credit]
                    ], [
                        "remboursement.lte"=>"le montant remboursé est supérieur à l'emprunt du membre"
                    ]);
                }

                if ($request->get('remboursement') != 0) {
                    $caisse_interet = CaisseInteret::where("avec_id", $avec->id)->where("mois_id", $request->mois_id)->where("semaine", $request->semaine)->first();
                    if (is_null($caisse_interet)) {
                        $caisse_interet = CaisseInteret::create([
                            "projet_id" => $projet->id,
                            "avec_id" => $avec->id,
                            "mois_id" => $request->mois_id,
                            "semaine" => $request->semaine,
                        ]);
                    }
                    if ($membre->interets_sur_credit >= $request->remboursement) {
                        $membre->interets_sur_credit = $membre->interets_sur_credit + $request->remboursement;
                        $interet = $request->remboursement;
                        $caisse_interet->montant = $caisse_interet->montant + $interet;
                        $interet_genere = $request->remboursement;
                    } else {
                        #étant donné que le remboursement d'une dette commence par le paiement des intérêts pour après payer le montant emprunté,
                        #cette clause permet de comparer si le montant remboursé est supérieur à la valeur des intérêts alors retrancher le montant restant sur
                        #le champs crédit du membre après avoir rétranché la première partie sur le champs intérêts_sur_credit du membre.

                        $difference = $request->remboursement - $membre->interets_sur_credit;
                        $caisse_interet->montant = $caisse_interet->montant + $membre->interets_sur_credit;
                        $membre->interets_sur_credit = 0;
                        $membre->credit = $membre->credit + $difference;
                        if ($membre->credit == 0) {
                            $membre->date_de_remboursement = null;
                        }
                        $caisse_epargne->montant = $caisse_epargne->montant + $difference;
                        $interet_genere = $membre->interets_sur_credit;
                    }
                }
            }
            $transaction->parts_achetees = $request->input('parts_achetees');
            $transaction->cotisation = $request->input('cotisation');
            $transaction->amande = $request->input('amande');
            $transaction->mois_id = $request->input('mois_id');
            $transaction->semaine = $request->input('semaine');
            $transaction->semaine_debut = $request->input('semaine_debut');
            $transaction->semaine_fin = $request->input('semaine_fin');
            $transaction->date_de_la_reunion = $request->input('date_de_la_reunion');
            $transaction->frequentation = $request->input('frequentation');
            $transaction->credit_rembourse = $request->input('remboursement');
            $transaction->interet_genere = $interet_genere;
            $transaction->credit = $request->input('credit');
            $transaction->taux_interet = $request->input('taux_interet');
            $transaction->date_de_remboursement = $request->get('date_de_remboursement');
        }else {
            $transaction->parts_achetees = 0;
            $transaction->cotisation = 0;
            $transaction->amande = 0;
            $transaction->mois_id = $request->input('mois_id');
            $transaction->semaine = $request->input('semaine');
            $transaction->semaine_debut = $request->input('semaine_debut');
            $transaction->semaine_fin = $request->input('semaine_fin');
            $transaction->date_de_la_reunion = $request->input('date_de_la_reunion');
            $transaction->frequentation = $request->input('frequentation');
            $transaction->credit_rembourse = 0;
            $transaction->interet_genere = 0;
            $transaction->credit = 0;
            $transaction->taux_interet = 0;
            $transaction->date_de_remboursement = null;
        }

        $membre->update();
        $caisse_interet ? $caisse_interet->update() : 0;
        $caisse_epargne->update();
        $caisse_amande->update();
        $caisse_solidarite->update();
        $transaction->update();

        return redirect()->route('rapports.rapport_transactions_membre',
            [$membre_id, $transaction->projet->id, $transaction->avec->id])->with('success', "la transaction a été modifié");
    }
}
