<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RankHistory extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
    ];

    public function amazonListing(): BelongsTo
    {
        return $this->belongsTo(AmazonListing::class);
    }
}
