<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use Illuminate\Http\Request;
use App\Services\FirebaseService;

class InterventionController extends Controller
{
    protected FirebaseService $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    // Liste des interventions (Backoffice)
    public function index()
    {
        $interventions = Intervention::all();
        return response()->json($interventions);
    }

    // Ajouter une intervention
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|unique:interventions,nom',
            'prix' => 'required|numeric',
            'duree_minutes' => 'required|integer',
        ]);

        $intervention = Intervention::create($validated);

        // Synchroniser avec Firebase
        $this->firebase->setIntervention([
            'id' => strtolower(str_replace(' ', '_', $intervention->nom)), 
            'name' => $intervention->nom,
            'price' => $intervention->prix,
            'duration_minutes' => $intervention->duree_minutes,
        ]);

        return response()->json($intervention, 201);
    }

    // Modifier une intervention
    public function update(Request $request, Intervention $intervention)
    {
        $validated = $request->validate([
            'nom' => 'required|unique:interventions,nom,' . $intervention->id,
            'prix' => 'required|numeric',
            'duree_minutes' => 'required|integer',
        ]);

        $intervention->update($validated);

        // Synchroniser la modification dans Firebase
        $this->firebase->setIntervention([
            'id' => strtolower(str_replace(' ', '_', $intervention->nom)),
            'name' => $intervention->nom,
            'price' => $intervention->prix,
            'duration_minutes' => $intervention->duree_minutes,
        ]);

        return response()->json($intervention);
    }

    // Supprimer une intervention
    public function destroy(Intervention $intervention)
    {
        $idFirebase = strtolower(str_replace(' ', '_', $intervention->nom));

        $intervention->delete();

        // Supprimer de Firebase
        $this->firebase->deleteIntervention($idFirebase);

        return response()->json(['message' => 'Supprimé']);
    }
}
