<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Avec;
use App\Models\AxesProjet;
use App\Models\CaisseAmande;
use App\Models\CasOctroiSoutien;
use App\Models\ComiteAvec;
use App\Models\CycleDeGestion;
use App\Models\Membre;
use App\Models\ProjetAvec;
use App\Models\ReglesDeTaxationDesAmandes;
use App\Models\ReglesDeTaxationDesInterets;
use App\Models\Transactions;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Http\Requests\AddProjetRequest;
use Illuminate\Validation\Rules;

class DashboardController extends Controller
{
    public function profiles():View
    {
        $current_user = Auth::user();
        $users = User::with(["projet"])->get();
        $projets = ProjetAvec::all();
        $projet_count = $projets->count();

        $breadcrumbs = [
            ['url'=>url('dashboard_admin'), 'label'=>'Accueil'],
            ['url'=>url('manage_user'), 'label'=>'Utilisateurs'],
        ];

        return view('layouts.dashboard_admin_layouts.user_manager.list_users', ['users' => $users,
            'current_user'=>$current_user, 'projet_count'=>$projet_count, "breadcrumbs"=>$breadcrumbs]);
    }

    public function admin_dashboard(Request $request):View
    {
        $projets = ProjetAvec::all();
        $projet_count = $projets->count();
        $utilisateurs = User::where("droits", "utilisateur")->get();
        $visiteurs = User::where("droits", "visiteur")->get();
        $administrateurs = User::where("droits", "administrateur")->get();
        $current_user = $request->user();

        $date = Carbon::today()->format('Y-m-d');
        $date2 = Carbon::yesterday()->format('Y-m-d');
        $date3 = Carbon::tomorrow()->format('Y-m-d');
        $taches = Agenda::where("user_id", $request->user()->id)->whereDate("date", $date)->orderBy("heure_debut", "asc")->get();
        $taches2 = Agenda::where("user_id", $request->user()->id)->whereDate("date", $date2)->orderBy("heure_debut", "asc")->get();
        $taches3 = Agenda::where("user_id", $request->user()->id)->whereDate("date", $date3)->orderBy("heure_debut", "asc")->get();

        $breadcrumbs = [
            ['url'=>url('dashboard_admin'), 'label'=>'Accueil'],
        ];

        return view('layouts.dashboard', compact("utilisateurs", "administrateurs", "visiteurs",
        "projet_count", "projets", "current_user", "breadcrumbs", "date", "date2", "date3", "taches", "taches2",
        "taches3"));
    }

