<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComiteAvec extends Model
{
    use HasFactory;

    protected $fillable = [
        "avec_id",
        "membre_id",
        "fonction",
    ];

    public function avec()
    {
        return $this->belongsTo(Avec::class, "avec_id");
    }

    public function membre()
    {
        return $this->belongsTo(Membre::class, "membre_id");
    }
}
