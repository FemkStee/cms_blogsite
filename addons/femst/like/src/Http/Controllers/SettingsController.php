<?php

namespace Femst\Like\Http\Controllers;

use App\Http\Controllers\Controller;
use Femst\Like\Functions\Functions;
use Illuminate\Http\Request;
use Statamic\Facades\YAML;

class SettingsController extends Controller {
    public function index() {
        // Lees de bestaande YAML-gegevens
        $yamlPath = resource_path('like/likes.yaml');
        $yamlData = Yaml::parse(file_get_contents($yamlPath));
        $allEntryIds = [];
        $totalRatings = 0;
        $averageRating = 0;
        
        if(isset($yamlData['ratings'])) {
            foreach ($yamlData['ratings'] as $rating) {
                $allEntryIds[] = $rating['entry_id'];
            }
            $totalRatings = count($yamlData['ratings']);
            $averageRating = Functions::getAverageRating($yamlData['ratings']);
        }

        return view('like::cp.settings', [
            'allEntryIds' => $allEntryIds,
            'totalRatings' => $totalRatings,
            'averageRating' => $averageRating,
        ]);
    }

    public function allEntryIds($slug) {
        // Lees de bestaande YAML-gegevens
        $yamlPath = resource_path('like/likes.yaml');
        $yamlData = Yaml::parse(file_get_contents($yamlPath));
        
        // ratings die bij de entry horen
        $ratings = [];
        foreach ($yamlData['ratings'] as $rating) {
            if ($rating['entry_id'] == $slug) {
                $ratings[] = $rating;
            }
        }
        
        return view('like::cp.list', [
            'slug' => $slug,
            'ratings' => $ratings
        ]);
    }

    public function edit($slug) {
        // Lees de bestaande YAML-gegevens
        $yamlPath = resource_path('like/likes.yaml');
        $yamlData = Yaml::parse(file_get_contents($yamlPath));
        $ratingId = $slug;

        // Filter 
        $rating = [];
        foreach ($yamlData['ratings'] as $rating) {
            if ($rating['id'] == $ratingId) {
                $rating[] = $rating;
            }
        }

        $valueRatingUser = $rating['rating'];

        return view('like::cp.edit', [
            'rating' => $rating,
            'valueRatingUser' => $valueRatingUser,
            'csrf_field' => csrf_field(),
        ]);
    }

    public function update(Request $request) {
        $ratingValue = $request->input('rating');
        $entry_id = $request->input('entry_id');
        $user_id = $request->input('user_id');
        $id = $request->input('id');

        // Lees de bestaande YAML-gegevens
        $yamlPath = resource_path('like/likes.yaml');
        $yamlData = Yaml::parse(file_get_contents($yamlPath));

        // Update YAML data
        foreach ($yamlData['ratings'] as $key => $rating) {
            if ($rating['id'] == $id) {
                $yamlData['ratings'][$key]['rating'] = $ratingValue;
                $yamlData['ratings'][$key]['user_id'] = $user_id;
                $yamlData['ratings'][$key]['entry_id'] = $entry_id;
            } 
        }

        // Schrijf de nieuwe YAML-gegevens
        file_put_contents($yamlPath, YAML::dump($yamlData));

        return redirect('/cp/like');
    }

    public function delete($slug) {
        // Lees de bestaande YAML-gegevens
        $yamlPath = resource_path('like/likes.yaml');
        $yamlData = Yaml::parse(file_get_contents($yamlPath));

        // Delete YAML data
        foreach ($yamlData['ratings'] as $key => $rating) {
            if ($rating['id'] == $slug) {
                unset($yamlData['ratings'][$key]);
            }
        }

        // Schrijf de nieuwe YAML-gegevens
        file_put_contents($yamlPath, YAML::dump($yamlData));

        return redirect('/cp/like');
    }

    public function add() {
        return view('like::cp.edit', [
            'csrf_field' => csrf_field(),
            'unique_id' => uniqid(),
        ]);
    }

    public function store(Request $request) {
        
        $validatedData = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'entry_id' => 'required',
            'user_id' => 'required',
            'id' => 'required',
        ]);

        $ratingValue = $request->input('rating');
        $entry_id = $request->input('entry_id');
        $user_id = $request->input('user_id');
        $id = $request->input('id');

        // Lees de bestaande YAML-gegevens
        $yamlPath = resource_path('like/likes.yaml');
        $yamlData = Yaml::parse(file_get_contents($yamlPath));

        // Controleer of er al beoordelingen zijn, anders maak een lege array aan
        if (!isset($yamlData['ratings'])) {
            $yamlData['ratings'] = [];
        }
        
        // Voeg de nieuwe beoordeling en id toe aan de array
        $yamlData['ratings'][] = [
            'id' => $id,
            'rating' => $ratingValue,
            'entry_id' => $entry_id,
            'user_id' => $user_id
        ];
    
        // Zet de bijgewerkte instellingen terug naar YAML en deze opslaan in bestand
        $yamlUpdatedData = Yaml::dump($yamlData);
        file_put_contents($yamlPath, $yamlUpdatedData);

        return back();
    }

}