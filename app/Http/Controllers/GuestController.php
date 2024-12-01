<?php

namespace App\Http\Controllers;

use App\Models\Avec;
use App\Models\AxesProjet;
use App\Models\CaisseAmande;
use App\Models\CaisseEpargne;
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
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class GuestController extends Controller
{
    public function lst_des_avecs($projet_id, Request $request):View
    {
        $avecs = Avec::with(["animateur", "superviseur", "membres", "axe"])->where('projet_id', $projet_id)->get();
        $projet = ProjetAvec::find($projet_id);
        $current_user = $request->user();

        $breadcrumbs = [
            ['url'=>url('guest_dashboard', $projet->id), 'label'=>'Accueil'],
            ['url'=>url('lst_des_avecs', $projet->id), 'label'=>'Avecs'],
        ];

        return view('layouts.dashboard_guest_layouts.list_des_avecs',
            compact("avecs", "projet", "current_user", "breadcrumbs"));
    }

    public function display_avec($avec_id, Request $request): View
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

        $breadcrumbs = [
            ['url'=>url('guest_dashboard', $projet->id), 'label'=>'Accueil'],
            ['url'=>url('lst_des_avecs', $projet->id), 'label'=>'Avecs'],
            ['url'=>url('display_avec', $avec->id), 'label'=>"Vue de l'avec"],
        ];

        return view("layouts.dashboard_guest_layouts.afficher_avec", compact("avec",
            "projet", "animateurs", "axes", "comite", "membres", "regles_de_taxation_des_interets",
            "regles_de_taxation_des_amandes", "cas_octroi_soutien", "current_user", "breadcrumbs"));
    }


    public function display_membre($membre_id, Request $request): View
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

        $alert_remboursement = null;
        if ($membre->date_de_remboursement) {
            $now = Carbon::today();
            $date_remboursement_carbon = Carbon::parse($membre->date_de_remboursement);

            if ($now->greaterThan($date_remboursement_carbon)) {
                $alert_remboursement = $membre->nom. " a dépassé la date de remboursement de son crédit qui devait être le ".$membre->date_de_remboursement;
            }
        }

        $breadcrumbs = [
            ['url'=>url('lst_des_avecs', $projet->id), 'label'=>'Avecs'],
            ['url'=>url('display_avec', $avec->id), 'label'=>'Vue'],
            ['url'=>url('display_membre', $membre->id), 'label'=>'Afficher un membre'],
        ];

        return view('layouts.dashboard_guest_layouts.afficher_un_membre', compact("membre",
            "avec", "projet", "fonction", "interets", "membre_fonction", "caisse_amande", "transactionsCount",
            "current_user", "breadcrumbs"))->with("alert_remboursement", $alert_remboursement);
    }

    public function calculateTrend(Collection $data) {
        $n = $data->count();
        if ($n >= 2) {
            $sumX = $data->keys()->sum(); // somme des indices de mois (1,2,3,...)
            $sumY = $data->sum();
            $sumXY = $data->map(function($y, $x) {
                return $x * $y;
            })->sum();
            $sumX2 = $data->keys()->map(function($x) {
                return $x * $x;
            })->sum();  // somme de (x²)

            //calcul de la pente (m) et de l'ordonné à l'origine (b)
            $m = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
            $b = ($sumY - $m * $sumX) / $n;

            $trend = $data->keys()->map(function($x) use ($m, $b){
                return $m * $x + $b;
            });

            $firstTrendValue = $trend->first();
            $lastTrendValue = $trend->last();

            $trendPercentageGlobal = $firstTrendValue != 0 ? (($lastTrendValue - $firstTrendValue) / abs($firstTrendValue)) * 100 : null;

            return [
                'slope'=>$m,
                'intercept'=>$b,
                'trend'=>$trend,
                'trendPercentage'=>$trendPercentageGlobal,
            ];
        }else {
            return [
                'slope'=>0,
                'intercept'=>0,
                'trend'=>[0],
                'trendPercentage'=>0,
            ];
        }
    }

    public function report_transactions_du_membre($membre_id, $projet_id, $avec_id, Request $request) {
        $transactions = Transactions::with(["cycle_de_gestion"])->where("membre_id", $membre_id)->get();
        $membre = Membre::find($membre_id);
        $projet = ProjetAvec::find($projet_id);
        $avec = Avec::with(["animateur", "superviseur"])->find($avec_id);

        $parts = $transactions->sum('parts_achetees');
        $credit = $membre->credit + $membre->interets_sur_credit;

        $breadcrumbs = [
            ['url'=>url('display_avec', $avec->id), 'label'=>'Vue'],
            ['url'=>url('display_membre', $membre->id), 'label'=>'Membre'],
            ['url'=>url('report_transactions_du_membre', [$membre->id, $avec->id, $projet->id]), 'label'=>'Transactions'],
        ];

        return view('layouts.dashboard_guest_layouts.reports.rapport_transactions_membre', ['current_user' => $request->user(),
            'transactions' => $transactions, "breadcrumbs"=>$breadcrumbs, "projet"=>$projet, "avec"=>$avec, "membre"=>$membre, "parts"=>$parts, "credit"=>$credit,
        ]);
    }

    public function report_analytique_du_membre($membre_id, $projet_id, $avec_id, Request $request) {
        $months = CycleDeGestion::where("projet_id", $projet_id)->pluck('designation', 'id');
        $monthlysavings = Transactions::selectRaw("mois_id, SUM(parts_achetees) as total_amount")->whereIn('mois_id',
            $months->keys())->where("membre_id", $membre_id)->groupBy('mois_id')->pluck("total_amount", "mois_id");

        //intérêts générés par le membre pour chaque mois
        $monthlygeneratedinterest = Transactions::selectRaw("mois_id, SUM(interet_genere) as total_interet")->whereIn('mois_id',
            $months->keys())->where("membre_id", $membre_id)->groupBy('mois_id')->pluck("total_interet", "mois_id");

        //récupération des noms de tous les mois du projet
        $labels = $months->values();

        //récupération des montants des intérêts générés pour chaque mois par le membre
        $valuesinterets = $months->map(fn($name, $id) => $monthlygeneratedinterest->get($id, 0))->values();

        //récupération des parts épargnées par le membre pour chaque mois
        $values = $months->map(fn($name, $id) => $monthlysavings->get($id, 0))->values();

        //analyse statistique des épargnes
        $totalAmount = $values->sum();//montant total
        $totalAmountInterets = $valuesinterets->sum();//montant total des intérêts générés
        $averageAmount = number_format($monthlysavings->average(), 1); //moyenne des épargnes (les parts achetées par le membre) faites
        $maxAmount = $values->max();//parts maximum achetées

        //remplir les mois sans transactions avec zéro pour le calcul de la tendance
        $monthlyTotals = $months->mapWithKeys(function ($name, $id) use ($monthlysavings) {
            return [$id=>$monthlysavings->get($id, 0)];
        });

        //calcul de la tendance
        $trendData = $this->calculateTrend($monthlysavings); //calcul de la tendance pour les mois actifs (avec transactions) sur les parts achetées par le membre
        $trendProject = $this->calculateTrend($monthlyTotals);//projection sur tous les mois du projet
        $trend = $trendData['trend'];
        $trendProjection = $trendProject['trend'];
        $trendPercentage = $trendData['trendPercentage'];// exprimer la tendance en pourcentage pour chaque mois; //pourcentage de variation de la tendance ente le premier et le dernier mois
        $slope = $trendData['slope'];
        $intercept = $trendData['intercept'];

        $membre = Membre::find($membre_id);
        $projet = ProjetAvec::find($projet_id);
        $avec = Avec::with(["animateur", "superviseur"])->find($avec_id);
        $current_user = $request->user();

        $breadcrumbs = [
            ['url'=>url('display_membre', $membre->id), 'label'=>'Membre'],
            ['url'=>url('report_transactions_du_membre', [$membre->id, $avec->id, $projet->id]), 'label'=>'Transactions'],
            ['url'=>url('report_analytique_du_membre', [$membre->id, $avec->id, $projet->id]), 'label'=>'Analyse des transactions'],
        ];

        return view('layouts.dashboard_guest_layouts.reports.rapport_analytique_membre', compact('labels',
            'values', 'valuesinterets', 'projet', 'avec', 'membre', 'current_user', 'totalAmount', 'averageAmount', 'maxAmount',
            'trend', 'trendProjection', 'slope', 'breadcrumbs', 'intercept', 'trendPercentage', 'totalAmountInterets'));
    }

    public function report_analytique_de_avec($avec_id, $projet_id, Request $request) {
        $months = CycleDeGestion::where("projet_id", $projet_id)->pluck('designation', 'id');
        $monthlysavings = Transactions::selectRaw("mois_id, SUM(parts_achetees) as total_amount")->whereIn('mois_id',
            $months->keys())->where("avec_id", $avec_id)->where("statut_du_membre", "!=", "abandon")->groupBy('mois_id')->pluck("total_amount", "mois_id");

        //intérêts générés par les membres pour chaque mois
        $monthlygeneratedinterest = Transactions::selectRaw("mois_id, SUM(interet_genere) as total_interet")->whereIn('mois_id',
            $months->keys())->where("avec_id", $avec_id)->groupBy('mois_id')->pluck("total_interet", "mois_id");

        //récupération des noms de tous les mois du projet
        $labels = $months->values();

        //récupération des montants des intérêts générés pour chaque mois par les membres
        $valuesinterets = $months->map(fn($name, $id) => $monthlygeneratedinterest->get($id, 0))->values();

        //récupération des parts épargnées par le membre pour chaque mois
        $values = $months->map(fn($name, $id) => $monthlysavings->get($id, 0))->values();

        //analyse statistique des épargnes
        $totalAmount = $values->sum();//montant total
        $totalAmountInterets = $valuesinterets->sum();//montant total des intérêts générés
        $averageAmount = number_format($monthlysavings->average(), 1); //moyenne des épargnes (les parts achetées par le membre) faites
        $maxAmount = $values->max();//parts maximum achetées

        //remplir les mois sans transactions avec zéro pour le calcul de la tendance
        $monthlyTotals = $months->mapWithKeys(function ($name, $id) use ($monthlysavings) {
            return [$id=>$monthlysavings->get($id, 0)];
        });

        //calcul de la tendance
        $trendData = $this->calculateTrend($monthlysavings); //calcul de la tendance pour les mois actifs (avec transactions) sur les parts achetées par le membre
        $trendProject = $this->calculateTrend($monthlyTotals);//projection sur tous les mois du projet
        $trend = $trendData['trend'];
        $trendProjection = $trendProject['trend'];
        $trendPercentage = $trendData['trendPercentage'];// exprimer la tendance en pourcentage; pourcentage de variation de la tendance ente le premier et le dernier mois
        $slope = $trendData['slope'];
        $intercept = $trendData['intercept'];

        $projet = ProjetAvec::find($projet_id);
        $avec = Avec::with(["animateur", "superviseur"])->find($avec_id);
        $current_user = $request->user();

        $breadcrumbs = [
            ['url'=>url('display_avec', $avec->id), 'label'=>'Vue'],
            ['url'=>url('report_transactions_de_avec', [$avec->id, $projet->id]), 'label'=>"Transactions"],
            ['url'=>url('report_analytique_de_avec', [$avec->id, $projet->id]), 'label'=>"Analyse des transactions"],
        ];

        return view('layouts.dashboard_guest_layouts.reports.rapport_analytique_avec', compact('labels',
            'values', 'valuesinterets', 'projet', 'avec', 'current_user', 'totalAmount', 'averageAmount', 'maxAmount',
            'trend', 'trendProjection', 'slope', 'intercept', 'breadcrumbs', 'trendPercentage', 'totalAmountInterets'));
    }

    public function report_transactions_de_avec($avec_id, $projet_id, Request $request) {

        $transactions = Transactions::with("cycle_de_gestion")->where("avec_id", $avec_id)->where("statut_du_membre", "!=", "abandon")->selectRaw("mois_id, semaine,
        MIN(semaine_debut) as semaine_debut, MAX(semaine_fin) as semaine_fin, MAX(date_de_la_reunion) as date_de_la_reunion, SUM(parts_achetees) as parts_achetees, SUM(cotisation) as cotisation,
        SUM(amande) as amande, SUM(credit) as credit, SUM(credit_rembourse) as credit_rembourse,
        SUM(interet_genere) as interet_genere")->groupBy('mois_id', 'semaine')->orderBy('mois_id')->orderBy('semaine')->get();

        $projet = ProjetAvec::find($projet_id);
        $avec = Avec::with(["animateur", "superviseur"])->find($avec_id);
        $cycle_de_gestion = CycleDeGestion::where("projet_id", $projet->id)->get();
        $current_user = $request->user();

        $totalMembres = Membre::where("avec_id", $avec_id)->where('statut', "!=", "abandon")->count();
        $Membres = Membre::where("avec_id", $avec_id)->where('statut', "!=", "abandon")->get();
        $actifs = Membre::where("avec_id", $avec_id)->where('statut', 'actif')->count();
        $inactifs = Membre::where("avec_id", $avec_id)->where('statut', 'inactif')->count();
        $abandons = Membre::where("avec_id", $avec_id)->where('statut', 'abandon')->count();

        $hommes = Membre::where("avec_id", $avec_id)->where("sexe", "homme")->where('statut', "!=", "abandon")->count();
        $femmes = Membre::where("avec_id", $avec_id)->where("sexe", "femme")->where('statut', "!=", "abandon")->count();

        $caisse_epargne = CaisseEpargne::where('avec_id', $avec->id)->first();
        $caisse_solidarite = CaisseSolidarite::where('avec_id', $avec->id)->first();
        $caisse_amande = CaisseAmande::where('avec_id', $avec->id)->first();
        $montantamande = $caisse_amande ? $caisse_amande->montant: 0;
        $montantsolidarite = $caisse_solidarite ? $caisse_solidarite->montant: 0;
        $montantencaisse = $caisse_epargne ? $caisse_epargne->montant: 0;
        $montantinteret = $avec->interets;


        $partsTotAchetees = 0;
        foreach ($Membres as $membre){
            if ($membre->statut != "abandon") {
                $partsTotAchetees += $membre->part_tot_achetees;
            }
        }

        $breadcrumbs = [
            ['url'=>url('lst_des_avecs', $projet->id), 'label'=>'Avecs'],
            ['url'=>url('display_avec', $avec->id), 'label'=>'Vue'],
            ['url'=>url('report_transactions_de_avec', [$avec->id, $projet->id]), 'label'=>"Transactions"],
        ];

        return view('layouts.dashboard_guest_layouts.reports.rapport_transactions_avec', compact("projet",
            "transactions", "avec", "cycle_de_gestion", "current_user", "totalMembres",
            "partsTotAchetees", "montantinteret", "montantamande", "montantsolidarite", "montantencaisse",
            "hommes", "femmes", "actifs", "inactifs", "abandons", "breadcrumbs"));
    }

    public function situation_generale_de_avec($avec_id, $projet_id, Request $request) {
        $projet = ProjetAvec::find($projet_id);
        $avec = Avec::with(["animateur", "superviseur"])->find($avec_id);
        $current_user = $request->user();
        $totalMembres = Membre::where("avec_id", $avec_id)->where('statut', "!=", "abandon")->count();
        $Membres = Membre::where("avec_id", $avec_id)->where('statut', "!=", "abandon")->get();
        $actifs = Membre::where("avec_id", $avec_id)->where('statut', 'actif')->count();
        $inactifs = Membre::where("avec_id", $avec_id)->where('statut', 'inactif')->count();
        $abandons = Membre::where("avec_id", $avec_id)->where('statut', 'abandon')->count();

        $hommes = Membre::where("avec_id", $avec_id)->where("sexe", "homme")->where('statut', "!=", "abandon")->count();
        $femmes = Membre::where("avec_id", $avec_id)->where("sexe", "femme")->where('statut', "!=", "abandon")->count();

        $caisse_epargne = CaisseEpargne::where('avec_id', $avec->id)->first();
        $caisse_solidarite = CaisseSolidarite::where('avec_id', $avec->id)->first();
        $caisse_amande = CaisseAmande::where('avec_id', $avec->id)->first();
        $montant_interet = $avec->interets;

        //pas important (juste pour le chart des crédits/interets/total montant épargné de chaque membre)
        $credit_des_membres = [];
        $interets_sur_credit_des_membres = [];
        $parts_ht_des_membres = [];


        $montantTotalEpargne = 0;
        foreach ($Membres as $membre){
            $montantTotalEpargne += $membre->part_tot_achetees;
            $parts_ht_des_membres[] = ["name" => $membre->nom, "value" => $membre->part_tot_achetees];
        }

        $montantTotalcredit = 0;
        foreach ($Membres as $membre){
            $montantTotalcredit += $membre->credit;
            $credit_des_membres[] = ["name" => $membre->nom, 'value' => $membre->credit];
        }

        $interetTotalcredit = 0;
        foreach ($Membres as $membre){
            $interetTotalcredit += $membre->interets_sur_credit;
            $interets_sur_credit_des_membres[] = ["name"=>$membre->nom ,"value"=>$membre->interets_sur_credit];
        }

        $breadcrumbs = [
            ['url'=>url('lst_des_avecs', $projet->id), 'label'=>'Avecs'],
            ['url'=>url('display_avec', $avec->id), 'label'=>'Vue'],
            ['url'=>url('situation_gen_avec', [$avec->id, $projet->id]), 'label'=>"Situation de l'avec"],
        ];

        return view('layouts.dashboard_guest_layouts.reports.situation_generale_avec', compact("projet", "avec",
            "actifs", "inactifs", "abandons", "current_user", "hommes", "femmes", "totalMembres", "caisse_amande", "caisse_epargne",
            "caisse_solidarite", "montant_interet", "montantTotalEpargne", "montantTotalcredit", "interetTotalcredit",
            "interets_sur_credit_des_membres", "parts_ht_des_membres", "breadcrumbs", "credit_des_membres"));
    }

    public function releve_des_transactions_caisse_solidarite($avec_id, $projet_id, Request $request):View
    {
        $avec = Avec::with(["animateur", "superviseur", 'membres'])->find($avec_id);
        $projet = ProjetAvec::find($projet_id);
        $transactions = SoutienCaisseSolidarite::with("membre")->where("avec_id", $avec_id)->get();
        $transactionsCount = Transactions::where("avec_id", $avec_id)->get()->count();
        $current_user = $request->user();

        $totalMembres = Membre::where("avec_id", $avec_id)->where('statut', "!=", "abandon")->count();
        $Membres = Membre::where("avec_id", $avec_id)->where('statut', "!=", "abandon")->get();
        $actifs = Membre::where("avec_id", $avec_id)->where('statut', 'actif')->count();
        $inactifs = Membre::where("avec_id", $avec_id)->where('statut', 'inactif')->count();
        $abandons = Membre::where("avec_id", $avec_id)->where('statut', 'abandon')->count();

        $hommes = Membre::where("avec_id", $avec_id)->where("sexe", "homme")->where('statut', "!=", "abandon")->count();
        $femmes = Membre::where("avec_id", $avec_id)->where("sexe", "femme")->where('statut', "!=", "abandon")->count();

        $caisse_epargne = CaisseEpargne::where('avec_id', $avec->id)->first();
        $caisse_solidarite = CaisseSolidarite::where('avec_id', $avec->id)->first();
        $caisse_amande = CaisseAmande::where('avec_id', $avec->id)->first();
        $montantamande = $caisse_amande ? $caisse_amande->montant: 0;
        $montantsolidarite = $caisse_solidarite ? $caisse_solidarite->montant: 0;
        $montantencaisse = $caisse_epargne ? $caisse_epargne->montant: 0;
        $montantinteret = $avec->interets;
        $partsTotAchetees = 0;
        foreach ($Membres as $membre){
            if ($membre->statut != "abandon") {
                $partsTotAchetees += $membre->part_tot_achetees;
            }
        }

        $breadcrumbs = [
            ['url'=>url('lst_des_avecs', $projet->id), 'label'=>'Avecs'],
            ['url'=>url('display_avec', $avec->id), 'label'=>'Vue'],
            ['url'=>url('transactions_caisse_solidarite', [$avec->id, $projet->id]), 'label'=>"Transactions"],
        ];

        return view('layouts.dashboard_guest_layouts.reports.releve_transactions_de_soutien', compact("projet",
            "transactions", "avec", "current_user", "totalMembres",
            "partsTotAchetees", "montantinteret", "montantamande", "montantsolidarite", "montantencaisse",
            "hommes", "femmes", "breadcrumbs", "actifs", "inactifs", "abandons", 'transactionsCount'));
    }
}
