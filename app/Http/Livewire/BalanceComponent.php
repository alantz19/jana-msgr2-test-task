<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\BalanceService;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class BalanceComponent extends Component
{

    public $balance;

    protected $listeners = ['balanceUpdated' => 'refreshBalance'];

    public function mount()
    {
        $this->refreshBalance();
    }

    public function render()
    {
        return view('livewire.balance-component');
    }

    public function refreshBalance()
    {
        $user = User::where('id',Auth::user()->id)->first();
        $this->balance = BalanceService::getTeamBalance($user);
    }

    public function hydrate()
    {
        $this->refreshBalance();
    }

    public function updated()
    {
        $this->refreshBalance();
    }
}
