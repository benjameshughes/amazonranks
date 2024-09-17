<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AmazonListing extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
    ];

    public function rankHistories(): HasMany
    {
        return $this->hasMany(RankHistory::class);
    }
}
