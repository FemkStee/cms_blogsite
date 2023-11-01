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

    public function getAverageRating()
    {
        $settingsPath = resource_path('like/likes.yaml');
        $existingSettings = YAML::parse(file_get_contents($settingsPath));

        if (isset($existingSettings['ratings']) && is_array($existingSettings['ratings'])) {
            $ratings = array_column($existingSettings['ratings'], 'rating');
            $averageRating = count($ratings) > 0 ? array_sum($ratings) / count($ratings) : 0;

            return $averageRating;
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

    public function checkIfIdExistsInYaml()
    {
        // Lees de YAML-gegevens uit het opgegeven pad
        $yamlPath = resource_path('like/likes.yaml');
        $yamlData = YAML::parse(file_get_contents($yamlPath));
        $user_id = $this->context->get('id')->value();
        $ratingForDesiredId = null;
        $allIds = [];

        if (isset($yamlData['ratings']) && is_array($yamlData['ratings'])) {
            // Loop door de ratings en controleer of een "id" overeenkomt met $this->context->get('id')
            foreach ($yamlData['ratings'] as $rating) {
                $allIds[] = $rating['id'];
            }
        }

        if (in_array($user_id, $allIds)) {
            if (isset($yamlData['ratings']) && is_array($yamlData['ratings'])) {
                foreach ($yamlData['ratings'] as $rating) {
                    if (isset($rating['id']) && $rating['id'] === $user_id) {
                        $ratingForDesiredId = $rating['rating'];
                        break; // We hebben de overeenkomst gevonden, dus we kunnen de lus verlaten
                    }
                }
            }
            return $ratingForDesiredId;
        }

        return $ratingForDesiredId;
    }

    public function index()
    {
        $averageRating = $this->getAverageRating();
        $roundedAverageRating = floor($averageRating);
        $valueRatingUser = $this->checkIfIdExistsInYaml();
        return view('like::like', $this->context, compact('averageRating', 'roundedAverageRating', 'valueRatingUser'));
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
