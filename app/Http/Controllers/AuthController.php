<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Show the admin login form
     */
    public function showAdminLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Show the welcome page after login
     */
    public function showWelcome()
    {
        return view('admin.welcome');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.login')
                        ->withErrors($validator)
                        ->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Redirect based on user role
            $user = Auth::user();
            if ($user->role === 'super_admin' || $user->role === 'admin') {
                return redirect()->intended(route('admin.welcome'));
            }
            
            return redirect()->intended('/');
        }

        return redirect()->route('admin.login')
                    ->withErrors(['email' => 'Invalid credentials'])
                    ->withInput();
    }

    /**
     * Handle admin login request
     */
    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.login')
                        ->withErrors($validator)
                        ->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Check if user has admin privileges
            $user = Auth::user();
            if ($user->role === 'super_admin' || $user->role === 'admin') {
                return redirect()->intended(route('admin.welcome'));
            } else {
                // If not admin, logout and redirect with error
                Auth::logout();
                return redirect()->route('admin.login')
                            ->withErrors(['email' => 'You do not have admin privileges'])
                            ->withInput();
            }
        }

        return redirect()->route('admin.login')
                    ->withErrors(['email' => 'Invalid credentials'])
                    ->withInput();
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|string|min:6',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('profile')
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $validator->validated();

        // Check current password if new password is provided
        if ($request->filled('new_password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return redirect()->route('profile')
                            ->withErrors(['current_password' => 'Current password is incorrect'])
                            ->withInput();
            }
            $data['password'] = Hash::make($request->new_password);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                \Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        // Remove password fields that shouldn't be updated
        unset($data['current_password'], $data['new_password'], $data['new_password_confirmation']);

        $user->update($data);

        if ($request->expectsJson() || $request->header('Content-Type') === 'application/x-www-form-urlencoded; charset=UTF-8' || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null
            ]);
        }

        return redirect()->route('profile')
                    ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('profile')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('profile')
                        ->withErrors(['current_password' => 'Current password is incorrect'])
                        ->withInput();
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('profile')
                    ->with('success', 'Password updated successfully!');
    }
}