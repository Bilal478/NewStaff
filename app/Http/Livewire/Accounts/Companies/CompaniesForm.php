<?php

namespace App\Http\Livewire\Accounts\Companies;

use App\Models\Department;
use Livewire\Component;
use App\Http\Livewire\Traits\Notifications;
use App\Models\Company;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CompaniesForm extends Component
{
    use AuthorizesRequests, Notifications;

    public $company;
    public $isEditing = false;

    protected $listeners = [
        'companiesEdit' => 'edit',
        'companiesCreate' => 'create',
    ];

    protected $rules = [
        'company.title' => 'required|string|max:250',
    ];

    public function create(Company $company)
    {
        if (request()->user()->cannot('create', Company::class)) {
            //return $this->toast(
                //'Unauthorize Action',
                //'You don\'t have permission to create a department.',
                //'error'
            //);
        }

        $this->isEditing = false;
        $this->company = $company;
        $this->showModal();
    }

    public function edit(Company $company)
    {
        if (request()->user()->cannot('update', $company)) {
            //return $this->toast(
                //'Unauthorize Action',
                //'You don\'t have permission to update a department.',
                //'error'
            //);
        }

        $this->isEditing = true;
        $this->company = $company;
        $this->showModal();
    }

    public function showModal()
    {
        $this->dispatchBrowserEvent('open-create-company-modal');

    }

    public function save()
    {
        // dd(auth()->user());
        $this->validate();
        $this->company->save();

        $this->emit('companyUpdate');
        $this->dispatchBrowserEvent('close-create-company-modal');
        $this->isEditing
            ? $this->toast('company Updated', "company has been updated.")
            : $this->toast('company Created', "company has been created.");
    }

    public function render()
    {
        // dd('value');
        return view('livewire.accounts.companies.form');
    }
}
