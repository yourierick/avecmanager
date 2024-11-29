<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;


Route::middleware('auth')->name('guest.')->group(function () {
    Route::get('/lst_des_avecs/{projet_id}', [GuestController::class, 'lst_des_avecs'])
        ->name('lst_des_avecs');
    Route::get('display_avec/{avec_id}', [GuestController::class,
        'display_avec'])->name('display_avec');
    Route::get('display_membre/{membre_id}', [GuestController::class,
        'display_membre'])->name('display_membre');
    Route::get('report_transactions_du_membre/{membre_id}/{projet_id}/{avec_id}', [GuestController::class,
        'report_transactions_du_membre'])->name('report_transactions_du_membre');
    Route::get('report_analytique_du_membre/{membre_id}/{projet_id}/{avec_id}', [GuestController::class,
        'report_analytique_du_membre'])->name('report_analytique_du_membre');
    Route::get('report_analytique_de_avec/{avec_id}/{projet_id}', [GuestController::class,
        'report_analytique_de_avec'])->name('report_analytique_de_avec');
    Route::get('report_transactions_de_avec/{avec_id}/{projet_id}', [GuestController::class,
        'report_transactions_de_avec'])->name('report_transactions_de_avec');
    Route::get('situation_gen_avec/{avec_id}/{projet_id}', [GuestController::class,
        'situation_generale_de_avec'])->name('situation_generale_de_avec');
    Route::get('transactions_caisse_solidarite/{avec_id}/{projet_id}', [GuestController::class,
        'releve_des_transactions_caisse_solidarite'])->name('releve_des_transactions_caisse_solidarite');
});