    public function user_dashboard(Request $request):View
    {
        $projet = ProjetAvec::find($request->user()->projet_id);
        $projet_id = $projet->id;
        $cycledegestion = CycleDeGestion::where('projet_id', $projet->id)->get();
        $axes = AxesProjet::where("projet_id", $projet->id)->get();

        $date = Carbon::today()->format('Y-m-d');
        $date2 = Carbon::yesterday()->format('Y-m-d');
        $date3 = Carbon::tomorrow()->format('Y-m-d');
        $taches = Agenda::where("user_id", $request->user()->id)->whereDate("date", $date)->get();
        $taches2 = Agenda::where("user_id", $request->user()->id)->whereDate("date", $date2)->get();
        $taches3 = Agenda::where("user_id", $request->user()->id)->whereDate("date", $date3)->get();

        $current_user = $request->user();

        $months = CycleDeGestion::where("projet_id", $projet_id)->pluck('designation', 'id');

        $breadcrumbs = [
            ['url'=>url('user_dashboard'), 'label'=>'Accueil']
        ];
        if ($request->user()->fonction === "coordinateur du projet" || ($request->user()->fonction === "chef de projet") || ($request->user()->fonction === "assistant suivi et évaluation")) {
            $equipedegestion = User::where('projet_id', $projet->id)->get();
            $avecs = Avec::where("projet_id", $projet->id)->get();

            $total_abandons = Membre::whereHas('avec', function($query) use ($projet_id) {
                $query->where('projet_id',$projet_id)->where("statut", "abandon");
            })->get()->count();

            $total_investissement = Membre::whereHas('avec', function($query) use ($projet_id) {
                $query->where('projet_id', $projet_id)->where("statut",'!=', "abandon");
            })->sum("part_tot_achetees");

            $hommes = Membre::whereHas('avec', function($query) use ($projet_id) {
                $query->where('projet_id', $projet_id)->where("statut",'!=', "abandon")->where("sexe", "homme");
            })->get()->count();

            $femmes = Membre::whereHas('avec', function($query) use ($projet_id) {
                $query->where('projet_id', $projet_id)->where("sexe", "femme");
            })->get()->count();

            $total_interet = Avec::where("projet_id", $projet_id)->sum("interets");

            $monthlysavings = Transactions::selectRaw("mois_id, SUM(parts_achetees) as total_amount")->whereIn('mois_id',
                $months->keys())->where("projet_id", $projet_id)->where("statut_du_membre", "!=", "abandon")->groupBy('mois_id')->pluck("total_amount", "mois_id");

            //intérêts générés par le membre pour chaque mois
            $monthlygeneratedinterest = Transactions::selectRaw("mois_id, SUM(interet_genere) as total_interet")->whereIn('mois_id',
                $months->keys())->where("projet_id", $projet_id)->where("statut_du_membre", "!=", "abandon")->groupBy('mois_id')->pluck("total_interet", "mois_id");

            //récupération des noms de tous les mois du projet
            $labels = $months->values();

            //récupération des montants des intérêts générés pour chaque mois par le membre
            $valuesinterets = $months->map(fn($name, $id) => $monthlygeneratedinterest->get($id, 0))->values();

            //récupération des parts épargnées par le membre pour chaque mois
            $values = $months->map(fn($name, $id) => $monthlysavings->get($id, 0))->values();

        }elseif ($request->user()->fonction === "superviseur") {
            $equipedegestion = User::where('projet_id', $projet->id)->where('fonction', 'animateur')->where('superviseur_id', $request->user()->id)->get();
            $avecs = Avec::where("projet_id", $projet->id)->where("superviseur_id", $request->user()->id)->get();

            $total_abandons = Membre::whereHas('avec', function($query) use ($projet_id, $request) {
                $query->where('projet_id',$projet_id)->where("superviseur_id", $request->user()->id)->where("statut", "abandon");
            })->get()->count();

            $total_investissement = Membre::whereHas('avec', function($query) use ($projet_id, $request) {
                $query->where('projet_id', $projet_id)->where("statut",'!=', "abandon")->where("superviseur_id", $request->user()->id);
            })->sum("part_tot_achetees");

            $hommes = Membre::whereHas('avec', function($query) use ($projet_id, $request) {
                $query->where('projet_id', $projet_id)->where("statut",'!=', "abandon")->where("superviseur_id", $request->user()->id)->where("sexe", "homme");
            })->get()->count();

            $femmes = Membre::whereHas('avec', function($query) use ($projet_id, $request) {
                $query->where('projet_id', $projet_id)->where("superviseur_id", $request->user()->id)->where("sexe", "femme");
            })->get()->count();

            $total_interet = Avec::where("projet_id", $projet_id)->where("superviseur_id", $request->user()->id)->sum("interets");

            $monthlysavings = Transactions::selectRaw("mois_id, SUM(parts_achetees) as total_amount")->whereIn('mois_id',
                $months->keys())->where("projet_id", $projet_id)->where("statut_du_membre", "!=",
                "abandon")->whereHas('avec', function ($query) use ($request) {
                    $query->where('superviseur_id', $request->user()->id);
            })->groupBy('mois_id')->pluck("total_amount", "mois_id");

            //intérêts générés par le membre pour chaque mois
            $monthlygeneratedinterest = Transactions::selectRaw("mois_id, SUM(interet_genere) as total_interet")->whereIn('mois_id',
                $months->keys())->where("projet_id", $projet_id)->where("statut_du_membre", "!=",
                "abandon")->whereHas('avec', function ($query) use ($request) {
                $query->where('superviseur_id', $request->user()->id);
            })->groupBy('mois_id')->pluck("total_interet", "mois_id");

            //récupération des noms de tous les mois du projet
            $labels = $months->values();

            //récupération des montants des intérêts générés pour chaque mois par le membre
            $valuesinterets = $months->map(fn($name, $id) => $monthlygeneratedinterest->get($id, 0))->values();

            //récupération des parts épargnées par le membre pour chaque mois
            $values = $months->map(fn($name, $id) => $monthlysavings->get($id, 0))->values();

        }else {
            $equipedegestion = User::where('projet_id', $projet->id)->get();
            $avecs = Avec::where("projet_id", $projet->id)->where("animateur_id", $request->user()->id)->get();

            $total_abandons = Membre::whereHas('avec', function($query) use ($projet_id, $request) {
                $query->where('projet_id',$projet_id)->where("animateur_id", $request->user()->id)->where("statut", "abandon");
            })->get()->count();

            $total_investissement = Membre::whereHas('avec', function($query) use ($projet_id, $request) {
                $query->where('projet_id', $projet_id)->where("statut",'!=', "abandon")->where("animateur_id", $request->user()->id);
            })->sum("part_tot_achetees");

            $hommes = Membre::whereHas('avec', function($query) use ($projet_id, $request) {
                $query->where('projet_id', $projet_id)->where("statut",'!=', "abandon")->where("animateur_id", $request->user()->id)->where("sexe", "homme");
            })->get()->count();

            $femmes = Membre::whereHas('avec', function($query) use ($projet_id, $request) {
                $query->where('projet_id', $projet_id)->where("animateur_id", $request->user()->id)->where("sexe", "femme");
            })->get()->count();

            $total_interet = Avec::where("projet_id", $projet_id)->where("animateur_id", $request->user()->id)->sum("interets");

            $monthlysavings = Transactions::selectRaw("mois_id, SUM(parts_achetees) as total_amount")->whereIn('mois_id',
                $months->keys())->where("projet_id", $projet_id)->where("statut_du_membre", "!=",
                "abandon")->whereHas('avec', function ($query) use ($request) {
                $query->where('animateur_id', $request->user()->id);
            })->groupBy('mois_id')->pluck("total_amount", "mois_id");

            //intérêts générés par le membre pour chaque mois
            $monthlygeneratedinterest = Transactions::selectRaw("mois_id, SUM(interet_genere) as total_interet")->whereIn('mois_id',
                $months->keys())->where("projet_id", $projet_id)->where("statut_du_membre", "!=",
                "abandon")->whereHas('avec', function ($query) use ($request) {
                $query->where('animateur_id', $request->user()->id);
            })->groupBy('mois_id')->pluck("total_interet", "mois_id");

            //récupération des noms de tous les mois du projet
            $labels = $months->values();

            //récupération des montants des intérêts générés pour chaque mois par le membre
            $valuesinterets = $months->map(fn($name, $id) => $monthlygeneratedinterest->get($id, 0))->values();

            //récupération des parts épargnées par le membre pour chaque mois
            $values = $months->map(fn($name, $id) => $monthlysavings->get($id, 0))->values();
        }

        return view('layouts.dashboard_utilisateur', compact("projet", "equipedegestion", "cycledegestion",
        "axes", "avecs", "taches", "taches2", "taches3", "date", "date2", "date3", "current_user", "values", "valuesinterets", "labels",
        "total_interet", "total_abandons", "total_investissement", "hommes", "femmes", "breadcrumbs"));
    }

