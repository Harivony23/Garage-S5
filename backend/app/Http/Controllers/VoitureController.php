<?php

namespace App\Http\Controllers;

use App\Models\Voiture;
use Illuminate\Http\Request;

class VoitureController extends Controller
{
    // Liste des voitures
    public function index()
    {
        $voitures = Voiture::with('client', 'reparations')->get();
        return response()->json($voitures);
    }

    // Ajouter une voiture
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'marque' => 'required|string|max:100',
            'modele' => 'required|string|max:100',
            'statut' => 'required|string|in:en_attente,en_cours,terminee',
        ]);

        $voiture = Voiture::create($validated);
        return response()->json($voiture, 201);
    }

    // Modifier une voiture
    public function update(Request $request, Voiture $voiture)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'marque' => 'required|string|max:100',
            'modele' => 'required|string|max:100',
            'statut' => 'required|string|in:en_attente,en_cours,terminee',
        ]);

        $voiture->update($validated);
        return response()->json($voiture);
    }

    // Supprimer une voiture
    public function destroy(Voiture $voiture)
    {
        $voiture->delete();
        return response()->json(['message' => 'Supprimé']);
    }
}
