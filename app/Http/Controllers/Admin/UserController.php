<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\AuditLog;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->filled('search'), fn ($query) => $query->where(function ($query) use ($request) {
                $term = '%'.$request->string('search').'%';
                $query->where('name', 'like', $term)->orWhere('email', 'like', $term);
            }))
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->string('role')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role', 'status']),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', ['branches' => Branch::ordered()->get()]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create([
            ...$request->safe()->except('password'),
            'password' => Hash::make($request->validated('password')),
        ]);

        AuditLog::record('user.created', "Created user {$user->email} with role {$user->role}.");

        return redirect()->route('admin.users.index')->with('success', 'User account created successfully.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', ['editUser' => $user, 'branches' => Branch::ordered()->get()]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        if ($user->is($request->user()) && $request->validated('role') !== $user->role) {
            return back()->with('error', 'You cannot change your own role.');
        }

        if ($user->hasRole(User::ROLE_SUPER_ADMIN)
            && $request->validated('role') !== User::ROLE_SUPER_ADMIN
            && User::where('role', User::ROLE_SUPER_ADMIN)->count() <= 1
        ) {
            return back()->with('error', 'At least one super admin account must remain.');
        }

        $originalRole = $user->role;
        $originalStatus = $user->status;

        $user->fill($request->safe()->except('password'));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->validated('password'));
        }

        $user->save();

        if ($originalRole !== $user->role || $originalStatus !== $user->status) {
            AuditLog::record(
                'user.updated',
                "Updated {$user->email}: role {$originalRole} \u{2192} {$user->role}, status {$originalStatus} \u{2192} {$user->status}."
            );
        }

        return redirect()->route('admin.users.index')->with('success', 'User account updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->hasRole(User::ROLE_SUPER_ADMIN) && User::where('role', User::ROLE_SUPER_ADMIN)->count() <= 1) {
            return back()->with('error', 'At least one super admin account must remain.');
        }

        AuditLog::record('user.deleted', "Deleted user {$user->email} (role: {$user->role}).");

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User account deleted.');
    }
}
