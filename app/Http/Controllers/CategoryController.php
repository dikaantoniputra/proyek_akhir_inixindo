<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    
    public function index()
    {
        $categories = Category::withCount('tasks')->orderBy('name')->get();
        return view('categories.index', compact('categories'));
    }

    
    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        
        $baseSlug = $validated['slug'];
        $count = 1;
        while (Category::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $baseSlug . '-' . $count++;
        }

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori baru berhasil dibuat.');
    }

    
    public function update(CategoryRequest $request, Category $category)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $baseSlug = $validated['slug'];
        $count = 1;
        while (Category::where('slug', $validated['slug'])->where('id', '!=', $category->id)->exists()) {
            $validated['slug'] = $baseSlug . '-' . $count++;
        }

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
