<?php

namespace App\Livewire\Tenant\Settings\Departments;

use App\Models\Department;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.tenant')]
class DepartmentIndex extends Component
{
    public $departments;
    
    public $isModalOpen = false;
    public $departmentId = null;
    public $name = '';
    public $description = '';
    public $isActive = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'isActive' => 'boolean',
    ];

    public function mount()
    {
        $this->loadDepartments();
    }

    public function loadDepartments()
    {
        $this->departments = Department::all();
    }

    public function create()
    {
        $this->resetValidation();
        $this->departmentId = null;
        $this->name = '';
        $this->description = '';
        $this->isActive = true;
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $this->resetValidation();
        $department = Department::findOrFail($id);
        $this->departmentId = $department->id;
        $this->name = $department->name;
        $this->description = $department->description;
        $this->isActive = $department->is_active;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->departmentId) {
            Department::findOrFail($this->departmentId)->update([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->isActive,
            ]);
        } else {
            Department::create([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->isActive,
            ]);
        }

        $this->isModalOpen = false;
        $this->loadDepartments();
    }

    public function delete($id)
    {
        Department::findOrFail($id)->delete();
        $this->loadDepartments();
    }

    public function render()
    {
        return view('livewire.tenant.settings.departments.department-index');
    }
}
