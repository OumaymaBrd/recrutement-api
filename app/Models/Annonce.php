<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titre',
        'description',
        'localisation',
        'type_contrat',
        'salaire',
        'user_id',
    ];

    /**
     * Get the user that owns the annonce.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the candidatures for the annonce.
     */
    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }
}