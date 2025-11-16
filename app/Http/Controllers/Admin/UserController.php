<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.form');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:super_admin,admin,user',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|string|in:active,inactive',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.users.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        User::create($data);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:super_admin,admin,user',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|string|in:active,inactive',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.users.edit', $user)
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $validator->validated();

        // Only update password if provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                \Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deletion of current user
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                           ->with('error', 'You cannot delete your own account!');
        }

        // Prevent deletion of super admin by non-super admin
        if ($user->role === 'super_admin' && auth()->user()->role !== 'super_admin') {
            return redirect()->route('admin.users.index')
                           ->with('error', 'You cannot delete a super admin account!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User deleted successfully!');
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus(User $user)
    {
        // Prevent deactivating current user
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot deactivate your own account!'], 400);
        }

        // Prevent deactivating super admin by non-super admin
        if ($user->role === 'super_admin' && auth()->user()->role !== 'super_admin') {
            return response()->json(['error' => 'You cannot deactivate a super admin account!'], 400);
        }

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return response()->json([
            'success' => true,
            'status' => $user->status,
            'message' => 'User status updated successfully!'
        ]);
    }

    /**
     * Reset user password
     */
    public function resetPassword(User $user)
    {
        // Generate random password
        $newPassword = str()->random(12);
        
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        // In a real application, you would send this password via email
        // For now, we'll return it in the response (not recommended for production)
        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully!',
            'new_password' => $newPassword // Remove this in production
        ]);
    }

    /**
     * Bulk actions for users
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:activate,deactivate,delete',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $action = $request->input('action');
        $userIds = $request->input('user_ids');
        
        // Remove current user from bulk actions
        $userIds = array_filter($userIds, function($id) {
            return $id != auth()->id();
        });

        if (empty($userIds)) {
            return response()->json(['error' => 'No valid users selected for bulk action!'], 400);
        }

        $count = 0;

        switch ($action) {
            case 'activate':
                $count = User::whereIn('id', $userIds)->update(['status' => 'active']);
                break;
                
            case 'deactivate':
                // Prevent deactivating super admins by non-super admin
                if (auth()->user()->role !== 'super_admin') {
                    $userIds = User::whereIn('id', $userIds)
                                 ->where('role', '!=', 'super_admin')
                                 ->pluck('id')
                                 ->toArray();
                }
                $count = User::whereIn('id', $userIds)->update(['status' => 'inactive']);
                break;
                
            case 'delete':
                // Prevent deleting super admins by non-super admin
                if (auth()->user()->role !== 'super_admin') {
                    $userIds = User::whereIn('id', $userIds)
                                 ->where('role', '!=', 'super_admin')
                                 ->pluck('id')
                                 ->toArray();
                }
                $count = User::whereIn('id', $userIds)->delete();
                break;
        }

        return response()->json([
            'success' => true,
            'message' => "Bulk action completed successfully! {$count} users affected.",
            'count' => $count
        ]);
    }

    /**
     * Search users
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $role = $request->input('role');
        $status = $request->input('status');

        $users = User::query();

        if ($query) {
            $users->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('phone', 'LIKE', "%{$query}%");
            });
        }

        if ($role) {
            $users->where('role', $role);
        }

        if ($status) {
            $users->where('status', $status);
        }

        $users = $users->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Export users to Excel
     */
    public function exportExcel()
    {
        // This would require creating a UserExport class
        // For now, return a simple response
        return response()->json(['message' => 'Export functionality will be implemented']);
    }

    /**
     * Export users to PDF
     */
    public function exportPdf()
    {
        $users = User::all();
        $pdf = Pdf::loadView('admin.users.pdf', compact('users'));
        return $pdf->download('users_list.pdf');
    }
}