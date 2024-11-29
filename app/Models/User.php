<?php

namespace App\Models;

use App\Notifications\CustomerVerifyEmail;
use App\Notifications\CustomResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'photo',
        'sexe',
        'adresse',
        'telephone',
        'email',
        'password',
        'statut',
        'fonction',
        'droits',
        'autorisations',
        'projet_id',
        'superviseur_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendEmailVerificationNotification() {
        $this->notify(new CustomerVerifyEmail());
    }

    public function sendPasswordResetNotification($token) {
        $this->notify(new CustomResetPassword($token));
    }

    public function projet()
    {
        return $this->belongsTo(ProjetAvec::class, "projet_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "superviseur_id");
    }

//    public function imageUrl (): string
//    {
//        return Storage::disk('public')->url($this->photo);
//    }
}
