<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "tache",
        "date",
        "heure_debut",
        "heure_fin",
        "statut",
    ];

    protected function casts():array
    {
        return [
            'date'=>'date',
        ];
    }

    public function user() {
        return $this->belongsTo(User::class, "user_id");
    }
}
