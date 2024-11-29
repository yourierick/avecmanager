<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\CheckAccountType;

Route::get('/dashboard_admin', [DashboardController::class, 'admin_dashboard'])->middleware(['auth', 'verified', CheckAccountType::class.':administrateur'])->name('dashboard_admin');
Route::get('/user_dashboard', [DashboardController::class, 'user_dashboard'])->middleware(['auth', 'verified', CheckAccountType::class.':utilisateur'])->name('user_dashboard');
Route::get('/guest_dashboard', [DashboardController::class, 'guest_dashboard'])->middleware(['auth', 'verified', CheckAccountType::class.':visiteur'])->name('guest_dashboard');

Route::middleware(["auth"])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/mettre_a_jour', [ProfileController::class, 'update'])->name('profile.update')->withoutMiddleware(CheckAccountType::class);
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware([CheckAccountType::class.':administrateur', "auth"])->name('projet.')->group(function () {
   Route::get('/list_projet', [DashboardController::class, 'list_projets'])->name('list');
   Route::get('/ajouter_un_projet', [DashboardController::class, 'ajouter_un_projet'])->name('ajouter');
   Route::get('/configuration_projet/{projet_id}', [DashboardController::class, 'configuration_projet'])->name('configuration');
   Route::post('/enregistrer_projet', [DashboardController::class, 'enregistrer_projet'])->name('enregistrer');
   Route::put('/configuration_mois_cycle_de_gestion', [DashboardController::class, 'configuration_mois_cycle_de_gestion'])->name('configuration_mois_cycle_de_gestion');
   Route::put('/save_edition_projet/{projet_id}', [DashboardController::class, 'save_edition_projet'])->name('save_edition_projet');
   Route::get('/afficher_projet/{projet_id}', [DashboardController::class, 'afficher_projet'])->name('afficher_projet');
   Route::get('/afficher_une_avec/{avec_id}', [DashboardController::class, 'afficher_une_avec'])->name('afficher_une_avec');
   Route::put('/traitement_projet/{projet_id}', [DashboardController::class, 'traitement_projet'])->name('traitement');
   Route::get('/list_des_avecs/{projet_id}', [DashboardController::class, 'list_des_avecs'])->name('list_des_avecs');
   Route::post('/ajouter_axe/{projet_id}', [DashboardController::class, 'ajouter_axe'])->name('ajouter_axe');
   Route::delete('/supprimer_axe', [DashboardController::class, 'supprimer_axe'])->name('supprimer_axe');
   Route::delete('/supprimer_avec', [DashboardController::class, 'supprimer_avec'])->name('supprimer_avec');
   Route::delete('/supprimer_un_membre', [DashboardController::class, 'supprimer_un_membre'])->name('supprimer_un_membre');
   Route::get('/list_du_personnel_projet/{projet_id}', [DashboardController::class, 'list_du_personnel_projet'])->name('list_du_personnel_projet');
   Route::get('loadsuperviseurs/{projet_id}', [DashboardController::class, 'loadsuperviseurs'])->name('loadsuperviseurs');
   Route::get('afficher_membre/{membre_id}', [DashboardController::class, 'afficher_membre'])->name('afficher_membre');
});


Route::middleware('auth')->name('user.')->group(function () {
    Route::get('/manage_user', [DashboardController::class, 'profiles'])->middleware([CheckAccountType::class.':administrateur'])->name('list_users');
    Route::get('edit_profile_user/{user_id}', [DashboardController::class, 'edit_profile_user'])->middleware([CheckAccountType::class.':administrateur'])->name('profile_edit');
    Route::put('update_user_password/{user_id}', [DashboardController::class, 'update_user_password'])->middleware([CheckAccountType::class.':administrateur'])->name('update_password');
    Route::put('update_profile_user/{user_id}', [DashboardController::class, 'update_profile_user'])->middleware([CheckAccountType::class.':administrateur'])->name('update_profile');
    Route::delete('destroy_user_account/{user_id}', [DashboardController::class, 'destroy_user_account'])->middleware([CheckAccountType::class.':administrateur'])->name('destroy_account');
});

Route::middleware('auth')->name('agenda.')->group(function () {
    Route::get('/agenda', [DashboardController::class, 'agenda'])->name('agenda');
    Route::post('/add_task', [DashboardController::class, 'add_task'])->name('add_task');
    Route::put('/change_status_task/{tache_id}', [DashboardController::class, 'change_status_task'])->name('change_status_task');
    Route::delete('/delete_task', [DashboardController::class, 'delete_task'])->name('delete_task');
});



require __DIR__.'/auth.php';
require __DIR__.'/equipegestion.php';
require __DIR__.'/rapports.php';
require __DIR__.'/rapportsadmin.php';
require __DIR__.'/guestroutes.php';
