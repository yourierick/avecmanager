<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReglesDeTaxationDesAmandes extends Model
{
    use HasFactory;

    protected $fillable = [
        "avec_id",
        "regle",
        "amande",
    ];

    public function avec()
    {
        return $this->belongsTo(Avec::class, "avec_id");
    }
}
