<?php

namespace App\Http\Livewire\Auth\Admin;

use App\Models\Admin;
use App\Rules\Equals;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\AdminInvitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;

class Invitation extends Component
{
    public $firstName = '';
    public $lastName = '';
    public $email = '';
    public $password = '';
    public $invitationId = null;

    public function mount(Request $request, $adminInvitation)
    {
        // Signature invalid
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $invitation = AdminInvitation::findOrFail($adminInvitation);

        $this->email = $invitation->email;
        $this->invitationId = $invitation->id;

        // User logged in as admin
        if (Auth::guard('admin')->check()) {
            return redirect(route(RouteServiceProvider::ADMIN_HOME));
        }

        // User already has an account
        if (Admin::where('email', $this->email)->exists()) {
            return redirect(route('admin.login'));
        }
    }

    public function register()
    {
        $this->validate([
            'firstName' => ['required', 'max:50'],
            'lastName' => ['required', 'max:50'],
            'email' => ['required', 'email', 'unique:admins', new Equals($this->adminInvitation->email)],
            'password' => ['required', 'min:8'],
        ]);

        $user = Admin::create([
            'firstname' => $this->firstName,
            'lastname' => $this->lastName,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $this->adminInvitation->delete();
        Auth::guard('admin')->login($user, true);

        return redirect()->intended(route(RouteServiceProvider::ADMIN_HOME));
    }

    public function getAdminInvitationProperty()
    {
        return AdminInvitation::find($this->invitationId);
    }

    public function render()
    {
        return view('livewire.auth.admin.invitation')
            ->layout('layouts.auth', ['title' => 'Admin Invitation']);
    }
}
