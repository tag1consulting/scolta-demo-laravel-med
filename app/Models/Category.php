<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'type', 'description', 'icon', 'sort_order'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
