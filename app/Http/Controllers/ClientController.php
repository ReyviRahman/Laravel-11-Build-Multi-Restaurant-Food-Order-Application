<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        return view('client.client_dashboard');
    }
}
