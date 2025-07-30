<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Exports\CategoryExport;
use App\Imports\CategoryImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Categories/Index', [
            'categories' => Category::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Category::create($request->only('name', 'description'));

        return redirect('/categories');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $category->update($request->only('name', 'description'));

        return redirect('/categories');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect('/categories');
    }

    public function export()
    {
        return Excel::download(new CategoryExport, 'categories.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls']);
        Excel::import(new CategoryImport, $request->file('file'));
        return redirect('/categories');
    }

}

