<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaisseInteret extends Model
{
    use HasFactory;
    protected $fillable = ["projet_id", "avec_id", "mois_id", "semaine", 'montant'];

    public function projet()
    {
        return $this->belongsTo(ProjetAvec::class, "projet_id");
    }

    public function avec()
    {
        return $this->belongsTo(Avec::class, "avec_id");
    }

    public function mois()
    {
        return $this->belongsTo(CycleDeGestion::class, "mois_id");
    }
}
