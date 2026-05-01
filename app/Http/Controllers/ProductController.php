<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        $products = Product::latest()->get();
        return view('product-master', compact('products'));
    }

    public function store(Request $request) {
        Product::create($request->all());
        return redirect()->back()->with('success', 'Product added to list successfully!');
    }

    public function update(Request $request, Product $product) {
        $product->update($request->all());
        return redirect()->back()->with('success', 'Product updated!');
    }

    public function destroy(Product $product) {
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted!');
    }

    public function bulkDelete(Request $request) {
        $ids = $request->ids;
        Product::whereIn('id', explode(",", $ids))->delete();
        return response()->json(['status' => true, 'message' => "Selected products deleted."]);
    }
}