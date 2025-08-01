<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Exports\CategoryExport;
use App\Imports\CategoryImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Models\Audit;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }   

        if ($request->sort_by) {
            $query->orderBy($request->sort_by, $request->sort_dir ?? 'asc');
        }

        return Inertia::render('Categories/Index', [
            'categories' => $query->get(),
            'filters' => $request->only(['search', 'sort_by', 'sort_dir']),
            'audits' => Audit::where('auditable_type', Category::class)
                ->with('user')
                ->latest()
                ->get(),
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

    public function audit(Category $category)
    {
        return Inertia::render('Categories/AuditTrail', [
            'audits' => $category->audits()->with('user')->latest()->get(),
        ]);
    }
}

