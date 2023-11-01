<?php

namespace Femst\Like\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Statamic\Entries\Entry;
use Statamic\Facades\YAML;

class LikeController extends Controller {
    public function index()
    {
        $somedata = 'foobar';

        return view('like::cp.settings', [
            'somedata' => $somedata
        ]);
    }

    public function store(Request $request)
    {
        $rating = $request->input('rating');
        $id = $request->input('entry_id');
    
        // Lees de bestaande YAML-gegevens
        $settingsPath = resource_path('like/likes.yaml');
        $existingSettings = Yaml::parse(file_get_contents($settingsPath));
    
        // Controleer of er al beoordelingen zijn, anders maak een lege array aan
        if (!isset($existingSettings['ratings'])) {
            $existingSettings['ratings'] = [];
        }
    
        // Voeg de nieuwe beoordeling en id toe aan de array
        $existingSettings['ratings'][] = [
            'rating' => $rating,
            'id' => $id,
        ];
    
        // Zet de bijgewerkte instellingen terug naar YAML
        $updatedSettings = Yaml::dump($existingSettings);
    
        // Instellingen opslaan in het bestand
        file_put_contents($settingsPath, $updatedSettings);
        return back();
    }
    public function destroy(Request $request, $id)
    {
    }
}
