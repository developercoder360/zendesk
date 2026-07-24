<?php

namespace App\Livewire\Tenant\Customers;

use App\Models\Visitor;
use App\Models\Chat;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.tenant')]
#[Title('Customers')]
class CustomerIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public ?int $selectedCustomerId = null;

    public function selectCustomer(?int $id)
    {
        $this->selectedCustomerId = $id;
    }

    public function getSelectedCustomerProperty()
    {
        if (!$this->selectedCustomerId) {
            return null;
        }

        return Visitor::where('tenant_id', tenant('id'))
            ->with(['chats' => function ($q) {
                $q->withCount('messages')->latest();
            }])
            ->find($this->selectedCustomerId);
    }

    public function render()
    {
        $tenantId = tenant('id');

        $customers = Visitor::where('tenant_id', $tenantId)
            ->when($this->search !== '', function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'ilike', '%' . $this->search . '%')
                        ->orWhere('email', 'ilike', '%' . $this->search . '%')
                        ->orWhere('ip_address', 'ilike', '%' . $this->search . '%');
                });
            })
            ->withCount('chats')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('livewire.tenant.customers.customer-index', [
            'customers' => $customers,
            'selectedCustomer' => $this->selectedCustomer,
        ]);
    }
}
