<?php

use App\Http\Controllers\EquipeDeGestionController;
use App\Http\Middleware\CheckAccountType;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', CheckAccountType::class.':utilisateur'])->name('gestionprojet.')->group(function () {
    Route::get('/list_personnel_projet/{projet_id}', [EquipeDeGestionController::class, 'list_du_personnel_projet'])
        ->name('lists_personnel');
    Route::get('/list_avecs/{projet_id}', [EquipeDeGestionController::class, 'list_avecs'])
        ->name('list_avecs');
    Route::get('/ajouter_un_animateur/{projet_id}', [EquipeDeGestionController::class, 'ajouter_un_animateur'])
        ->name('ajouter_un_animateur');
    Route::post('/ajouter_un_animateur/{projet_id}', [EquipeDeGestionController::class, 'save_animateur']);
    Route::get('/ajouter_une_avec/{projet_id}', [EquipeDeGestionController::class, 'ajouter_une_avec'])
        ->name('ajouter_une_avec');
    Route::post("/ajouter_une_avec/{projet_id}", [EquipeDeGestionController::class, 'save_avec']);

    Route::get('afficher_avec/{avec_id}', [EquipeDeGestionController::class,
        'afficher_avec'])->name('afficher_avec');
    Route::put('edit_avec_configuration/{avec_id}', [EquipeDeGestionController::class,
        'edit_avec_configuration'])->name('edit_avec_configuration');
    Route::get('ajouter_un_membre/{avec_id}', [EquipeDeGestionController::class,
        'ajouter_un_membre'])->name('ajouter_un_membre');
    Route::post('ajouter_un_membre/{avec_id}', [EquipeDeGestionController::class,
        'save_membre']);
    Route::post('ajouter_comite/{avec_id}', [EquipeDeGestionController::class,
        'ajouter_comite'])->name('ajouter_comite');
    Route::put('editer_un_membre/{membre_id}', [EquipeDeGestionController::class,
        'editer_un_membre'])->name('editer_un_membre');
    Route::delete('supprimer_un_membre_avec', [EquipeDeGestionController::class,
        'supprimer_un_membre_avec'])->name('supprimer_un_membre_avec');
    Route::get('gestion_cas_abandon_membre/{membre_id}/{avec_id}', [EquipeDeGestionController::class,
        'gestion_cas_abandon_membre'])->name("gestion_cas_abandon_membre");
    Route::post('gestion_cas_abandon_membre_treatment/{membre_id}/{avec_id}', [EquipeDeGestionController::class,
        'gestion_cas_abandon_membre_treatment'])->name("gestion_cas_abandon_membre_treatment");
    Route::delete('supprimer_fonction_membre/{membre_id}',
        [EquipeDeGestionController::class, 'supprimer_fonction_membre'])->name('supprimer_fonction_membre');
    Route::delete('supprimer_regle_de_taxation_amande', [EquipeDeGestionController::class,
        'supprimer_regle_de_taxation_amande'])->name('supprimer_regle_de_taxation_amande');
    Route::delete('supprimer_regle_de_taxation_interet', [EquipeDeGestionController::class,
        'supprimer_regle_de_taxation_interet'])->name('supprimer_regle_de_taxation_interet');
    Route::post('ajouter_comite/{avec_id}', [EquipeDeGestionController::class,
        'ajouter_comite'])->name('ajouter_comite');
    Route::post('ajouter_regle_de_taxation_amande/{avec_id}', [EquipeDeGestionController::class,
        'ajouter_regle_de_taxation_amande'])->name('ajouter_regle_de_taxation_amande');
    Route::post('ajouter_regle_de_taxation_interet/{avec_id}', [EquipeDeGestionController::class,
        'ajouter_regle_de_taxation_interet'])->name('ajouter_regle_de_taxation_interet');
    Route::post('ajouter_comite/{avec_id}', [EquipeDeGestionController::class,
        'ajouter_comite'])->name('ajouter_comite');
    Route::post('ajouter_comite/{avec_id}', [EquipeDeGestionController::class,
        'ajouter_comite'])->name('ajouter_comite');
    Route::post('ajouter_cas_octroi_soutien/{avec_id}', [EquipeDeGestionController::class,
        'ajouter_cas_octroi_soutien'])->name('ajouter_cas_octroi_soutien');
    Route::delete('supprimer_cas_octroi_soutien', [EquipeDeGestionController::class,
        'supprimer_cas_octroi_soutien'])->name('supprimer_cas_octroi_soutien');
    Route::get('assister_un_membre/{avec_id}/{projet_id}', [EquipeDeGestionController::class,
        'assister_un_membre'])->name('assister_un_membre');
    Route::post('save_transaction_assistance/{avec_id}/{projet_id}', [EquipeDeGestionController::class,
        'save_transaction_assistance'])->name('save_transaction_assistance');
    Route::delete('supprimer_transaction_caisse_solidarite/{avec_id}', [EquipeDeGestionController::class,
        'supprimer_transaction_caisse_solidarite'])->name('supprimer_transaction_caisse_solidarite');


    #GESTION MEMBRES
    Route::get('afficher_un_membre/{membre_id}', [EquipeDeGestionController::class,
        'afficher_un_membre'])->name('afficher_un_membre');
    Route::get('transactions_hebdomadaire/{membre_id}', [EquipeDeGestionController::class,
        'transactions_hebdomadaire'])->name('transactions_hebdomadaire');
    Route::get('calcul_taux_interet/{pret}/{avec_id}',
        [EquipeDeGestionController::class, 'calcul_taux_interet'])->name('calcul_taux_interet');
    Route::post('save_transactions_hebdomadaire/{membre_id}',
        [EquipeDeGestionController::class, 'save_transactions_hebdomadaire'])->name('save_transactions_hebdomadaire');
    Route::delete('supprimer_transaction',
        [EquipeDeGestionController::class, 'supprimer_transaction'])->name('supprimer_transaction');
    Route::get("/load_semaines/{mois_id}/{membre_id}/{avec_id}",
        [EquipeDeGestionController::class, 'load_semaines'])->name("load_semaines");
});
