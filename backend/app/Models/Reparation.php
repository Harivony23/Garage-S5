<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reparation extends Model
{
    use HasFactory;

    protected $fillable = ['voiture_id', 'statut', 'slot', 'started_at', 'ended_at', 'total'];

    public function voiture()
    {
        return $this->belongsTo(Voiture::class);
    }

    public function interventions()
    {
        return $this->hasMany(ReparationIntervention::class);
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }
}
