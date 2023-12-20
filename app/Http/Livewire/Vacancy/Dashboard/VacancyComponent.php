<?php

namespace App\Http\Livewire\Vacancy\Dashboard;

use Livewire\Component;

use App\Traits\ModesTrait;

use App\Vacancy;

class VacancyComponent extends Component
{
    use ModesTrait;

    public $displayingVacancy;

    public $modes = [
        'createMode' => false,
        'listMode' => true,
        'displayMode' => false,
        'updateMode' => false,
    ];

    protected $listeners = [
        'vacancyCreateCompleted',
        'vacancyCreateCancelled',

        'displayVacancy',
    ];

    public function render()
    {
        return view('livewire.vacancy.dashboard.vacancy-component');
    }

    public function vacancyCreateCompleted()
    {
        $this->exitMode('createMode');
    }

    public function vacancyCreateCancelled()
    {
        $this->exitMode('createMode');
    }

    public function displayVacancy(Vacancy $vacancy)
    {
        $this->displayingVacancy = $vacancy;

        $this->enterMode('displayMode');
    }
}
