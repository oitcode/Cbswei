<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Expense;

class ExpenseComponent extends Component
{
    public $displayingExpense = null;

    public $modes = [
        'create' => false,
        'list' => true,
        'display' => true,
        'report' => false,

        'createCategory' => false,
    ];

    protected $listeners = [
        'exitCreateMode',
        'expenseCategoryCreated',
        'exitCategoryCreateMode',
        'expenseCreated',
        'displayExpense',
    ];

    public function render()
    {
        return view('livewire.expense-component');
    }

    /* Clear modes */
    public function clearModes()
    {
        foreach ($this->modes as $key => $val) {
            $this->modes[$key] = false;
        }
    }

    /* Enter and exit mode */
    public function enterMode($modeName)
    {
        $this->clearModes();

        $this->modes[$modeName] = true;
    }

    public function exitMode($modeName)
    {
        $this->modes[$modeName] = false;
    }

    public function exitCreateMode()
    {
        $this->exitMode('create');
    }

    public function expenseCreated()
    {
        session()->flash('message', 'Expense added');

        $this->exitMode('create');
    }

    public function expenseCategoryCreated()
    {
        session()->flash('message', 'Expense category added');

        $this->exitMode('createCategory');
    }

    public function exitCategoryCreateMode()
    {
        $this->exitMode('createCategory');
    }

    public function displayExpense(Expense $expense)
    {
        $this->displayingExpense = $expense;

        $this->enterMode('display');
    }
}
