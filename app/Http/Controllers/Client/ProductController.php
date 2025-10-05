<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Menu;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $client = Auth::guard('client')->user(); // ambil model Client
        $products = $client->products()
            ->with(['category', 'menu', 'city'])
            ->latest()
            ->get();


        return view('client.backend.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $categories = Category::latest()->get();
        $menus = Menu::latest()->get();
        $cities = City::latest()->get();
        return view('client.backend.product.create', compact('categories', 'menus', 'cities'));
    }

    private function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        // cek apakah slug sudah ada
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    private function generateUniqueCode()
    {
        do {
            $code = 'PRD' . rand(10000, 99999);
        } while (Product::where('code', $code)->exists());

        return $code;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $client = Auth::guard('client')->user();

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'city_id'        => 'required|exists:cities,id',
            'menu_id'        => 'required|exists:menus,id',
            'code'           => 'nullable|string|unique:products,code',
            'qty'            => 'nullable|integer|min:0',
            'size'           => 'nullable|string|max:50',
            'price'          => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg',
            'most_populer'   => 'nullable|boolean',
            'best_seller'    => 'nullable|boolean',
            'status'         => 'required|in:active,inactive',
        ]);

        $imagePath = null;
        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());

            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image)->resize(300, 300);

            $imagePath = 'product_images/' . $name_gen;
            Storage::disk('public')->put($imagePath, (string) $img->encode());
        }

        // Generate code otomatis jika kosong
        $productCode = $validated['code'] ?? $this->generateUniqueCode();

        $product = Product::create([
            'name'           => $validated['name'],
            'slug'           => $this->generateUniqueSlug($validated['name']),
            'category_id'    => $validated['category_id'],
            'city_id'        => $validated['city_id'],
            'menu_id'        => $validated['menu_id'],
            'client_id'      => $client->id,
            'code'           => $productCode,
            'qty'            => $validated['qty'] ?? 0,
            'size'           => $validated['size'] ?? null,
            'price'          => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'image'          => $imagePath,
            'most_populer'   => $validated['most_populer'] ?? false,
            'best_seller'    => $validated['best_seller'] ?? false,
            'status'         => $validated['status'],
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::latest()->get();
        $menus = Menu::latest()->get();
        $cities = City::latest()->get();
        return view('client.backend.product.edit', compact('product', 'categories', 'menus', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());

            // Generate nama unik
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Resize gambar
            $img = $manager->read($image)->resize(300, 300);

            // Simpan ke storage/app/public/product
            $path = 'product_images/' . $name_gen;
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            Storage::disk('public')->put($path, (string) $img->encode());

            // Simpan path ke DB
            $product->update([
                'name' => $request->name,
                'slug' => $this->generateUniqueSlug($request->name),
                'category_id' => $request->category_id,
                'city_id' => $request->city_id,
                'menu_id' => $request->menu_id,
                'qty' => $request->qty,
                'size' => $request->size,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'most_populer' => $request->most_populer,
                'best_seller' => $request->best_seller,
                'status' => $request->status,
                'image' => $path,
            ]);
        } else {
            $product->update([
                'name' => $request->name,
                'slug' => $this->generateUniqueSlug($request->name),
                'category_id' => $request->category_id,
                'city_id' => $request->city_id,
                'menu_id' => $request->menu_id,
                'qty' => $request->qty,
                'size' => $request->size,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'most_populer' => $request->most_populer ?? false,
                'best_seller' => $request->best_seller ?? false,
                'status' => $request->status,
            ]);
        }

        $notification = [
            'message' => 'Product Updated Successfully',
            'alert_type' => 'success'
        ];

        return redirect()->route('products.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        $notification = [
            'message' => 'Product Deleted Successfully',
            'alert_type' => 'success'
        ];

        return redirect()->route('products.index')->with($notification);
    }
}
