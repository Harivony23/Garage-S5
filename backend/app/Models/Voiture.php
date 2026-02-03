<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voiture extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'marque', 'modele', 'statut'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function reparations()
    {
        return $this->hasMany(Reparation::class);
    }
}
