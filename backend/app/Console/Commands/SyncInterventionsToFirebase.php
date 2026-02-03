<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Intervention;
use App\Services\FirebaseService;

class SyncInterventionsToFirebase extends Command
{
    protected $signature = 'firebase:sync-interventions';
    protected $description = 'Synchronise toutes les interventions existantes vers Firebase';

    protected FirebaseService $firebase;

    public function __construct(FirebaseService $firebase)
    {
        parent::__construct();
        $this->firebase = $firebase;
    }

    public function handle()
    {
        $interventions = Intervention::all();

        foreach ($interventions as $intervention) {
            $idFirebase = strtolower(str_replace(' ', '_', $intervention->nom));

            try {
                $this->firebase->setIntervention([
                    'id' => $idFirebase,
                    'name' => $intervention->nom,
                    'price' => (float) $intervention->prix,
                    'duration_minutes' => (int) $intervention->duree_minutes,
                ]);

                $this->info("Intervention '{$intervention->nom}' (ID: {$idFirebase}) synchronisée avec Firestore !");
            } catch (\Exception $e) {
                $this->error("Échec de la synchronisation pour '{$intervention->nom}': " . $e->getMessage());
            }

        }

        $this->info('Fin du processus de synchronisation.');
        return 0;

    }
}
