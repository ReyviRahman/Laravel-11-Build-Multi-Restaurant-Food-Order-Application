<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CategoryController extends Controller
{
    public function AllCategory() {
        $category = Category::latest()->get();
        return view('admin.backend.category.all_category', compact('category'));
    }   

    public function AddCategory() {
        return view('admin.backend.category.add_category');
    }

    public function StoreCategory(Request $request) {
        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());

            // Generate nama unik
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Resize gambar
            $img = $manager->read($image)->resize(300, 300);

            // Simpan ke storage/app/public/category
            $path = 'category_images/' . $name_gen;
            Storage::disk('public')->put($path, (string) $img->encode());

            // Simpan path ke DB
            Category::create([
                'category_name' => $request->category_name,
                'image' => $path, 
            ]);
        }

        $notification = [
            'message' => 'Category Inserted Successfully',
            'alert_type' => 'success'
        ];

        return redirect()->route('all.categories')->with($notification);
    }
}
