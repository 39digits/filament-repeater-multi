<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory;

    // To keep this example simple, we'll allow all fields to be mass assignable
    protected $guarded = [];

    public function supplierProductVariants(): HasMany
    {
        return $this->hasMany(SupplierProductVariant::class);
    }
}
