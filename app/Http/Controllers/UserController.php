<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Support\InputSanitizer;

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
                'image_url' => asset('storage/user/' . ($user->image_name ?? 'default-image.jpg')),
                'name' => $user->name,
                'email' => $user->email,
                'role_label' => $user->role_label,
            ];
        });

        return view('user.index', compact('users'));
    }

    public function create(): View
    {
        return view('user.create');
    }

    public function register(Request $request): RedirectResponse
    {
        $this->sanitizeUserInput($request);

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
            'role' => 'staf',
            'image_name' => $imageName,
        ]);

        return redirect()->route('login')->with('success', 'Berhasil membuat akun');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->sanitizeUserInput($request);

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
            'role' => 'required|in:admin,staf,komisaris,direktur_utama',
        ]);

        User::create([
            'name' => $validatedData['name'],
            'address' => $validatedData['address'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'password' => Hash::make($validatedData['password']),
            'is_admin' => $validatedData['role'] === 'admin' ? 1 : 0,
            'role' => $validatedData['role'],
            'image_name' => $imageName,
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
    }

    public function show($id)
    {
        if ((int) $id === 1) {
            return redirect()->route('user.index')->with('error', 'User tidak ditemukan');
        }

        $user = User::with(['createdBy', 'updatedBy'])->findOrFail($id);
        return view('user.show', compact('user'));
    }

    public function edit($id)
    {
        if ((int) $id === 1) {
            return redirect()->route('user.index')->with('error', 'User tidak ditemukan');
        }

        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->sanitizeUserInput($request);

        if ((int) $id === 1) {
            return redirect()->route('user.index')->with('error', 'User tidak ditemukan');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:15',
            'password' => 'nullable|string|min:8|confirmed|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
            'role' => 'required|in:admin,staf,komisaris,direktur_utama',
        ]);

        $user = User::findOrFail($id);

        $validatedData['is_admin'] = $validatedData['role'] === 'admin' ? 1 : 0;

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

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === 1) {
            return redirect()->route('user.index')->with('error', 'User tidak ditemukan');
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
        return view('user.profile');
    }

    public function updateProfile(Request $request)
    {
        $this->sanitizeUserInput($request);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'password' => 'nullable|string|min:8|confirmed|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
        ]);

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

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui');
    }

    private function sanitizeUserInput(Request $request): void
    {
        $request->merge([
            'name' => InputSanitizer::clean($request->name) ?? '',
            'address' => InputSanitizer::clean($request->address) ?? '',
        ]);
    }
}
