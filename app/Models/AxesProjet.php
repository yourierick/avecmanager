<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AxesProjet extends Model
{
    use HasFactory;
    protected $fillable = [
        'projet_id',
        'designation',
    ];

    public function projet()
    {
        return $this->belongsTo(ProjetAvec::class, 'projet_id');
    }
}
