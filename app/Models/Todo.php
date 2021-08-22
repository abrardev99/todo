<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class Todo extends Model
{
    use HasFactory;
    protected $guarded = [];

    // relationships

    public function user(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
