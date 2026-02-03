<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = ['reparation_id', 'montant', 'statut', 'paid_at'];

    public function reparation()
    {
        return $this->belongsTo(Reparation::class);
    }
}
