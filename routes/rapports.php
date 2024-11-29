<?php

use App\Http\Controllers\RapportsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->name('rapports.')->group(function () {
    Route::get('/rapport_transactions_membre/{membre_id}/{projet_id}/{avec_id}', [RapportsController::class, 'rapport_transactions_membre'])
        ->name('rapport_transactions_membre');
    Route::get('/rapport_analytique_membre/{membre_id}/{projet_id}/{avec_id}', [RapportsController::class, 'rapport_analytique_membre'])
        ->name('rapport_analytique_membre');
    Route::get('/rapport_analytique_avec/{avec_id}/{projet_id}', [RapportsController::class, 'rapport_analytique_avec'])
        ->name('rapport_analytique_avec');
    Route::get('/rapport_transactions_avec/{avec_id}/{projet_id}', [RapportsController::class, 'rapport_transactions_avec'])
        ->name('rapport_transactions_avec');
    Route::get('/situation_generale_avec/{avec_id}/{projet_id}', [RapportsController::class, 'situation_generale_avec'])
        ->name('situation_generale_avec');
    Route::get('/releve_transactions_caisse_solidarite/{avec_id}/{projet_id}', [RapportsController::class, 'releve_transactions_caisse_solidarite'])
        ->name('releve_transactions_caisse_solidarite');
});
