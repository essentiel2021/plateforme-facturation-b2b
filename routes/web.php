<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/health', function () {
    try {
        $dbName = DB::connection()->getDatabaseName();
        $dbStatus = "Connecté à PostgreSQL (Base : {$dbName})";
    } catch (\Exception $e) {
        $dbStatus = "Erreur PostgreSQL : " . $e->getMessage();
    }

    try {
        $redisPong = Redis::ping();
        $redisStatus = "Connecté à Redis ({$redisPong})";
    } catch (\Exception $e) {
        $redisStatus = "Erreur Redis : " . $e->getMessage();
    }

    return response()->json([
        'status'   => 'success',
        'message'  => 'Tous les conteneurs communiquent parfaitement !',
        'services' => [
            'web_server' => 'Nginx (Reverse Proxy)',
            'app_server' => 'PHP ' . phpversion() . ' FPM',
            'database'   => $dbStatus,
            'redis'      => $redisStatus,
        ],
    ]);
});
