<?php

namespace App\Http\Livewire\Accounts\Companies;


use App\Models\Subscription;
use App\Models\User;


use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Traits\Notifications;
use App\Models\Company;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CompaniesIndex extends Component
{
    use WithPagination, AuthorizesRequests, Notifications;

    public $search = '';

    protected $listeners = ['companyUpdate' => '$refresh'];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function companyShow(Company $company)
    {
        return redirect()->route('accounts.companies.show', ['company' => $company]);
    }

    public function companyArchive(Company $company)
    {
        if (request()->user()->cannot('delete', $company)) {
            //return $this->toast(
                //'Unauthorize Action',
                //'You don\'t have permission to delete a department.',
                //'error'
            //);
        }

        $company->delete();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
	
		return view('livewire.accounts.companies.index', [
				'companies' => $this->companies(),
		])->layout('layouts.app', ['title' => 'Companies']);
			
    }

    public function companies()
    {
        return Auth::guard('web')->user()->isOwnerOrManager()
            ? $this->companiesForAccount()
            : $this->companiesForUser();
    }

    public function companiesForAccount()
    {
        return Company::titleSearch($this->search)
            ->with('departments')
            ->withCount(['departments'])
            ->orderBy('title')
            ->paginate(12);
    }

    public function companiesForUser()
    {
        return Auth::guard('web')->user()
            ->companies()
            ->titleSearch($this->search)
            ->with('users:id,firstname,lastname')
            ->withCount(['users'])
            ->latest()
            ->paginate(12);
    }
}
