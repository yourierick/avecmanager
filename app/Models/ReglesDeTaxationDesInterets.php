<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReglesDeTaxationDesInterets extends Model
{
    use HasFactory;

    protected $fillable = [
        "avec_id",
        "enonce_regle",
        "valeur_min",
        "valeur_max",
        "taux_interet",
    ];

    public function avec()
    {
        return $this->belongsTo(Avec::class, "avec_id");
    }
}
