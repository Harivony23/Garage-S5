<?php

namespace App\Http\Controllers;

use App\Models\ReparationIntervention;
use Illuminate\Http\Request;

class ReparationInterventionController extends Controller
{
    // Liste des interventions d'une réparation
    public function index($reparationId)
    {
        $interventions = ReparationIntervention::with('intervention')
            ->where('reparation_id', $reparationId)
            ->get();

        return response()->json($interventions);
    }

    // Mettre à jour la progression / statut d'une intervention
    public function update(Request $request, ReparationIntervention $reparationIntervention)
    {
        $validated = $request->validate([
            'statut' => 'required|string|in:en_attente,en_cours,terminee',
            'progression' => 'required|integer|min:0|max:100',
        ]);

        $reparationIntervention->update($validated);
        return response()->json($reparationIntervention);
    }
}
