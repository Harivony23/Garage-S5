<?php

namespace App\Http\Controllers;

use App\Models\Reparation;
use Illuminate\Http\Request;

class ReparationController extends Controller
{
    // Frontoffice: réparations en cours / en attente avec filtre optionnel
    public function index(Request $request)
    {
        $query = Reparation::with('voiture.client', 'interventions.intervention');

        // Filtrer par statut si fourni
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        } else {
            // Sinon lister uniquement en attente ou en cours
            $query->whereIn('statut', ['en_attente','en_cours']);
        }

        // Filtrer par slot si nécessaire (ex: slot=1 ou 2)
        if ($request->has('slot')) {
            $query->where('slot', $request->slot);
        }

        $reparations = $query->get();
        return response()->json($reparations);
    }

    // Backoffice: créer une réparation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'voiture_id' => 'required|exists:voitures,id',
            'statut' => 'required|string|in:en_attente,en_cours,terminee',
            'slot' => 'required|integer|in:1,2',
            'started_at' => 'nullable|date',
            'ended_at' => 'nullable|date',
            'total' => 'nullable|numeric',
        ]);

        $reparation = Reparation::create($validated);
        return response()->json($reparation, 201);
    }

    // Modifier une réparation
    public function update(Request $request, Reparation $reparation)
    {
        $validated = $request->validate([
            'statut' => 'required|string|in:en_attente,en_cours,terminee',
            'slot' => 'required|integer|in:1,2',
            'started_at' => 'nullable|date',
            'ended_at' => 'nullable|date',
            'total' => 'nullable|numeric',
        ]);

        $reparation->update($validated);
        return response()->json($reparation);
    }

    // Supprimer une réparation
    public function destroy(Reparation $reparation)
    {
        $reparation->delete();
        return response()->json(['message' => 'Supprimé']);
    }
}
