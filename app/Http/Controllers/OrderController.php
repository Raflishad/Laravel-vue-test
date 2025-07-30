<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use OwenIt\Auditing\Models\Audit;
use App\Exports\OrderExport;
use App\Imports\OrderImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index()
    {
        return Inertia::render('Orders/Index', [
            'orders' => Order::latest()->get(),
            'products' => Product::all(),
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
}
