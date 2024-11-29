<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    protected $fillable = [
        "projet_id",
        "avec_id",
        'membre_id',
        'mois_id',
        'semaine',
        'semaine_debut',
        'semaine_fin',
        'date_de_la_reunion',
        'num_reunion',
        'frequentation',
        'parts_achetees',
        'cotisation',
        'amande',
        'credit',
        'taux_interet',
        'date_de_remboursement',
        'credit_rembourse',
        'interet_rembourse',
        'statut_du_membre',
    ];

    protected function casts(): array
    {
        return [
            'date_de_remboursement' => 'date',
            'date_de_la_reunion' => 'date',
            'semaine_debut' => 'date',
            'semaine_fin' => 'date',
        ];
    }

    #chargement de la relation membre
    public function membre()
    {
        return $this->belongsTo(Membre::class, "membre_id");
    }

    #chargement de la relation mois du projet
    public function cycle_de_gestion()
    {
        return $this->belongsTo(CycleDeGestion::class, "mois_id");
    }

    public function avec()
    {
        return $this->belongsTo(Avec::class, 'avec_id');
    }

    public function projet()
    {
        return $this->belongsTo(Avec::class, 'projet_id');
    }
}
