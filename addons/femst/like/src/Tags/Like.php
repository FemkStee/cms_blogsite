<?php

namespace Femst\Like\Tags;

use Femst\Like\Functions\Functions;
use Statamic\Facades\YAML;
use Statamic\Tags\Tags;

class Like extends Tags
{
    /**
     * The {{ like }} tag.
     *
     * @return string|array
     */

    public function index()
    {
        // haal entry id en ratings van de huidige entry op
        $entry_id = $this->context->get('id')->value();
        $yamlPath = resource_path('like/likes.yaml');
        $yamlData = Yaml::parse(file_get_contents($yamlPath));

        // vars
        $user_id = 0; // id van huidige user
        $averageRating = 0; // gemiddelde rating van entry
        $valueRatingUser = null; // rating van huidige user als deze al een rating heeft gegeven
        
        session_start();

        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        } else {
            // voorkomen dat er dubbele user id's worden aangemaakt
            if(isset($yamlData['ratings'])) {
                $ratings = $yamlData['ratings'];
                $allUserIds = [];
                foreach ($ratings as $rating) {
                    $allUserIds[] = $rating['user_id'];
                }
            }            
            
            do {
                $_SESSION['user_id'] = uniqid();
                $user_id = $_SESSION['user_id'];
            } while (in_array($user_id, $allUserIds));
            
        }

        // ratings van huidige entry + gemiddelde rating + rating van huidige user
        if (isset($yamlData['ratings'])) {
            $ratings = $yamlData['ratings'];
            $ratingsFromEntry = [];
            foreach ($ratings as $rating) {
                if ($rating['entry_id'] === $entry_id) {
                    $ratingsFromEntry[] = $rating;
                }
            }
            $averageRating = Functions::getAverageRating($ratingsFromEntry);
            $valueRatingUser = Functions::checkIfIdExistsInYaml($user_id, $ratingsFromEntry);
        }

        return view('like::like', $this->context, compact('averageRating', 'valueRatingUser'));
    }

    /**
     * The {{ like:example }} tag.
     *
     * @return string|array
     */
    public function example()
    {
        //
    }
}
