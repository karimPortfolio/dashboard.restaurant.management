<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [];


    public function parentCategory():BelongsTo
    {
        return $this->belongsTo(self::class, 'category_id');
    }

    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // public function productImage(): Attribute
    // {
    //     if ($this->getFirstMediaUrl('product_images')) {
    //         return $this->getFirstMediaUrl('product_images');
    //     }

    //     return Attribute::get()
    // }
}
