<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membre extends Model
{
    use HasFactory;

    protected $fillable = [
        "nom",
        "sexe",
        "adresse",
        "numeros_de_telephone",
        "avec_id",
        "photo",
        "statut",
        "parts_tot_achetees",
        "gains_actuels",
        "credit",
        "date_de_remboursement",
        "gains",
    ];

    protected function casts(): array
    {
        return [
            'date_de_remboursement' => 'datetime',
        ];
    }

    public function avec()
    {
        return $this->belongsTo(Avec::class, "avec_id");
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class);
    }

    public function transactions_caisse_solidarite()
    {
        return $this->hasMany(SoutienCaisseSolidarite::class);
    }

    public function agenda()
    {
        return $this->hasMany(Agenda::class);
    }

    public function fonction()
    {
        return $this->hasOne(ComiteAvec::class);
    }
}
