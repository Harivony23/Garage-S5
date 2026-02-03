<?php

namespace App\Http\Controllers;

use App\Models\Reparation;
use App\Models\Client;

class StatistiqueController extends Controller
{
    public function index()
    {
        // On calcule le total en sommant les interventions de toutes les réparations (ou juste terminées selon besoin)
        $totalMontant = \App\Models\ReparationIntervention::join('interventions', 'reparation_intervention.intervention_id', '=', 'interventions.id')
            ->sum('interventions.prix');

        $nombreClients = Client::count();

        return response()->json([
            'total_montant' => $totalMontant,
            'nombre_clients' => $nombreClients
        ]);
    }
}
