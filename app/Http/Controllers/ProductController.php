<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use OwenIt\Auditing\Models\Audit;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'files']);

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->sort_by) {
            $query->orderBy($request->sort_by, $request->sort_dir ?? 'asc');
        }

        return Inertia::render('Products/Index', [
            'products' => $query->get(),
            'categories' => Category::all(),
            'filters' => $request->only(['search', 'category_id', 'sort_by', 'sort_dir']),
                        'audits' => Audit::where('auditable_type', Product::class)
            ->with('user')
            ->latest()
            ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric',
            'is_active'     => 'boolean',
            'tags'          => 'nullable|array',
            'release_date'  => 'nullable|date',
        ]);

        Product::create($request->all());

        return redirect('/products');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric',
            'is_active'     => 'boolean',
            'tags'          => 'nullable|array',
            'release_date'  => 'nullable|date',
        ]);

        $product->update($request->all());

        return redirect('/products');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect('/products');
    }

    public function audit(Product $product)
    {
        return Inertia::render('Products/AuditTrail', [
            'audits' => $product->audits()->with('user')->latest()->get(),
        ]);
    }
  
    public function export(Request $request)
    {
        $fields = $request->input('fields', []);

        $filename = 'products_' . now()->timestamp . '.xlsx';

        Excel::store(new ProductExport($fields), $filename, 'local');

        return response()->download(storage_path("app/{$filename}"));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new ProductImport, $request->file('file'));

        return redirect('/products')->with('success', 'Data berhasil diimpor.');
    }
}
