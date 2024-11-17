<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function menus():BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'menu_items');
    }

    public function ingredients():BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'item_ingredients');
    }
}
