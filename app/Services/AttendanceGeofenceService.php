<?php

namespace App\Services;

class AttendanceGeofenceService
{
    public function enabled(): bool
    {
        return (bool) config('attendance.geofence.enabled', false);
    }

    public function settings(): array
    {
        return [
            'enabled' => $this->enabled(),
            'latitude' => $this->latitude(),
            'longitude' => $this->longitude(),
            'radius_meters' => $this->radiusMeters(),
            'max_accuracy_meters' => $this->maxAccuracyMeters(),
        ];
    }

    public function check(?float $latitude, ?float $longitude, ?float $accuracy): array
    {
        if (! $this->enabled()) {
            return [
                'allowed' => true,
                'status' => 'disabled',
                'distance_meters' => null,
                'message' => null,
            ];
        }

        if (! $this->hasCenter()) {
            return [
                'allowed' => false,
                'status' => 'not_configured',
                'distance_meters' => null,
                'message' => 'Attendance location check is not configured. Contact the administrator.',
            ];
        }

        if ($latitude === null || $longitude === null) {
            return [
                'allowed' => false,
                'status' => 'missing_location',
                'distance_meters' => null,
                'message' => 'Please allow location access before timing in.',
            ];
        }

        if ($accuracy !== null && $accuracy > $this->maxAccuracyMeters()) {
            return [
                'allowed' => false,
                'status' => 'weak_accuracy',
                'distance_meters' => null,
                'message' => 'Your location accuracy is too weak. Move near an open area, turn on GPS, then try again.',
            ];
        }

        $distance = $this->distanceMeters(
            $latitude,
            $longitude,
            $this->latitude(),
            $this->longitude(),
        );

        if ($distance > $this->radiusMeters()) {
            return [
                'allowed' => false,
                'status' => 'outside_radius',
                'distance_meters' => $distance,
                'message' => 'You are outside the allowed time-in area.',
            ];
        }

        return [
            'allowed' => true,
            'status' => 'verified',
            'distance_meters' => $distance,
            'message' => null,
        ];
    }

    public function distanceMeters(float $fromLatitude, float $fromLongitude, float $toLatitude, float $toLongitude): float
    {
        $earthRadiusMeters = 6371000;

        $fromLat = deg2rad($fromLatitude);
        $toLat = deg2rad($toLatitude);
        $latDelta = deg2rad($toLatitude - $fromLatitude);
        $lngDelta = deg2rad($toLongitude - $fromLongitude);

        $angle = sin($latDelta / 2) ** 2
            + cos($fromLat) * cos($toLat) * sin($lngDelta / 2) ** 2;

        return round($earthRadiusMeters * 2 * atan2(sqrt($angle), sqrt(1 - $angle)), 2);
    }

    private function hasCenter(): bool
    {
        return $this->latitude() !== null && $this->longitude() !== null;
    }

    private function latitude(): ?float
    {
        $latitude = config('attendance.geofence.latitude');

        return is_numeric($latitude) ? (float) $latitude : null;
    }

    private function longitude(): ?float
    {
        $longitude = config('attendance.geofence.longitude');

        return is_numeric($longitude) ? (float) $longitude : null;
    }

    private function radiusMeters(): float
    {
        return max(1, (float) config('attendance.geofence.radius_meters', 75));
    }

    private function maxAccuracyMeters(): float
    {
        return max(1, (float) config('attendance.geofence.max_accuracy_meters', 150));
    }
}
