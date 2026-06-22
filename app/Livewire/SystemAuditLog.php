<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class SystemAuditLog extends Component
{
    use WithPagination;

    public $timeframe = '7_days';

    public function mount()
    {
        // Strict Access Logging
        AuditLog::create([
            'event_type' => 'admin_viewed_audit_logs',
            'model_type' => Auth::user() ? get_class(Auth::user()) : null,
            'model_id'   => Auth::id(),
            'user_id'    => Auth::id(),
            'old_values' => null,
            'new_values' => ['action' => 'Viewed system audit logs page'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function updatingTimeframe()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = AuditLog::query()->orderBy('created_at', 'desc');

        if ($this->timeframe === 'today') {
            $query->where('created_at', '>=', now()->startOfDay());
        } elseif ($this->timeframe === '7_days') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($this->timeframe === '30_days') {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        return view('livewire.system-audit-log', [
            'logs' => $query->paginate(15)
        ]);
    }
}
