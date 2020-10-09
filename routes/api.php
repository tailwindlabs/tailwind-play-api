<?php

use App\Models\Playground;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/playgrounds/{playground:uuid}', fn (Playground $playground) => $playground);

Route::post('/playgrounds', function (Request $request) {
    $payload = $request->validate([
        'html' => 'required|string',
        'css' => 'required|string',
        'config' => 'required|string',
    ]);

    $hash = md5(implode('.', $payload));

    return Playground::firstOrCreate(
        ['hash' => $hash],
        array_merge($payload, [
            'uuid' => Str::random(10),
        ])
    );
})->middleware(['throttle:api']);
