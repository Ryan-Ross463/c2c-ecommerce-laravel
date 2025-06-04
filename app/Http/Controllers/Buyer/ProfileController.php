<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{    public function index()
    {

        if (!auth()->check() || auth()->user()->role_id != 1) {
            return redirect()->route('login');
        }

        return view('buyer.profile.index');
    }    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::user()->user_id . ',user_id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();
        
        if ($request->hasFile('profile_image')) {            // Check if old profile picture exists and delete it
            if ($user->profile_image) {
                $oldFilePath = public_path('uploads/profile_pictures/' . $user->profile_image);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            
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
            
            // Create uploads directory if it doesn't exist
            $uploadPath = public_path('uploads/profile_pictures');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move($uploadPath, $imageName);
            $user->profile_image = $imageName;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->bio = $request->bio;
        $user->save();

        return redirect()->route('buyer.profile')
            ->with('success', 'Profile updated successfully!');
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

        return redirect()->route('buyer.profile')
            ->with('success', 'Password updated successfully!');
    }
}
