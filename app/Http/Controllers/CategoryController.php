<?php

namespace App\Http\Controllers;

use App\Actions\Category\CreateCategory;
use App\Actions\Category\UpdateCategory;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(perPage: 5)
            ->through(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'created_at' => $category->created_at,
                'author' => ['name' => $category->author->name ?? 'N/A'],
            ]);

        return Inertia::render('admin/categories/Index', [
            'categories' => $categories,
            'filters' => request()->only(['search', 'per_page']),
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/categories/Create');
    }

    public function store(StoreCategoryRequest $request, CreateCategory $action): RedirectResponse
    {
        try {
            $category = $action->execute(
                $request->validated(),
                $request->user()
            );

            return to_route('admin.categories.index');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong: '.$e->getMessage()])
                ->withInput();
        }
    }

    public function edit(Category $category)
    {
        return Inertia::render('admin/categories/Edit', [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
            ],
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category, UpdateCategory $action): RedirectResponse
    {
        try {
            $action->execute(
                $category,
                $request->validated(),
                $request->user()
            );

            return to_route('admin.categories.index');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong: '.$e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Category $category): RedirectResponse
    {
        try {
            // Simple Laravel delete
            $category->delete();

            return to_route('admin.categories.index');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong: '.$e->getMessage()])
                ->withInput();
        }
    }
}
