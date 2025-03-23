<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'annonce_id',
        'cv_path',
        'lettre_motivation',
        'statut',
    ];

    /**
     * Get the user that owns the candidature.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the annonce that owns the candidature.
     */
    public function annonce()
    {
        return $this->belongsTo(Annonce::class);
    }

    /**
     * Get the notifications for the candidature.
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}