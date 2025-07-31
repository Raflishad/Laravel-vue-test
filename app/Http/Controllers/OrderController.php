<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Order;
use App\Models\Product;
use App\Exports\OrderExport;
use App\Imports\OrderImport;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('product');
        if ($request->search) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }   

        if ($request->sort_by) {
            $query->orderBy($request->sort_by, $request->sort_dir ?? 'asc');
        }

        return Inertia::render('Orders/Index', [
            'orders' => $query->get(),
            'products' => Product::all(),
            'filters' => $request->only(['search', 'sort_by', 'sort_dir']),
            'audits' => Audit::where('auditable_type', Order::class)
            ->with('user')
            ->latest()
            ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        $order = Order::create([
            'product_id'    => $product->id,
            'product_name'  => $product->name,         // snapshot
            'product_price' => $product->price,        // snapshot
            'quantity'      => $request->quantity,
            'total_price'   => $product->price * $request->quantity,
        ]);

        return redirect('/orders');
    }

    public function audit(Order $order)
    {
        return Inertia::render('Orders/AuditTrail', [
            'audits' => $order->audits()->with('user')->latest()->get(),
        ]);
    }

    public function export()
    {
        return Excel::download(new OrderExport, 'orders.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls']);
        Excel::import(new OrderImport, $request->file('file'));
        return redirect('/orders');
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        $order->update([
            'product_id' => $request->product_id,
            'product_name' => $product->name,
            'product_price' => $product->price,
            'quantity' => $request->quantity,
            'total_price' => $product->price * $request->quantity,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Order berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->back()->with('success', 'Order berhasil dihapus.');
    }
}