    public function guest_dashboard(Request $request):View
    {
        $current_user = $request->user();
        $projet_id = $current_user->projet_id;

        $months = CycleDeGestion::where("projet_id", $projet_id)->pluck('designation', 'id');
        $monthlysavings = Transactions::selectRaw("mois_id, SUM(parts_achetees) as total_amount")->whereIn('mois_id',
            $months->keys())->where("projet_id", $projet_id)->where("statut_du_membre", "!=", "abandon")->groupBy('mois_id')->pluck("total_amount", "mois_id");

        //intérêts générés par le membre pour chaque mois
        $monthlygeneratedinterest = Transactions::selectRaw("mois_id, SUM(interet_genere) as total_interet")->whereIn('mois_id',
            $months->keys())->where("projet_id", $projet_id)->where("statut_du_membre", "!=", "abandon")->groupBy('mois_id')->pluck("total_interet", "mois_id");

        $total_abandons = Membre::whereHas('avec', function($query) use ($projet_id) {
            $query->where('projet_id',$projet_id)->where("statut", "abandon");
        })->get()->count();

        $total_investissement = Membre::whereHas('avec', function($query) use ($projet_id) {
            $query->where('projet_id', $projet_id)->where("statut",'!=', "abandon");
        })->sum("part_tot_achetees");

        $hommes = Membre::whereHas('avec', function($query) use ($projet_id) {
            $query->where('projet_id', $projet_id)->where("statut",'!=', "abandon")->where("sexe", "homme");
        })->get()->count();

        $femmes = Membre::whereHas('avec', function($query) use ($projet_id) {
            $query->where('projet_id', $projet_id)->where("sexe", "femme");
        })->get()->count();

        $total_interet = Avec::where("projet_id", $projet_id)->sum("interets");

        //récupération des noms de tous les mois du projet
        $labels = $months->values();

        //récupération des montants des intérêts générés pour chaque mois par le membre
        $valuesinterets = $months->map(fn($name, $id) => $monthlygeneratedinterest->get($id, 0))->values();

        //récupération des parts épargnées par le membre pour chaque mois
        $values = $months->map(fn($name, $id) => $monthlysavings->get($id, 0))->values();

        $date = Carbon::today()->format('Y-m-d');
        $date2 = Carbon::yesterday()->format('Y-m-d');
        $date3 = Carbon::tomorrow()->format('Y-m-d');
        $taches = Agenda::where("user_id", $request->user()->id)->whereDate("date", $date)->get();
        $taches2 = Agenda::where("user_id", $request->user()->id)->whereDate("date", $date2)->get();
        $taches3 = Agenda::where("user_id", $request->user()->id)->whereDate("date", $date3)->get();

        $projet = ProjetAvec::find($request->user()->projet_id);

        $breadcrumbs = [
            ['url'=>url('guest_dashboard'), 'label'=>'Accueil'],
        ];

        return view('layouts.dashboard_guest', compact("date", "date2", "date3", "values", "valuesinterets",
        "taches", "taches2", "taches3", "projet", "current_user", "labels", "monthlygeneratedinterest", "monthlysavings",
        "total_interet", "total_investissement", "breadcrumbs", "total_abandons", "hommes", "femmes"));
    }

