<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReparationIntervention extends Model
{
    use HasFactory;

    protected $table = 'reparation_intervention';

    protected $fillable = ['reparation_id', 'intervention_id', 'statut', 'progression'];

    public function reparation()
    {
        return $this->belongsTo(Reparation::class);
    }

    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }
}
