<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['read' => 'boolean'];
    }
}
