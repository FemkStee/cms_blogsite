<?php

namespace Femst\Like\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Statamic\Entries\Entry;
use Statamic\Facades\YAML;

class LikeController extends Controller {

    public function store(Request $request)
    {
        session_start();
        $rating = $request->input('rating');
        $id = $request->input('entry_id');
        $user_id = $_SESSION['user_id'];
        
        // Lees de bestaande YAML-gegevens
        $yamlPath = resource_path('like/likes.yaml');
        $yamlData = Yaml::parse(file_get_contents($yamlPath));
        
        // Controleer of er al beoordelingen zijn, anders maak een lege array aan
        if (!isset($yamlData['ratings'])) {
            $yamlData['ratings'] = [];
        }
        
        // Voeg de nieuwe beoordeling en id toe aan de array
        $yamlData['ratings'][] = [
            'rating' => $rating,
            'id' => $id,
            'user_id' => $user_id
        ];
    
        // Zet de bijgewerkte instellingen terug naar YAML en deze opslaan in bestand
        $yamlUpdatedData = Yaml::dump($yamlData);
        file_put_contents($yamlPath, $yamlUpdatedData);

        return back();
    }
}
