<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class parameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'icon',
        'icon_gray',
    ];
}
