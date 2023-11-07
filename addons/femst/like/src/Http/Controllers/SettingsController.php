<?php

namespace Femst\Like\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Statamic\Entries\Entry;
use Statamic\Facades\YAML;

class SettingsController extends Controller {
    public function index()
    {
        // Lees de bestaande YAML-gegevens
        $yamlPath = resource_path('like/likes.yaml');
        $yamlData = Yaml::parse(file_get_contents($yamlPath));
        $ratings = $yamlData['ratings'];
        $emojis = ['','😡','😕','😐','😊','🔥'];
        $ratingsUrls = [];

        foreach ($ratings as $rating) {
            $url = $rating['url'];
            if (!in_array($url, $ratingsUrls)) {
                $ratingsUrls[] = $url;
            }
        }
        
        return view('like::cp.settings', [
            'ratings' => $ratings,
            'emojis' => $emojis,
        ]);
    }
}