<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    // To keep this example simple, we'll allow all fields to be mass assignable
    protected $guarded = [];

    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(Variant::class);
    }
}
