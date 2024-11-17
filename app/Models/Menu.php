<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'menu_items');
    }

    public function createBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
