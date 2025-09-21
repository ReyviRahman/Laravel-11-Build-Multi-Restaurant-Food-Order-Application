<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function ClientLogin() {
        return view('client.client_login');
    }
    public function ClientRegister() {
        return view('client.client_register');
    }

    public function ClientRegisterSubmit(Request $request) {
        $request->validate([
            'name' => 'required|string|max:200',
            'email' => 'required|unique:clients',
        ]);

        Client::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'status' => '0'
        ]);

        $notification = [
            'message'    => 'Client registered successfully',
            'alert_type' => 'success',
        ];

        return redirect()->route('client.login')->with($notification);
    }

    public function ClientLoginSubmit(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $check = $request->all();
        $data = [
            'email' => $check['email'],
            'password' => $check['password']
        ];
        if (Auth::guard('client')->attempt($data)) {
            return redirect()->route('client.dashboard')->with('success', 'Login Successfully');
        } else {
            return redirect()->route('client.login')->with('error', 'Invalid Credentials');
        }

    }

    public function ClientDashboard() {
        return view('client.index');
    }

    public function ClientProfile() {
        $id = Auth::guard('client')->id();
        $profileData = Client::find($id);
        return view('client.client_profile', compact('profileData'));
    }

    public function ClientProfileUpdate(Request $request) {
        $id = Auth::guard('client')->id();
        $data = client::findOrFail($id);

        // update field basic
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        // simpan nama file lama
        $oldPhotoPath = $data->photo;

        if ($request->hasFile('photo')) {
            // simpan ke storage/app/public/client_images
            $path = $request->file('photo')->store('client_images', 'public');

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

    public function ClientChangePassword() {
        $id = Auth::guard('client')->id();
        $profileData = Client::find($id);
        return view('client.client_change_password', compact('profileData'));
    }

    public function ClientPasswordUpdate(Request $request) {
        $client = Auth::guard('client')->user();
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if (!Hash::check($request->old_password, $client->password)) {
            $notification = [
                'message'    => 'Old Password Does Not Match!',
                'alert_type' => 'error',
            ];
            return redirect()->back()->with($notification);
        }

        Client::whereId($client->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notification = [
            'message'    => 'Password Change Successfully!',
            'alert_type' => 'success',
        ];
        return redirect()->back()->with($notification);
    }

    public function ClientLogout() {
        Auth::guard('client')->logout();
        return redirect()->route('client.login')->with('success', 'Logout Successfully');
    }
}
