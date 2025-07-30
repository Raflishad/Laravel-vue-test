<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductFileController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:512', // max 500 KB
        ]);

        $path = $request->file('file')->store('product_files');

        $product->files()->create([
            'file_path' => $path,
        ]);

        return redirect()->back()->with('success', 'File berhasil diunggah.');
    }

    public function destroy(ProductFile $file)
    {
        Storage::delete($file->file_path);
        $file->delete();

        return redirect()->back()->with('success', 'File dihapus.');
    }
}

