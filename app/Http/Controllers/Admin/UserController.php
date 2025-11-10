<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::where('id', '!=', 1)
            ->where('id', '!=', Auth::id())
            ->latest()
            ->get();

        $users->transform(function ($user) {
            return [
                'id' => $user->id,
                'image' => '<a href="#" data-toggle="modal" data-target="#imageModal" onclick="showImage(\'' . $user->name . '\', \'' . asset('storage/user/' . ($user->image_name ?? 'default-image.jpg')) . '\')">
                                <img class="default-img" src="' . asset('storage/user/' . ($user->image_name ?? 'default-image.jpg')) . '" width="60">
                            </a>',
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
            ];
        });

        return view('admin.user.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.user.create');
    }

    public function register(Request $request): RedirectResponse
    {
        $imageName = "";

        if ($request->hasFile('image_name')) {
            $image = $request->file('image_name')->store('user', 'public');
            $imageName = basename($image);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'password' => 'string|min:8|confirmed|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
        ]);

        User::create([
            'name' => $validatedData['name'],
            'address' => $validatedData['address'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'password' => Hash::make($validatedData['password']),
            'is_admin' => 0,
            'image_name' => $imageName,
        ]);

        return redirect()->route('login')->with('success', 'Berhasil membuat akun.');
    }

    public function store(Request $request): RedirectResponse
    {
        $imageName = null;

        if ($request->hasFile('image_name')) {
            $image = $request->file('image_name')->store('user', 'public');
            $imageName = basename($image);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'password' => 'string|min:8|confirmed|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
            'role' => 'required|in:0,1',
        ]);

        User::create([
            'name' => $validatedData['name'],
            'address' => $validatedData['address'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'password' => Hash::make($validatedData['password']),
            'is_admin' => $validatedData['role'],
            'image_name' => $imageName,
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function show($id)
    {
        if ((int)$id === 1) {
            return redirect()->route('admin.user.index')->with('error', 'User tidak ditemukan');
        }

        $user = User::findOrFail($id);
        return view('admin.user.show', compact('user'));
    }

    public function edit($id)
    {
        if ((int)$id === 1) {
            return redirect()->route('admin.user.index')->with('error', 'User tidak ditemukan');
        }

        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        if ((int) $id === 1) {
            return redirect()->route('admin.user.index')->with('error', 'User tidak ditemukan');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:15',
            'password' => 'nullable|string|min:8|confirmed|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
            'role' => 'required|in:0,1',
        ]);

        $user = User::findOrFail($id);

        $validatedData['is_admin'] = $validatedData['role'];

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        if ($request->hasFile('image_name')) {
            if ($user->image_name) {
                Storage::delete('public/user/' . $user->image_name);
            }

            $image = $request->file('image_name')->store('user', 'public');
            $imageName = basename($image);
            $user->image_name = $imageName;
        }

        $user->update($validatedData);

        return redirect()->route('admin.user.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === 1) {
            return redirect()->route('admin.user.index')->with('error', 'User tidak ditemukan');
        }

        if ($user->image_name) {
            Storage::delete('public/user/' . $user->image_name);
        }

        $user->delete();

        return redirect()->back()->with([
            'message' => 'Data berhasil dihapus',
            'alert-type' => 'danger'
        ]);
    }

    public function editProfile()
    {
        return view('admin.user.profile');
    }

    public function updateProfile(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'password' => 'nullable|string|min:8|confirmed|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        if ($request->hasFile('image_name')) {
            if ($user->image_name) {
                Storage::delete('public/user/' . $user->image_name);
            }

            $image = $request->file('image_name')->store('user', 'public');
            $imageName = basename($image);
            $user->image_name = $imageName;
        }

        $user->update($validatedData);

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
