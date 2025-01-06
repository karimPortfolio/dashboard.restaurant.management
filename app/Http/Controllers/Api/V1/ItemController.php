<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    
    public function index(Request $request)
    {
        $items = Item::query()
        ->with([
            'createdBy:id,name',
            'updatedBy:id,name',
        ])
        ->get();

        return ItemResource::collection($items);
    }

    public function store(ItemRequest $request)
    {
        $item = Item::create([
            'name' => $request->validated('name'),
            'price' => $request->validated('price'),
            'is_available' => $request->validated('is_available'),
            'created_by' => auth()->id(),
        ]);

        return ItemResource::make($item);
    }

    public function show(Item $item)
    {
        $item->load([
            'createdBy:id,name',
            'updatedBy:id,name',
            'menus:id,name',
            'ingredients:id,name,price,category_id',
            'ingredients.category:id,name',
        ]);

        return ItemResource::make($item);
    }

    public function update(ItemRequest $request, Item $item)
    {
        $item->update([
            'name' => $request->validated('name'),
            'price' => $request->validated('price'),
            'is_available' => $request->validated('is_available'),
            'updated_by' => auth()->id(),
        ]);

        return ItemResource::make($item);
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return response()->noContent();
    }

}
