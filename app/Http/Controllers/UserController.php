<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            function ($request, $next) {
                abort_if(auth()->user()->role_id != 1, 403);
                return $next($request);
            },
        ];
    }

    public function index(): View
    {
        $users = User::with(['divisionRelation'])->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $divisions = Division::orderBy('name')->get();
        $roles = [
            1 => 'Super Admin',
            2 => 'Manager',
            3 => 'Corporate User'
        ];
        return view('admin.users.create', compact('divisions', 'roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'employee_id' => 'required|string|max:50|unique:users,employee_id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'division_id' => 'required|exists:divisions,id',
            'role_id' => 'required|in:1,2,3',
        ]);

        User::create([
            'employee_id' => $request->employee_id,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'division_id' => $request->division_id,
            'role_id' => $request->role_id,
            'is_active' => true,
        ]);

        return redirect()->route('users.index')->with('success', 'User account provisioned successfully.');
    }

    public function edit(User $user): View
    {
        $divisions = Division::orderBy('name')->get();
        $roles = [
            1 => 'Super Admin',
            2 => 'Manager',
            3 => 'Corporate User'
        ];
        return view('admin.users.edit', compact('user', 'divisions', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'employee_id' => 'required|string|max:50|unique:users,employee_id,' . $user->id,
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'division_id' => 'required|exists:divisions,id',
            'role_id' => 'required|in:1,2,3',
        ]);

        $data = [
            'employee_id' => $request->employee_id,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'division_id' => $request->division_id,
            'role_id' => $request->role_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User account updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if(auth()->id() == $user->id, 403, 'Self-termination is blocked.');
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User identity purged successfully.');
    }
}