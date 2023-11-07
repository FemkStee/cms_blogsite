<?php

namespace Femst\Like\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Statamic\Facades\YAML;

class LikeController extends Controller {

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        session_start();
        $rating = $request->input('rating');
        $entry_id = $request->input('entry_id');
        $user_id = $_SESSION['user_id'];
        
        // Lees de bestaande YAML-gegevens
        $yamlPath = resource_path('like/likes.yaml');
        $yamlData = Yaml::parse(file_get_contents($yamlPath));
        
        // Controleer of er al beoordelingen zijn, anders maak een lege array aan
        if (!isset($yamlData['ratings'])) {
            $yamlData['ratings'] = [];
        }

        // geef elke beoordeling een uniek id
        $id = uniqid();
        
        // Voeg de nieuwe beoordeling en id toe aan de array
        $yamlData['ratings'][] = [
            'id' => $id,
            'rating' => $rating,
            'entry_id' => $entry_id,
            'user_id' => $user_id
        ];
    
        // Zet de bijgewerkte instellingen terug naar YAML en deze opslaan in bestand
        $yamlUpdatedData = Yaml::dump($yamlData);
        file_put_contents($yamlPath, $yamlUpdatedData);

        return back();
    }
}
