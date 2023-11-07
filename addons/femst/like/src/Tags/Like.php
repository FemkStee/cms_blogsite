<?php

namespace Femst\Like\Tags;

use Statamic\Facades\YAML;
use Statamic\Tags\Tags;

class Like extends Tags
{
    /**
     * The {{ like }} tag.
     *
     * @return string|array
     */

    public function getAverageRating($ratings)
    {
        if (is_array($ratings)) {
            $ratingsRating = array_column($ratings, 'rating');
            $ratingAverage = count($ratingsRating) > 0 ? array_sum($ratingsRating) / count($ratingsRating) : 0;
            $ratingAverage = round($ratingAverage);
            return $ratingAverage;
        }

        return 0; // Geen beoordelingen gevonden, geef een standaardwaarde terug
    }

    public function getPercentageOfReviewsByRating($targetRating)
    {
        $settingsPath = resource_path('like/likes.yaml');
        $existingSettings = YAML::parse(file_get_contents($settingsPath));

        if (isset($existingSettings['ratings']) && is_array($existingSettings['ratings'])) {
            $totalReviews = count($existingSettings['ratings']);
            $targetRatingCount = 0;

            foreach ($existingSettings['ratings'] as $rating) {
                if ($rating['rating'] == $targetRating) {
                    $targetRatingCount++;
                }
            }

            if ($totalReviews > 0) {
                $percentage = ($targetRatingCount / $totalReviews) * 100;
                return $percentage;
            }
        }

        return 0; // Geen beoordelingen gevonden, geef 0% terug
    }

    public function checkIfIdExistsInYaml($user_id, $ratings)
    {
        $ratingForDesiredId = null;
        $ratingValueForDesiredId = null;

        if (isset($ratings)) {
            $ratingsForCurrentID = [];
            foreach ($ratings as $rating) {
                if ($rating['user_id'] == $user_id) {
                    $ratingsForCurrentID[] = $rating;
                }
            }
            if (count($ratingsForCurrentID) > 0) {
                $ratingValueForDesiredId = $ratingsForCurrentID[0]['rating'];
            }
        }

        return $ratingValueForDesiredId;
    }

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
                $_SESSION['user_id'] = rand(1, 1000000);
                $user_id = $_SESSION['user_id'];
            } while (in_array($user_id, $allUserIds));
            
        }

        // ratings van huidige entry + gemiddelde rating + rating van huidige user
        if (isset($yamlData['ratings'])) {
            $ratings = $yamlData['ratings'];
            $ratingsFromEntry = [];
            foreach ($ratings as $rating) {
                if ($rating['id'] === $entry_id) {
                    $ratingsFromEntry[] = $rating;
                }
            }
            $averageRating = $this->getAverageRating($ratingsFromEntry);
            $valueRatingUser = $this->checkIfIdExistsInYaml($user_id, $ratingsFromEntry);
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
