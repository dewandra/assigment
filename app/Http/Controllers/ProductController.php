<?php

namespace App\Http\Controllers;

use App\Exports\ProductExport;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::all();
        $cat = Category::all();
        return view('assignment.dashboard.product', compact('product', 'cat'));
    }
    public function create()
    {
        $cat = Category::all();
        return view('assignment.dashboard.addproduct', compact('cat'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,png|max:100',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'hb' => 'required|numeric',
            'hj' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        $hb = $request->hb;
        $hj = $hb + ($hb * 0.30);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
        }

        $data = [
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'category_id' => $request->category_id,
            'image' => $path ?? null,
            'name' => $request->name,
            'hb' => $request->hb,
            'hj' => $hj,
            'stok' => $request->stok,
            'category' => $request->category,
        ];

        Product::create($data);
        return response()->json([
            'status' => 200
        ]);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $cat = Category::all();
        return view('assignment.dashboard.editproduct', compact('product', 'cat'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,png|max:100',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'hb' => 'required|numeric',
            'hj' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        $product = Product::find($request->id);

        if (!$product) {
            return response()->json([
                'status' => 404,
                "message" => "Product not found"
            ], 404);
        }

        $hb = $request->hb;
        $hj = $hb + ($hb * 0.30);

        if ($request->hasFile('image')) {

            if ($product->image && file_exists(storage_path('app/public' . $product->image)))
                unlink(storage_path('app/public' . $product->image));

            $path = $request->file('image')->store('images', 'public');
            $product->image = $path;
        }

        $product->name = $request->name;
        $product->hb = $request->hb;
        $product->hj = $hj;
        $product->category_id = $request->category_id;
        $product->stok = $request->stok;

        $product->save();

        return response()->json([
            'status' => 200,
            'message'  => 'product update successfully',
            'data' => $product
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
        ]);

        $product = Product::find($request->id);

        if ($product) {
            if ($product->image && file_exists(storage_path('app/public/' . $product->image))) {
                unlink(storage_path('app/public/' . $product->image));
            }

            // Hapus produk
            $product->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Product deleted successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Failed to delete product.',
            ]);
        }
    }

    public function export(Request $request)
    {
        // Ambil parameter pencarian dan kategori (berdasarkan nama kategori)
        $search = $request->get('search', ''); // Default kosong jika tidak ada
        $category_name = $request->get('category_name', ''); // Mengambil nama kategori

        // Query untuk mengambil data produk dengan filter
        $productsQuery = Product::query();

        // Filter berdasarkan nama produk
        if ($search) {
            $productsQuery->where('name', 'like', '%' . $search . '%');
        }

        // Filter berdasarkan nama kategori
        if ($category_name) {
            // Menambahkan filter untuk kategori berdasarkan nama kategori
            $productsQuery->whereHas('category', function ($query) use ($category_name) {
                $query->where('name', 'like', '%' . $category_name . '%');
            });
        }

        // Ambil data produk yang sudah difilter
        $products = $productsQuery->get();

        // Ekspor data produk yang sudah difilter ke Excel
        return Excel::download(new ProductExport($products), 'products.xlsx');
    }
}
