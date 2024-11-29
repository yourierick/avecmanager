<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasOctroiSoutien extends Model
{
    use HasFactory;
    protected $fillable = [
        "cas",
        "avec_id"
    ];

    public function avec()
    {
        return $this->belongsTo(Avec::class, "avec_id");
    }
}
