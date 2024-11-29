<?php

use App\Http\Controllers\RapportsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportsAdminController;

Route::middleware('auth')->name('report.')->group(function () {
    Route::get('/rapport_transactions_du_membre/{membre_id}/{projet_id}/{avec_id}', [ReportsAdminController::class, 'rapport_transactions_du_membre'])
        ->name('rapport_transactions_du_membre');
    Route::get('/rapport_analytique_du_membre/{membre_id}/{projet_id}/{avec_id}', [ReportsAdminController::class, 'rapport_analytique_du_membre'])
        ->name('rapport_analytique_du_membre');
    Route::get('/rapport_analytique_de_avec/{avec_id}/{projet_id}', [ReportsAdminController::class, 'rapport_analytique_de_avec'])
        ->name('rapport_analytique_de_avec');
    Route::get('/rapport_transactions_de_avec/{avec_id}/{projet_id}', [ReportsAdminController::class, 'rapport_transactions_de_avec'])
        ->name('rapport_transactions_de_avec');
    Route::get('/situation_generale_de_avec/{avec_id}/{projet_id}', [ReportsAdminController::class, 'situation_generale_de_avec'])
        ->name('situation_generale_de_avec');
    Route::get('/releve_des_transactions_caisse_solidarite/{avec_id}/{projet_id}', [ReportsAdminController::class, 'releve_des_transactions_caisse_solidarite'])
        ->name('releve_des_transactions_caisse_solidarite');
});