    public function list_projets(Request $request):View
    {
        $projets = ProjetAvec::all();
        $projet_count = $projets->count();
        $projets = ProjetAvec::all();

        $breadcrumbs = [
            ['url'=>url('dashboard_admin'), 'label'=>'Accueil'],
            ['url'=>url('list_projet'), 'label'=>'Liste des projets'],
        ];

        return view('layouts.dashboard_admin_layouts.list_projets', ['current_user'=>$request->user(),
            'projets'=>$projets, 'projet_count'=>$projet_count, "breadcrumbs"=>$breadcrumbs]);
    }

    public function ajouter_un_projet(Request $request):View
    {
        $projets = ProjetAvec::all();
        $projet_count = $projets->count();

        $breadcrumbs = [
            ['url'=>url('dashboard_admin'), 'label'=>'Accueil'],
            ['url'=>url('ajouter_un_projet'), 'label'=>'Ajouter un nouveau projet'],
        ];

        return view('layouts.dashboard_admin_layouts.ajouter_un_projet', ['current_user'=>$request->user(),
            'projet_count'=>$projet_count, "breadcrumbs"=>$breadcrumbs]);
    }

    public function enregistrer_projet(AddProjetRequest $request)
    {
        $projet = ProjetAvec::create([
            'code_reference'=>$request->validated('code_reference'),
            'context'=>$request->validated('context'),
            'cycle_de_gestion'=>$request->validated('cycle_de_gestion'),
            'date_de_debut'=>$request->validated('date_de_debut'),
            'date_de_fin'=>$request->validated('date_de_fin'),
        ]);

        for($i = 1; $i <= $request->get('cycle_de_gestion'); $i++) {
            CycleDeGestion::create([
                'projet_id'=>$projet->id,
                'mois'=>"mois ".$i,
            ]);
        }

        return redirect()->route('projet.list', ['current_user'=>$request->user()]);
    }

    public function configuration_projet($projet_id, Request $request):View
    {
        $projet = ProjetAvec::find($projet_id);
        $cycle_de_gestion = CycleDeGestion::where('projet_id', $projet->id)->get();
        $projets = ProjetAvec::all();
        $projet_count = $projets->count();
        $axes = AxesProjet::where('projet_id', $projet->id)->get();
        $superviseurs = User::where('projet_id', $projet->id)->where('fonction', 'superviseur')->get();

        $breadcrumbs = [
            ['url'=>url('dashboard_admin'), 'label'=>'Accueil'],
            ['url'=>url('list_projet'), 'label'=>'Projets'],
            ['url'=>url('configuration_projet', $projet_id), 'label'=>'Configuration'],
        ];

        return view('layouts.dashboard_admin_layouts.configuration_projet', ['current_user' => $request->user(),
            'projet' => $projet, 'cycle_de_gestion' => $cycle_de_gestion, "axes" => $axes, "projet_count" => $projet_count,
            "superviseurs" => $superviseurs, "breadcrumbs"=>$breadcrumbs]);
    }

    public function configuration_mois_cycle_de_gestion(Request $request)
    {
        $request->validate([
            'mois' => ['required'],
            'designation' => ['required', 'string'],
        ]);

        $id = intval($request->get('mois'));
        $mois = CycleDeGestion::find($id);
        $mois->designation = $request->get('designation');
        $mois->update();

        return redirect()->back()->with("success", "la configuration a été faite");
    }

    public function save_edition_projet($projet_id, Request $request)
    {
        $projet = ProjetAvec::find($projet_id);
        $request->validate([
            'code_reference' => ['required'],
            'cycle_de_gestion' => ['required', 'integer', 'min:'.$projet->cycle_de_gestion],
            'date_de_debut'=>['required'],
            'date_de_fin'=>['required'],
        ]);

        $cycle_de_gestion = $projet->cycle_de_gestion;
        $difference = "";
        if ($request->get('cycle_de_gestion') > $cycle_de_gestion) {
            $difference = $request->get('cycle_de_gestion') - $cycle_de_gestion;
        }

        for ($i=1; $i<=$difference; $i++) {
            CycleDeGestion::create([
                'projet_id'=>$projet->id,
                'mois'=>"mois ".$cycle_de_gestion+$i,
            ]);
        }

        $projet->code_reference = $request->get('code_reference');
        $projet->cycle_de_gestion = $request->get('cycle_de_gestion');
        $projet->date_de_debut = $request->get('date_de_debut');
        $projet->date_de_fin = $request->get('date_de_fin');
        $projet->update();

        return redirect()->back()->with("success", "la mise à jour a été effectué");
    }

