<?php

namespace App\Http\Livewire\Accounts\Companies;

use App\Models\Company;
use App\Models\User;
use App\Models\Department;
use Livewire\Component;

class CompanyUsersForm extends Component
{
    public $companyId;
    public $departmentId = '';
    public $arrayDepartment = [];

    public function getCompanyProperty()
    {
        return Company::find($this->companyId);
    }

    public function add()
    {
        $this->validate([
            'departmentId' => ['required', 'max:100'],
        ]);
        $this->company->addMemeber($this->departmentId);
        return redirect('companies/'.$this->getCompanyProperty()->id);
    }

    public function remove($departmentId)
    {
        $this->company->removeMemeber($departmentId);
        return redirect('companies/'.$this->getCompanyProperty()->id);
    }

    public function render()
    {
        foreach ($this->getCompanyProperty()->departments as $key => $value) {
            $this->arrayDepartment[] = $value->pivot->department_id;
        }

        return view('livewire.accounts.companies.users-form', [
            'departmentIn' => $this->getCompanyProperty()->departments,
            'departmentOut' => Department::whereNotIn('id',$this->arrayDepartment)->orderBy('title')->get(),
        ]);
    }
}
