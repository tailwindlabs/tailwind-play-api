<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playground extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'version' => $this->version === null ? '1' : $this->version
        ]);
    }
}
