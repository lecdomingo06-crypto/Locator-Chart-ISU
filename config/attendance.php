<?php

return [
    'geofence' => [
        'enabled' => filter_var(env('ATTENDANCE_GEOFENCE_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
        'latitude' => env('ATTENDANCE_GEOFENCE_LATITUDE'),
        'longitude' => env('ATTENDANCE_GEOFENCE_LONGITUDE'),
        'radius_meters' => (float) env('ATTENDANCE_GEOFENCE_RADIUS_METERS', 75),
        'max_accuracy_meters' => (float) env('ATTENDANCE_GEOFENCE_MAX_ACCURACY_METERS', 150),
    ],
];
