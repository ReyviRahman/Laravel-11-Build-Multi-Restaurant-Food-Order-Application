<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index() {
        return view('frontend.index.index');
    }

    public function UserDashboard() {
        return view('frontend.dashboard.dashboard');
    }

    public function UserProfileUpdate(Request $request) {
        $id = Auth::guard('web')->id();
        $data = User::findOrFail($id);

        // update field basic
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        // simpan nama file lama
        $oldPhotoPath = $data->photo;

        if ($request->hasFile('photo')) {
            // simpan ke storage/app/public/user_images
            $path = $request->file('photo')->store('user_images', 'public');

            // update field di database
            $data->photo = $path;

            // hapus foto lama kalau ada
            if ($oldPhotoPath && Storage::disk('public')->exists($oldPhotoPath)) {
                Storage::disk('public')->delete($oldPhotoPath);
            }
        }

        $data->save();
        $notification = [
            'message'    => 'Profile updated successfully',
            'alert_type' => 'success',
        ];
        return redirect()->back()->with($notification);
    }

    public function UserLogout() {
        Auth::guard('web')->logout();
        return redirect()->route('login')->with('success', 'Logout Successfully');
    }

    public function UserChangePassword() {
        return view('frontend.dashboard.change_password');
    }

    public function UserPasswordUpdate(Request $request) {
        $user = Auth::guard('web')->user();
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            $notification = [
                'message'    => 'Old Password Does Not Match!',
                'alert_type' => 'error',
            ];
            return redirect()->back()->with($notification);
        }

        User::whereId($user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notification = [
            'message'    => 'Password Change Successfully!',
            'alert_type' => 'success',
        ];
        return redirect()->back()->with($notification);
    }
}
