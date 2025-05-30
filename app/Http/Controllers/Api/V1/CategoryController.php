<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->with([
                'parentCategory:id,name',
                'createdBy:id,name',
                'media'
            ])
            ->paginate($request->get('per_page', 12));

        return CategoryResource::collection($categories);
    }

    public function store(CategoryRequest $request)
    {

        $category = Category::create([
            'name' => $request->name,
            'category_id' => $request->parent_category,
            'created_by' => auth()->id(),
        ]);

        if ($request->hasFile('image')) {
            $category->addMediaFromRequest('image')
                ->toMediaCollection('categories');
        }

        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        $category->load([
            'parentCategory:id,name',
            'createdBy:id,name',
        ]);

        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update([
            'name' => $request->name,
            'category_id' => $request->parent_category,
        ]);

        if ($request->hasFile('image')) {
            $category->addMediaFromRequest('image')
                ->toMediaCollection('categories');
        }

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->noContent();
    }
}