    public function afficher_projet($projet_id, Request $request):View
    {
        $projet = ProjetAvec::find($projet_id);
        $equipedegestion = User::where('projet_id', $projet_id)->get();
        $cycledegestion = CycleDeGestion::where('projet_id', $projet_id)->get();
        $axes = AxesProjet::where("projet_id", $projet_id)->get();
        $avecs = Avec::where("projet_id", $projet_id)->get();
        $projets = ProjetAvec::all();
        $projet_count = $projets->count();
        $current_user = $request->user();

        $breadcrumbs = [
            ['url'=>url('dashboard_admin'), 'label'=>'Accueil'],
            ['url'=>url('list_projet'), 'label'=>'Projets'],
            ['url'=>url('afficher_projet', $projet_id), 'label'=>'Afficher'],
        ];

        $total_abandons = Membre::whereHas('avec', function($query) use ($projet_id) {
            $query->where('projet_id', $projet_id)->where("statut", "abandon");
        })->get()->count();

        $total_investissement = Membre::whereHas('avec', function($query) use ($projet_id) {
            $query->where('projet_id', $projet_id)->where("statut",'!=', "abandon");
        })->sum("part_tot_achetees");

        $hommes = Membre::whereHas('avec', function($query) use ($projet_id) {
            $query->where('projet_id', $projet_id)->where("statut",'!=', "abandon")->where("sexe", "homme");
        })->get()->count();

        $femmes = Membre::whereHas('avec', function($query) use ($projet_id) {
            $query->where('projet_id', $projet_id)->where("sexe", "femme");
        })->get()->count();

        $total_interet = Avec::where("projet_id", $projet_id)->sum("interets");

        $months = CycleDeGestion::where("projet_id", $projet_id)->pluck('designation', 'id');
        $monthlysavings = Transactions::selectRaw("mois_id, SUM(parts_achetees) as total_amount")->whereIn('mois_id',
            $months->keys())->where("projet_id", $projet_id)->where("statut_du_membre", "!=", "abandon")->groupBy('mois_id')->pluck("total_amount", "mois_id");

        //intérêts générés par le membre pour chaque mois
        $monthlygeneratedinterest = Transactions::selectRaw("mois_id, SUM(interet_genere) as total_interet")->whereIn('mois_id',
            $months->keys())->where("projet_id", $projet_id)->where("statut_du_membre", "!=", "abandon")->groupBy('mois_id')->pluck("total_interet", "mois_id");

        //récupération des noms de tous les mois du projet
        $labels = $months->values();

        //récupération des montants des intérêts générés pour chaque mois par le membre
        $valuesinterets = $months->map(fn($name, $id) => $monthlygeneratedinterest->get($id, 0))->values();

        //récupération des parts épargnées par le membre pour chaque mois
        $values = $months->map(fn($name, $id) => $monthlysavings->get($id, 0))->values();


        return view('layouts.dashboard_admin_layouts.afficher_projet', compact("projet",
        "equipedegestion", "cycledegestion", "axes", "avecs", "projet_count", "current_user",
        "total_abandons", "projets", "total_abandons", "total_investissement", "total_interet", "hommes",
        "femmes", "labels", "values", "valuesinterets", "breadcrumbs"));
    }

    public function traitement_projet($projet_id, Request $request)
    {
        $action = $request->get('action');
        $projet = ProjetAvec::find($projet_id);
        $message = "";
        if ($action === "donner_le_go") {
            $projet->statut = "en cours";
            $projet->update();
            $message = "le projet a été lancé";
            return redirect()->back()->with("success", $message);
        }elseif ($action === "mettre_en_attente") {
            $projet->statut = "en attente";
            $projet->update();
            $message = "le projet a été mis en attente";
            return redirect()->back()->with("success", $message);
        }elseif ($action === "terminer") {
            $projet->statut = "clôturé";
            $projet->update();
            $message = "le projet a été clôturé";
            return redirect()->back()->with("success", $message);
        }elseif ($action === "supprimer") {
            $users = User::where('projet_id', $projet_id)->get();
            foreach ($users as $user) {
                $user->fonction = null;
                $user->save();
            }
            $projet->delete();
            $message = "le projet a été supprimé";
            return redirect()->route('projet.list')->with("success", $message);
        }else{
            return redirect()->back()->with("error", "aucune action");
        }
    }

    public function list_des_avecs($projet_id, Request $request):View
    {
        $avecs = Avec::with(["animateur", "superviseur", "membres", "axe"])->where('projet_id', $projet_id)->get();
        $projet = ProjetAvec::find($projet_id);
        $current_user = $request->user();
        $projet_count = ProjetAvec::all()->count();

        $breadcrumbs = [
            ['url'=>url('list_projet'), 'label'=>'Projets'],
            ['url'=>url('afficher_projet', $projet_id), 'label'=>'Afficher'],
            ['url'=>url('list_des_avecs', $projet_id), 'label'=>'Avecs'],
        ];

        return view('layouts.dashboard_admin_layouts.list_des_avecs',
        compact("avecs", "projet", "projet_count", "current_user", "breadcrumbs"));
    }

    public function supprimer_avec(Request $request):RedirectResponse
    {
        $avec_id = $request->get('avec_id');
        $avec = Avec::find($avec_id);
        $avec->delete();
        return redirect()->back()->with('success', "l'avec a été supprimé");
    }


