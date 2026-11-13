<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invitation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    // Roles that the logged-in user is allowed to invite.
    private function allowedRoles($user)
    {
        $role = $user->role->slug;

        if ($role === 'super-admin') {
            return Role::where('slug', 'admin')->get();
        }

        if ($role === 'admin') {
            return Role::whereIn('slug', [
                'admin',
                'member',
            ])->get();
        }

        return collect();
    }

    // Display invitations.
    public function index()
    {
        $user = auth()->user();

        $query = Invitation::with([
            'company',
            'role',
            'inviter',
        ])->latest();

        if ($user->role->slug === 'admin') {
            $query->where('company_id', $user->company_id);
        } elseif ($user->role->slug !== 'super-admin') {
            abort(403);
        }

        $invitations = $query->paginate(10);

        return view('invitations.index', compact('invitations'));
    }

    // Show invitation form.
    public function create()
    {
        $user = auth()->user();

        $roles = $this->allowedRoles($user);

        if ($roles->isEmpty()) {
            abort(403, 'You cannot invite users.');
        }

        $companies = collect();

        if ($user->role->slug === 'super-admin') {
            $companies = Company::where('status', 1)
                ->orderBy('name')
                ->get();
        }

        return view('invitations.create', compact(
            'roles',
            'companies'
        ));
    }

    // Save invitation.
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'email' => 'required|email|max:255',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Validate selected role.
        $role = Role::findOrFail($request->role_id);

        $allowedRoles = $this->allowedRoles($user);

        if (!$allowedRoles->contains('id', $role->id)) {
            abort(403, 'You cannot invite this role.');
        }

        // SuperAdmin selects a company.
        if ($user->role->slug === 'super-admin') {

            $request->validate([
                'company_id' => 'required|exists:companies,id',
            ]);

            $company = Company::where('status', 1)
                ->findOrFail($request->company_id);

        }
        // Admin is restricted to their own company.
        elseif ($user->role->slug === 'admin') {

            $company = $user->company;

            if (!$company || !$company->status) {
                abort(403, 'Your company is inactive.');
            }

        } else {
            abort(403, 'You cannot invite users.');
        }

        // Prevent inviting an existing user.
        if (User::where('email', $request->email)->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'This email is already registered.',
                ]);
        }

        // Prevent duplicate active invitations for this company.
        $existingInvitation = Invitation::where(
            'company_id',
            $company->id
        )
            ->where('email', $request->email)
            ->where('status', 'pending')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();

        if ($existingInvitation) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'An active invitation already exists.',
                ]);
        }

        // Generate invitation.
        $invitation = Invitation::create([
            'company_id' => $company->id,
            'invited_by' => $user->id,
            'email' => $request->email,
            'role_id' => $role->id,
            'token' => Str::random(64),
            'status' => 'pending',
            'expires_at' => now()->addDays(3),
        ]);

        $link = route('invitations.accept', $invitation->token);

        return redirect()
            ->route('invitations.index')
            ->with('success', 'Invitation created successfully.')
            ->with('invitation_link', $link);
    }

    // Show invitation acceptance page.
    public function showAcceptForm($token)
    {
        $invitation = Invitation::with([
            'company',
            'role',
        ])->where('token', $token)->firstOrFail();

        if ($invitation->status !== 'pending') {
            abort(410, 'Invitation is no longer valid.');
        }

        if (
            $invitation->expires_at &&
            $invitation->expires_at->isPast()
        ) {

            $invitation->update([
                'status' => 'expired',
            ]);

            abort(410, 'Invitation has expired.');
        }

        if (!$invitation->company || !$invitation->company->status) {
            abort(410, 'Company is inactive.');
        }

        return view('invitations.accept', compact('invitation'));
    }

    // Accept invitation and create user.
    public function accept(Request $request, $token)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $result = DB::transaction(function () use ($request, $token) {

            $invitation = Invitation::where('token', $token)
                ->lockForUpdate()
                ->firstOrFail();

            if ($invitation->status !== 'pending') {
                return 'invalid';
            }

            if (
                $invitation->expires_at &&
                $invitation->expires_at->isPast()
            ) {

                $invitation->update([
                    'status' => 'expired',
                ]);

                return 'expired';
            }

            if (!$invitation->company || !$invitation->company->status) {
                return 'inactive';
            }

            if (User::where('email', $invitation->email)->exists()) {
                return 'registered';
            }

            User::create([
                'name' => $request->name,
                'email' => $invitation->email,
                'password' => Hash::make($request->password),
                'company_id' => $invitation->company_id,
                'role_id' => $invitation->role_id,
            ]);

            $invitation->update([
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);

            return 'success';
        });

        if ($result === 'expired') {
            abort(410, 'Invitation has expired.');
        }

        if ($result === 'invalid') {
            abort(410, 'Invitation is no longer valid.');
        }

        if ($result === 'inactive') {
            abort(410, 'Company is inactive.');
        }

        if ($result === 'registered') {
            return redirect()
                ->route('login')
                ->with('error', 'This email is already registered.');
        }

        return redirect()
            ->route('login')
            ->with('success', 'Account created successfully. Please login.');
    }

    // Cancel an invitation.
    public function cancel(Invitation $invitation)
    {
        $user = auth()->user();

        if ($user->role->slug === 'admin') {
            if ($invitation->company_id != $user->company_id) {
                abort(403);
            }
        } elseif ($user->role->slug !== 'super-admin') {
            abort(403);
        }

        if ($invitation->status !== 'pending') {
            return back()->with('error', 'Only pending invitations can be cancelled.');
        }

        $invitation->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Invitation cancelled successfully.');
    }
}
