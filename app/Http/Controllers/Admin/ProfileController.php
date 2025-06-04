<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile.index');
    }    public function update(Request $request)
    {
        $admin = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->user_id . ',user_id',            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
        ];        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            
            // Validate file type and size
            $allowedTypes = ['jpg', 'jpeg', 'png'];
            $fileExtension = strtolower($image->getClientOriginalExtension());
            $fileSize = $image->getSize(); // in bytes
            $maxSize = 2 * 1024 * 1024; // 2MB in bytes
            
            if (!in_array($fileExtension, $allowedTypes)) {
                return redirect()->back()->with('error', 'Profile picture must be a JPG, JPEG, or PNG file.');
            }
            
            if ($fileSize > $maxSize) {
                return redirect()->back()->with('error', 'Profile picture must be less than 2MB.');
            }
            
            // Check if old profile picture exists and delete it
            if (isset($admin->profile_image)) {
                $oldFilePath = public_path('uploads/profile_pictures/' . $admin->profile_image);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            
            // Create uploads directory if it doesn't exist
            $uploadPath = public_path('uploads/profile_pictures');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move($uploadPath, $imageName);
            $updateData['profile_image'] = $imageName;
        }

        DB::table('users')->where('user_id', $admin->user_id)->update($updateData);

        return Redirect::back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('admin.profile')
            ->with('success', 'Password updated successfully!');
    }
}
