<?php

namespace App\Livewire\Tenant\Settings\Teams;

use App\Models\Team;
use App\Models\TenantUser;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.tenant')]
#[Title('Teams')]
class TeamIndex extends Component
{
    public bool $showFormModal = false;
    public ?int $editingTeamId = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:1000')]
    public string $description = '';

    public array $selectedAgentIds = [];

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEditModal(int $id)
    {
        $team = Team::where('tenant_id', tenant('id'))->with('tenantUsers')->findOrFail($id);
        $this->editingTeamId = $team->id;
        $this->name = $team->name;
        $this->description = $team->description ?? '';
        $this->selectedAgentIds = $team->tenantUsers->pluck('id')->map(fn($val) => (string)$val)->toArray();
        $this->showFormModal = true;
    }

    public function save()
    {
        $this->validate();

        $tenantId = tenant('id');

        if ($this->editingTeamId) {
            $team = Team::where('tenant_id', $tenantId)->findOrFail($this->editingTeamId);
            $team->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            $this->dispatch('toast', message: 'Team updated successfully.', type: 'success');
        } else {
            $team = Team::create([
                'tenant_id' => $tenantId,
                'name' => $this->name,
                'description' => $this->description,
            ]);
            $this->dispatch('toast', message: 'Team created successfully.', type: 'success');
        }

        // Sync team members
        $team->tenantUsers()->sync(array_map('intval', $this->selectedAgentIds));

        $this->resetForm();
        $this->showFormModal = false;
    }

    public function deleteTeam(int $id)
    {
        $team = Team::where('tenant_id', tenant('id'))->findOrFail($id);
        $team->delete();
        $this->dispatch('toast', message: 'Team deleted successfully.', type: 'warning');
    }

    public function resetForm()
    {
        $this->editingTeamId = null;
        $this->name = '';
        $this->description = '';
        $this->selectedAgentIds = [];
        $this->resetValidation();
    }

    public function render()
    {
        $tenantId = tenant('id');

        $teams = Team::where('tenant_id', $tenantId)
            ->with(['tenantUsers.user'])
            ->withCount('tenantUsers')
            ->orderBy('name', 'asc')
            ->get();

        $availableAgents = TenantUser::where('tenant_id', $tenantId)
            ->whereHas('user', function ($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            })
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('is_ai', false)->orWhereNull('is_ai');
            })
            ->with('user')
            ->get();

        return view('livewire.tenant.settings.teams.team-index', [
            'teams' => $teams,
            'availableAgents' => $availableAgents,
        ]);
    }
}
