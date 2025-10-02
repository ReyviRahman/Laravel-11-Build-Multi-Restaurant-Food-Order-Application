<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $client = Auth::guard('client')->user(); // ambil model Client
        $menus = $client->menus; // akses relasi

        return view('client.backend.menu.index', compact('menus'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('client.backend.menu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());

            // Generate nama unik
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Resize gambar
            $img = $manager->read($image)->resize(300, 300);

            // Simpan ke storage/app/public/menu
            $path = 'menu_images/' . $name_gen;
            Storage::disk('public')->put($path, (string) $img->encode());

            $client = Auth::guard('client')->user();
            // Simpan path ke DB
            Menu::create([
                'menu_name' => $request->menu_name,
                'image' => $path,
                'client_id' => $client->id
            ]);
        }

        $notification = [
            'message' => 'Menu Inserted Successfully',
            'alert_type' => 'success'
        ];

        return redirect()->route('menus.index')->with($notification);
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
    public function edit(string $id)
    {
        $menu = Menu::find($id);
        return view('client.backend.menu.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $menu = Menu::findOrFail($id);

        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());

            // Generate nama unik
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Resize gambar
            $img = $manager->read($image)->resize(300, 300);

            // Simpan ke storage/app/public/menu
            $path = 'menu_images/' . $name_gen;
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }

            Storage::disk('public')->put($path, (string) $img->encode());

            // Simpan path ke DB
            $menu->update([
                'menu_name' => $request->menu_name,
                'image' => $path, 
            ]);
        } else {
            $menu->update([
                'menu_name' => $request->menu_name
            ]);
        }

        $notification = [
            'message' => 'Menu Updated Successfully',
            'alert_type' => 'success'
        ];

        return redirect()->route('menus.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $menu = Menu::findOrFail($id);
        if ($menu->image && Storage::disk('public')->exists($menu->image)) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        $notification = [
            'message' => 'Menu Deleted Successfully',
            'alert_type' => 'success'
        ];

        return redirect()->route('menus.index')->with($notification);
    }
}
