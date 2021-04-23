<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Event extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = 'event';
    protected $fillable = [
        'title',
        'slug',
        'content',
    ];
}
