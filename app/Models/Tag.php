<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    public function conditions(): MorphToMany
    {
        return $this->morphedByMany(Condition::class, 'taggable');
    }

    public function medications(): MorphToMany
    {
        return $this->morphedByMany(Medication::class, 'taggable');
    }

    public function procedures(): MorphToMany
    {
        return $this->morphedByMany(Procedure::class, 'taggable');
    }

    public function articles(): MorphToMany
    {
        return $this->morphedByMany(Article::class, 'taggable');
    }
}
