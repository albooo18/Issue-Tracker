<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'color',
    ];

    public function issues(): BelongsToMany
    {
        return $this->belongsToMany(Issue::class, 'issue_tag');
    }
}
