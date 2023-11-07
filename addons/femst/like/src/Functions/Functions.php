<?php
// LikeFunctions.php
namespace Femst\Like\Functions;

use Statamic\Facades\YAML;

class Functions {
    
    public static function getAverageRating($ratings) {
        if (is_array($ratings)) {
            $ratingsRating = array_column($ratings, 'rating');
            $ratingAverage = count($ratingsRating) > 0 ? array_sum($ratingsRating) / count($ratingsRating) : 0;
            $ratingAverage = round($ratingAverage);
            return $ratingAverage;
        }

        return 0; // Geen beoordelingen gevonden, geef een standaardwaarde terug

    }

    public static function checkIfIdExistsInYaml($user_id, $ratings) {
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
}
