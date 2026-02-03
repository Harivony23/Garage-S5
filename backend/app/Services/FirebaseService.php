<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $projectId;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $path = storage_path('firebase/serviceAccountKey.json');
        if (file_exists($path)) {
            $config = json_decode(file_get_contents($path), true);
            $this->projectId = $config['projectId'] ?? env('FIREBASE_PROJECT_ID');
            $this->apiKey = $config['apiKey'] ?? env('FIREBASE_API_KEY');
        } else {
            $this->projectId = env('FIREBASE_PROJECT_ID');
            $this->apiKey = env('FIREBASE_API_KEY');
        }
        $this->baseUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents";
    }

    /**
     * Formate un tableau PHP simple en structure "fields" Firestore
     */
    protected function formatFirestoreFields(array $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            if ($key === 'id') continue;
            
            if (is_int($value)) {
                $fields[$key] = ['integerValue' => (string)$value];
            } elseif (is_float($value) || is_numeric($value)) {
                $fields[$key] = ['doubleValue' => (float)$value];
            } elseif (is_bool($value)) {
                $fields[$key] = ['booleanValue' => $value];
            } else {
                $fields[$key] = ['stringValue' => (string)$value];
            }
        }
        return ['fields' => $fields];
    }

    /**
     * Parse une structure "fields" Firestore en tableau PHP simple
     */
    protected function parseFirestoreFields(array $document)
    {
        $data = [];
        $nameParts = explode('/', $document['name']);
        $data['id'] = end($nameParts);

        if (isset($document['fields'])) {
            foreach ($document['fields'] as $key => $value) {
                if (isset($value['stringValue'])) $data[$key] = $value['stringValue'];
                elseif (isset($value['integerValue'])) $data[$key] = (int)$value['integerValue'];
                elseif (isset($value['doubleValue'])) $data[$key] = (float)$value['doubleValue'];
                elseif (isset($value['booleanValue'])) $data[$key] = (bool)$value['booleanValue'];
                elseif (isset($value['timestampValue'])) $data[$key] = $value['timestampValue'];
            }
        }
        return $data;
    }

    // Récupérer toutes les interventions
    public function getInterventions()
    {
        $url = "{$this->baseUrl}/interventions?key={$this->apiKey}";

        try {
            $response = Http::get($url);

            if ($response->failed()) {
                throw new \Exception("Firestore REST Error: " . $response->body());
            }

            $json = $response->json();
            $interventions = [];

            if (isset($json['documents'])) {
                foreach ($json['documents'] as $doc) {
                    $interventions[] = $this->parseFirestoreFields($doc);
                }
            }

            return $interventions;
        } catch (\Exception $e) {
            Log::error("Erreur récupération Firestore: " . $e->getMessage());
            throw $e;
        }
    }

    // Créer ou mettre à jour une intervention
    public function setIntervention(array $intervention)
    {
        if (!isset($intervention['id'])) {
            throw new \InvalidArgumentException("L'intervention doit avoir un ID pour Firestore.");
        }

        $id = $intervention['id'];
        $url = "{$this->baseUrl}/interventions/{$id}?key={$this->apiKey}";

        try {
            $response = Http::patch($url, $this->formatFirestoreFields($intervention));

            if ($response->failed()) {
                throw new \Exception("Firestore REST Error: " . $response->body());
            }

            return $this->parseFirestoreFields($response->json());
        } catch (\Exception $e) {
            Log::error("Erreur Firestore REST pour l'intervention {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    // Supprimer une intervention
    public function deleteIntervention(string $id)
    {
        $url = "{$this->baseUrl}/interventions/{$id}?key={$this->apiKey}";
        
        return Http::delete($url);
    }
}
