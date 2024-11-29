<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avec extends Model
{
    use HasFactory;

    protected $fillable = [
        "code",
        "designation",
        "projet_id",
        "animateur_id",
        "superviseur_id",
        "axe_id",
        "valeur_part",
        "maximum_part_achetable",
        "valeur_montant_solidarite",
    ];

    #chargement de la relation membre
    public function animateur()
    {
        return $this->belongsTo(User::class, "animateur_id");
    }

    public function superviseur()
    {
        return $this->belongsTo(User::class, "superviseur_id");
    }

    public function membres()
    {
        return $this->hasMany(Membre::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class);
    }

    public function transactions_caisse_solidarite()
    {
        return $this->hasMany(SoutienCaisseSolidarite::class);
    }

    public function projet()
    {
        return $this->belongsTo(ProjetAvec::class, "projet_id");
    }

    public function axe()
    {
        return $this->belongsTo(AxesProjet::class, "axe_id");
    }
}
