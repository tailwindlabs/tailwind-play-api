<?php

use App\Models\Playground;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/playgrounds/{playground:uuid}', fn (Playground $playground) => $playground);

Route::post('/playgrounds', function (Request $request) {
    $request->validate([
        'html' => 'required|string|max:500000',
        'css' => 'required|string|max:500000',
        'config' => 'required|string|max:500000',
        'version' => 'string|in:1,2',
    ]);

    $hash = md5(implode('.', $request->only(['html', 'css', 'config', 'version'])));

    return Playground::firstOrCreate(
        ['hash' => $hash],
        array_merge($request->only(['html', 'css', 'config']), [
            'version' => $request->input('version', '1'),
            'uuid' => Str::random(10),
        ])
    );
})->middleware(['throttle:api']);
