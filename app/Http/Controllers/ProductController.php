<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use DataTables;

class ProductController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Product::select('*'))
                ->addColumn('action', 'product.product-action')
                ->addColumn('image', 'product.image')
                ->rawColumns(['action', 'image'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('product.home');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $productId = $request->product_id;
        $image = $request->hidden_image;

        if ($files = $request->file('image')) {
            // Delete old file if it exists
            if ($image && File::exists('public/product/' . $image)) {
                File::delete('public/product/' . $image);
            }

            // Insert new file
            $destinationPath = 'public/product/'; // upload path
            $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);
            $image = $profileImage;
        }

        $product = Product::find($productId) ?? new Product();
        $product->title = $request->title;
        $product->category = $request->category;
        $product->price = $request->price;
        $product->image = $image;

        $product->save();

        return response()->json($product);
    }

    public function edit($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Delete the image file if it exists
        if ($product->image && File::exists('public/product/' . $product->image)) {
            File::delete('public/product/' . $product->image);
        }

        $product->delete();

        return response()->json(['success' => 'Product deleted successfully']);
    }
}
