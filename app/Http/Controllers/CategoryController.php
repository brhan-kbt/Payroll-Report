<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with(['parent', 'children', 'posts'])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($data['name']) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('categories', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        Category::create($data);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'posts.user']);
        $posts = $category->posts()->with('user')->paginate(10);

        return view('categories.show', compact('category', 'posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $categories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($data['name']) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('categories', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has posts
        if ($category->posts()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete category with posts. Please move or delete posts first.');
        }

        // Check if category has children
        if ($category->children()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete category with subcategories. Please move or delete subcategories first.');
        }

        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * API: Display a listing of categories.
     */
    public function apiIndex()
    {
        $categories = Category::with(['parent', 'children', 'posts'])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Categories retrieved successfully.'
        ]);
    }

    /**
     * API: Display the specified category.
     */
    public function apiShow(Category $category)
    {
        $category->load(['parent', 'children', 'posts.user']);

        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Category retrieved successfully.'
        ]);
    }
}
