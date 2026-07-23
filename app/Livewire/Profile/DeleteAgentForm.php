<?php

namespace App\Livewire\Profile;

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeleteAgentForm extends Component
{
    public string $password = '';
    public bool $confirmingAgentDeletion = false;
    /**
     * Delete the currently authenticated user.
     */
    public function deleteAgent(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);
        tap(Auth::user(), $logout(...))->delete();
        $this->redirect('/', navigate: true);
    }
    public function render()
    {
        return view('livewire.profile.delete-agent-form');
    }
}