    public function afficher_une_avec($avec_id, Request $request): View
    {
        $avec = Avec::with(["animateur", "superviseur", "axe", "membres"])->find($avec_id);
        $projet = ProjetAvec::find($avec->projet_id);

        $animateurs = User::where('projet_id', $projet->id)->get();
        $axes = AxesProjet::where('projet_id', $projet->id)->get();

        $comite = ComiteAvec::where('avec_id', $avec_id)->get();
        $membres = Membre::with(["fonction"])->where('avec_id', $avec_id)->get();
        $regles_de_taxation_des_interets = ReglesDeTaxationDesInterets::where('avec_id', $avec_id)->get();
        $regles_de_taxation_des_amandes = ReglesDeTaxationDesAmandes::where('avec_id', $avec_id)->get();
        $cas_octroi_soutien = CasOctroiSoutien::where('avec_id', $avec_id)->get();
        $current_user = $request->user();
        $projet_count = ProjetAvec::all()->count();

        $breadcrumbs = [
            ['url'=>url('afficher_projet', $projet->id), 'label'=>'Afficher'],
            ['url'=>url('list_des_avecs', $projet->id), 'label'=>'Avecs'],
            ['url'=>url('afficher_une_avec', $avec->id), 'label'=>'Afficher une avec'],
        ];

        return view("layouts.dashboard_admin_layouts.afficher_avec", compact("avec",
        "projet", "animateurs", "axes", "comite", "membres", "regles_de_taxation_des_interets",
        "regles_de_taxation_des_amandes", "cas_octroi_soutien", "current_user", "breadcrumbs", "projet_count"));
    }

    public function supprimer_un_membre(Request $request): RedirectResponse
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


    public function afficher_membre($membre_id, Request $request): View
    {
        $membre = Membre::find($membre_id);
        $avec = Avec::with(["animateur", "superviseur", "membres"])->find($membre->avec_id);
        $projet = ProjetAvec::find($avec->projet_id);
        $fonction = ComiteAvec::where('membre_id', $membre_id)->first();
        $interets = $avec->interets;
        $membre_fonction = $fonction;

        $caisse_amande = CaisseAmande::where('avec_id', $avec->id)->first();
        $transactionsCount = Transactions::where('avec_id', $avec->id)->get()->count();
        $current_user = $request->user();

        $projet_count = ProjetAvec::all()->count();

        $alert_remboursement = null;
        if ($membre->date_de_remboursement) {
            $now = Carbon::today();
            $date_remboursement_carbon = Carbon::parse($membre->date_de_remboursement);

            if ($now->greaterThan($date_remboursement_carbon)) {
                $alert_remboursement = $membre->nom. " a dépassé la date de remboursement de son crédit qui devait être le ".$membre->date_de_remboursement;
            }
        }

        $breadcrumbs = [
            ['url'=>url('list_des_avecs', $projet->id), 'label'=>'Avecs'],
            ['url'=>url('afficher_une_avec', $avec->id), 'label'=>'Afficher'],
            ['url'=>url('afficher_membre', $membre->id), 'label'=>'Membre'],
        ];

        return view('layouts.dashboard_admin_layouts.afficher_un_membre', compact("membre",
        "avec", "projet", "fonction", "interets", "membre_fonction", "caisse_amande", "transactionsCount",
        "current_user", "projet_count", "breadcrumbs"))->with("alert_remboursement", $alert_remboursement);
    }

    public function list_du_personnel_projet($projet_id, Request $request):View
    {   $projets = ProjetAvec::all();
        $projet_count = $projets->count();
        $personnel = User::where('projet_id', $projet_id)->get();
        $projet = ProjetAvec::find($projet_id);

        $breadcrumbs = [
            ['url'=>url('list_projet'), 'label'=>'Projets'],
            ['url'=>url('afficher_projet', $projet->id), 'label'=>'Afficher'],
            ['url'=>url('list_du_personnel_projet', $projet->id), 'label'=>'Personnel'],
        ];

        return view('layouts.dashboard_admin_layouts.list_personnel_assigne', ['current_user'=>$request->user(),
            'personnel'=>$personnel, 'projet'=>$projet, 'projet_count'=>$projet_count, "breadcrumbs"=>$breadcrumbs]);
    }

    public function ajouter_axe($projet_id, Request $request)
    {
        $axe = AxesProjet::create([
            "projet_id"=>$projet_id,
            "designation"=>$request->get('designation'),
        ]);
        return response()->json(["success"=>"ajouté"]);
    }

    #Edition du profile d'un utilisateur
    public function edit_profile_user($user_id, Request $request)
    {
        $user = User::find($user_id);
        $projets = ProjetAvec::all();
        $projet_count = $projets->count();

        return view("layouts.dashboard_admin_layouts.profile_user.edit_profile_user", ['current_user'=>$request->user(), 'user'=>$user, 'projets'=>$projets, "projet_count"=>$projet_count]);
    }


