<?php

namespace App\Livewire\Accounting;

use Livewire\Component;

use App\AbAccount;

class AccountingComponent extends Component
{
    public $displayingLedgerAbAccount;

    public $modes = [
        'create' => false,
        'list' => false,
        'journal' => false,
        'ledger' => false,
        'trialBalance' => false,

        'incomeStatement' => false,
        'balanceSheet' => false,
        'cashFlow' => false,

        'accountTypeList' => false,
        'accountTypeCreate' => false,
    ];

    protected $listeners = [
        'abAccountAdded',
        'displayAbAccountLedger',
        'exitCreateMode',

        'exitAccountTypeCreateMode',
    ];

    public function render()
    {
        return view('livewire.accounting.accounting-component');
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

    public function abAccountAdded()
    {
        $this->exitMode('create');
    }

    public function displayAbAccountLedger($abAccountId)
    {
        $abAccount = AbAccount::find($abAccountId);

        $this->displayingLedgerAbAccount = $abAccount;

        $this->enterMode('displayLedger');
    }

    public function exitCreateMode()
    {
        $this->exitMode('create');
    }

    public function exitAccountTypeCreateMode()
    {
        $this->exitMode('accountTypeCreate');
    }
}
