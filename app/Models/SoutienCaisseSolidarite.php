<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoutienCaisseSolidarite extends Model
{
    use HasFactory;
    protected $fillable = [
        "avec_id",
        "beneficiaire",
        "cas",
        "montant",
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'date',
        ];
    }

    #chargement de la relation membre
    public function membre()
    {
        return $this->belongsTo(Membre::class, "beneficiaire");
    }

    public function avec()
    {
        return $this->belongsTo(Avec::class, "avec_id");
    }
}
