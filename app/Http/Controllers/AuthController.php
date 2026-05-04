<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Ensure this matches your User model

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // 1. We add 'status' => 1 to the credentials array.
        // This tells Laravel: "Only log this user in if their status is 1"
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'status'   => 1 // 1 = Active, 0 = Inactive
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Route based on Designation
            if ($user->designation == 'Project Manager') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('employee.dashboard');
            }
        }

        // 2. If login fails, check if it's because the account is disabled
        // to show a specific message.
        $user = User::where('username', $request->username)->first();
        if ($user && $user->status == 0) {
            return back()->with('error', 'Your access has been disabled. Please contact the administrator.');
        }

        return back()->with('error', 'Invalid Username or Password');
    }


   // Ensure this is at the very top of the file

public function showChangePassword() {
    return view('auth.change_password');
}

public function updatePassword(Request $request) {
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed', 
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'The current password does not match our records.']);
    }

    $user->password = Hash::make($request->new_password);
    
    // If your table is tbl_employeemaster and you have confirm_password column, update it too
    if (isset($user->confirm_password)) {
        $user->confirm_password = Hash::make($request->new_password);
    }

    $user->save();

    return back()->with('success', 'Password changed successfully!');
}


    public function logout() {
        Auth::logout();
        return redirect('/');
    }
}