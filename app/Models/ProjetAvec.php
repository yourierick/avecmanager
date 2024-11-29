<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjetAvec extends Model
{
    use HasFactory;
    protected $fillable = [
        'context',
        'code_reference',
        'cycle_de_gestion',
        'statut',
        'date_de_debut',
        'date_de_fin'
    ];

    protected function casts():array
    {
        return [
            'date_de_debut'=>'datetime',
            'date_de_fin'=>'datetime',
        ];
    }

    public function avec()
    {
        return $this->hasMany(Avec::class);
    }

    public function cycle_de_gestion()
    {
        return $this->hasMany(CycleDeGestion::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class);
    }

    public function caisseamande()
    {
        return $this->hasMany(CaisseAmande::class);
    }

    public function axes()
    {
        return $this->hasMany(AxesProjet::class);
    }
}
