<?php

namespace App\Http\Livewire\Accounts\Companies;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;

class CompaniesShow extends Component
{
    use WithPagination;

    public $companyId;
    protected $listeners = ['depratmentUpdate' => '$refresh'];
    protected $queryString = [
        'page' => ['except' => 1],
    ];

    public function mount(Company $company)
    {
        $this->companyId = $company->id;
    }

    public function getCompanyProperty()
    {
        return Company::find($this->companyId);
    }

    public function render()
    {
        return view('livewire.accounts.companies.show', [
            'company' => Company::find($this->companyId),
        ])->layout('layouts.app', ['title' => 'Company']);
    }

    public function companyTasks()
    {
        return $this->company
            ->tasks()
        // ->with('user:id,firstname,lastname')
            ->latest()
            ->paginate(8);
    }
}
