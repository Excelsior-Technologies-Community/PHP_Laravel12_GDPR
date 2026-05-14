<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Dashboard + Search + Pagination
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->oldest()
            ->paginate(5);

        return view('dashboard', compact('users'));
    }

    /**
     * Delete User
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'User deleted successfully.');
    }
}