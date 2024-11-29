<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CycleDeGestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'mois',
        'projet_id',
        'designation',
    ];

    public function projet()
    {
        return $this->belongsTo(ProjetAvec::class, "projet_id");
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class);
    }

    public function interets()
    {
        return $this->hasOne(CaisseInteret::class);
    }
}
