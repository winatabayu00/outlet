<?php

use App\Concerns\Medias\MediaConcern;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\HasMedia;

if (!function_exists('getDifferenceDistance')) {

    /**
     * @param array $coordinate1
     * @param array $coordinate2
     * @return array
     */
    function getDifferenceDistance(
        array $coordinate1,
        array $coordinate2,
    ): array
    {
        $lat1 = (float)$coordinate1['latitude'];
        $lat2 = (float)$coordinate2['latitude'];

        $lon1 = (float)$coordinate1['longitude'];
        $lon2 = (float)$coordinate2['longitude'];

        $earthRadiusKm = 6371; // Earth's radius in kilometers
        $earthRadiusM = 6371000; // Earth's radius in meters
        $earthRadiusCm = 637100000; // Earth's radius in centimeters
        $earthRadiusMm = 6371000000; // Earth's radius in millimeters

        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDiff / 2) * sin($lonDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distanceKm = $earthRadiusKm * $c;
        $distanceM = $earthRadiusM * $c;
        $distanceCm = $earthRadiusCm * $c;
        $distanceMm = $earthRadiusMm * $c;

        return [
            'kilometers' => $distanceKm,
            'meters' => $distanceM,
            'centimeters' => $distanceCm,
            'Millimeters' => $distanceMm,
        ];
    }
}

if (!function_exists('generateRandomCoordinatesWithinDistance')) {

    /**
     * @param $centerLat
     * @param $centerLon
     * @param $maxDistanceMeters
     * @return array
     */
    function generateRandomCoordinatesWithinDistance($centerLat, $centerLon, $maxDistanceMeters): array
    {
        $earthRadius = 6371000; // Earth's radius in meters

        $maxDistanceKm = $maxDistanceMeters / 1000; // Convert to kilometers

        // Generate a random angle and distance within the specified range
        $angle = deg2rad(mt_rand(0, 360));
        $distance = $maxDistanceKm * sqrt(mt_rand(0, 100) / 100);

        // Calculate new latitude and longitude using generated values
        $newLat = rad2deg(asin(sin(deg2rad($centerLat)) * cos($distance / $earthRadius) + cos(deg2rad($centerLat)) * sin($distance / $earthRadius) * cos($angle)));
        $newLon = $centerLon + rad2deg(atan2(sin($angle) * sin($distance / $earthRadius) * cos(deg2rad($centerLat)), cos($distance / $earthRadius) - sin(deg2rad($centerLat)) * sin(deg2rad($newLat))));

        return ['latitude' => $newLat, 'longitude' => $newLon];
    }
}

if (!function_exists('linkedMediaCollection')) {

    /**
     * @param HasMedia $model
     * @param string|Request $source
     * @param string $inputName
     * @return MediaConcern
     */
    function linkedMediaCollection(
        HasMedia $model,
        string|Request $source,
        string $inputName
    ): MediaConcern
    {
        return new MediaConcern(model: $model, source: $source,inputName: $inputName);
    }
}