    public function loadsuperviseurs($projet_id) {
        $superviseurs = User::where('projet_id', $projet_id)->where("fonction", "superviseur")->get();
        return response()->json(["superviseurs"=>$superviseurs]);
    }

    /* module de mise à jour des informations d'un utilisateur */
    public function update_profile_user($user_id, Request $request)
    {
        $user = User::find($user_id);

        $request->validate([
            'droits'=>['required'],
        ]);

        if ($request->get('droits') === "administrateur") {
            $request->validate([
                'photo'=>['max:5000'],
                'nom' => ['required', 'string', 'max:255'],
                'sexe' => ['required'],
                'adresse' => ['required'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
                'telephone' => ['required', 'max:10', Rule::unique(User::class)->ignore($user->id)],
            ]);

            $photo_init = $user->photo;
            if ($request->hasFile('photo')){
                /** @var UploadedFile $photo */
                $image = $request->photo;
                $imagePath = $image->store('medias/profiles', 'public');
                if ($photo_init){
                    Storage::disk('public')->delete($photo_init);
                }
                $user->photo = $imagePath;
            }else {
                $user->photo = $photo_init;
            }

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->nom = $request->get('nom');
            $user->sexe = $request->get('sexe');
            $user->adresse = $request->get('adresse');
            $user->telephone = $request->get('telephone');
            $user->email = $request->get('email');
            $user->droits = $request->get('droits');
            $user->fonction = null;
            $user->superviseur_id = null;
            $user->projet_id = null;

            if ($request->has('statut')) {
                $user->statut = true;
            }else {
                $user->statut = false;
            }
            $user->update();

            return redirect()->back()->with('success', 'le profile a été mis à jour');


        }elseif ($request->get('droits') === 'utilisateur') {
            $request->validate([
                'photo'=>['max:5000'],
                'nom' => ['required', 'string', 'max:255'],
                'sexe' => ['required'],
                'adresse' => ['required'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
                'telephone' => ['required', 'max:10', Rule::unique(User::class)->ignore($user->id)],
                'fonction' => ['required'],
                'projet_id' => ['required'],
            ]);

            $data = request()->all();

            $autorisations = [];

            if (array_key_exists('autorisation', $data)) {
                $autorisations = array_filter($data['autorisation']);
                $user->autorisations = json_encode($autorisations);
            }else {
                $user->autorisations = json_encode($autorisations);
            }

            if ($request->get('fonction') === "animateur"){
                $request->validate([
                    'superviseur_id'=>['required'],
                ]);

                $photo_init = $user->photo;
                if ($request->hasFile('photo')){
                    /** @var UploadedFile $photo */
                    $image = $request->photo;
                    $imagePath = $image->store('medias/profiles', 'public');
                    if ($photo_init){
                        Storage::disk('public')->delete($photo_init);
                    }
                    $user->photo = $imagePath;
                }else {
                    $user->photo = $photo_init;
                }

                if ($user->isDirty('email')) {
                    $user->email_verified_at = null;
                }

                $user->nom = $request->get('nom');
                $user->sexe = $request->get('sexe');
                $user->adresse = $request->get('adresse');
                $user->telephone = $request->get('telephone');
                $user->email = $request->get('email');
                $user->fonction = $request->get('fonction');
                $user->projet_id = $request->get('projet_id');
                $user->droits = $request->get('droits');
                $user->superviseur_id = $request->get('superviseur_id');

                if ($request->has('statut')) {
                    $user->statut = true;
                }else {
                    $user->statut = false;
                }
                $user->update();

                return redirect()->back()->with('success', 'le profile a été mis à jour');

            }else {
                $scan = 0;

                if ($request->get('fonction') === "chef de projet" || $request->get('fonction') === "coordinateur du projet" || $request->get('fonction') === "assistant suivi et évaluation") {
                    $scan = User::where('projet_id', $request->get('projet_id'))->where('fonction',
                        $request->get('fonction'))->where('id', '!=', $user->id)->get()->count();
                }

                if ($scan) {
                    return redirect()->back()->with('error', "ce projet a déjà un ".$request->get('fonction'));
                }else {
                    $photo_init = $user->photo;
                    if ($request->hasFile('photo')){
                        /** @var UploadedFile $photo */
                        $image = $request->photo;
                        $imagePath = $image->store('medias/profiles', 'public');
                        if ($photo_init){
                            Storage::disk('public')->delete($photo_init);
                        }
                        $user->photo = $imagePath;
                    }else {
                        $user->photo = $photo_init;
                    }

                    if ($user->isDirty('email')) {
                        $user->email_verified_at = null;
                    }

                    $data = request()->all();

                    $autorisations = [];

                    if (array_key_exists('autorisation', $data)) {
                        $autorisations = array_filter($data['autorisation']);
                        $user->autorisations = json_encode($autorisations);
                    }else {
                        $user->autorisations = json_encode($autorisations);
                    }

                    $user->nom = $request->get('nom');
                    $user->sexe = $request->get('sexe');
                    $user->adresse = $request->get('adresse');
                    $user->telephone = $request->get('telephone');
                    $user->email = $request->get('email');
                    $user->fonction = $request->get('fonction');
                    $user->projet_id = $request->get('projet_id');
                    $user->droits = $request->get('droits');
                    $user->superviseur_id = null;

                    if ($request->has('statut')) {
                        $user->statut = true;
                    }else {
                        $user->statut = false;
                    }
                    $user->update();

                    return redirect()->back()->with('success', 'le profile a été mis à jour');
                }
            }
        }else {
            $request->validate([
                'photo'=>['max:5000'],
                'nom' => ['required', 'string', 'max:255'],
                'sexe' => ['required'],
                'adresse' => ['required'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
                'telephone' => ['required', 'max:10', Rule::unique(User::class)->ignore($user->id)],
                'projet_id' => ['required'],
            ]);

            $photo_init = $user->photo;
            if ($request->hasFile('photo')){
                /** @var UploadedFile $photo */
                $image = $request->photo;
                $imagePath = $image->store('medias/profiles', 'public');
                if ($photo_init){
                    Storage::disk('public')->delete($photo_init);
                }
                $user->photo = $imagePath;
            }else {
                $user->photo = $photo_init;
            }

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->nom = $request->get('nom');
            $user->sexe = $request->get('sexe');
            $user->adresse = $request->get('adresse');
            $user->telephone = $request->get('telephone');
            $user->email = $request->get('email');
            $user->fonction = null;
            $user->projet_id = $request->get('projet_id');
            $user->superviseur_id = null;

            if ($request->has('statut')) {
                $user->statut = true;
            }else {
                $user->statut = false;
            }
            $user->update();

            return redirect()->back()->with('success', 'le profile a été mis à jour');
        }
    }

    public function update_user_password($user_id, Request $request): RedirectResponse
    {
        $user = User::find($user_id);
        $validated = $request->validateWithBag('updatePassword', [
            'password' => ['required', Rules\Password::defaults()]],
            [
                'password.required'=>'Ce champs est obligatoire',
                'password.min'=>'Le mot de passe doit avoir au minimum 8 caractères',
                'password.regex'=>'Le mot de passe doit contenir au moins une lettre et un chiffre',
            ]);
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        return redirect()->back()->with('success', 'le mot de passe a été mis à jour');
    }

    public function destroy_user_account($user_id, Request $request): RedirectResponse
    {
        $user = User::find($user_id);
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ], [
            'password.required'=>"mot de passe administrateur requis",
            'password.current_password'=>"mot de passe administrateur incorrect"
        ]);

        $user->delete();
        return redirect()->route("user.list_users")->with("success", "le profile a été supprimé");
    }

    public function supprimer_axe(Request $request):RedirectResponse
    {
        $axe_id = $request->get("axe_id");
        $axe = AxesProjet::find($axe_id);
        $axe->delete();
        return redirect()->back()->with('success', "l'axe a été supprimé");
    }

    //Agenda
    public function agenda(Request $request):View
    {
        $current_user = $request->user();
        $agenda = Agenda::with(['user'])->where("user_id", $current_user->id)->get();
        $projet = ProjetAvec::find($current_user->projet_id);
        $projet_count = ProjetAvec::all()->count();

        $breadcrumbs = [
            ['url'=>url('user_dashboard'), 'label'=>'Accueil'],
            ['url'=>url('agenda'), 'label'=>'Agenda'],
        ];

        if ($current_user->droits === "administrateur") {
            return view("layouts.agendaadmin", compact("current_user", "breadcrumbs", "agenda", "projet", "projet_count"));
        }elseif ($current_user->droits === "utilisateur") {
            return view("layouts.agenda", compact("current_user", "breadcrumbs", "agenda", "projet"));
        }else {
            return view("layouts.agendaguest", compact("current_user", "breadcrumbs", "agenda", "projet"));
        }
    }

    public function add_task(Request $request):RedirectResponse
    {
        $request->validate([
            "tache"=>['required'],
            "date"=>['required'],
            "heure_debut"=>['required'],
            "heure_fin"=>['required'],
        ]);

        Agenda::create([
            "user_id"=>$request->user()->id,
            "tache"=>$request->get('tache'),
            "date"=>$request->get('date'),
            "heure_debut"=>$request->get('heure_debut'),
            "heure_fin"=>$request->get('heure_fin'),
        ]);

        return redirect()->back()->with("success", "la tâche a été ajouté");
    }

    public function change_status_task($tache_id, Request $request)
    {
        $tache = Agenda::find($tache_id);
        $tache->statut = $request->status;
        $tache->save();

        return response()->json("mise à jour effectué");
    }

    public function delete_task(Request $request):RedirectResponse
    {
        $tache_id = $request->get('tache_id');
        $tache = Agenda::find($tache_id);
        $tache->delete();

        return redirect()->back()->with("success", "la tâche a été supprimé");
    }
}
