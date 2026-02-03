<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'email', 'telephone'];

    public function voitures()
    {
        return $this->hasMany(Voiture::class);
    }

    public function reparations()
    {
        return $this->hasManyThrough(Reparation::class, Voiture::class);
    }
}
