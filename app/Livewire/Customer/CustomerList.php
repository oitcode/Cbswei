<?php

namespace App\Livewire\Customer;

use Livewire\Component;

use App\Customer;

class CustomerList extends Component
{
    public $customers;

    public $total;

    public $customerSearch = [
        'name' => null,
        'phone' => null,
        'email' => null,
        'address' => null,
    ];

    public function mount()
    {
        $this->customers = Customer::orderBy('name', 'ASC')->get();
        $this->total = Customer::count();
    }

    public function render()
    {
        return view('livewire.customer.customer-list');
    }

    public function search()
    {
        $this->customers = new Customer;

        if ($this->customerSearch['name']) {
            $this->customers = $this->customers->where('name', 'like', '%'.$this->customerSearch['name'].'%');
        } 

        if ($this->customerSearch['phone']) {
            $this->customers = $this->customers->where('phone', 'like', '%'.$this->customerSearch['phone'].'%');
        } 

        $this->customers = $this->customers->get();
    }

    public function getCreditors()
    {
        $customers = Customer::all();

        foreach ($customers as $key => $customer) {
            if ($customer->getBalance() <= 0) {
              // remove this element
              unset($customers[$key]);
            }
        }

        $this->customers = $customers;
    }
}
