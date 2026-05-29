<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class AdminUsersList extends Component
{
    use WithPagination;

    public $search = '';
    public $role = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'role' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRole()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->role = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = User::query()
            ->with([
                'tutorProfile.subjects',
                'tutorProfile.bookings' => function($q) {
                    $q->where('status', 'completed');
                },
                'bookings' => function($q) {
                    $q->where('status', 'completed');
                }
            ]);

        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($this->role) && in_array($this->role, ['tutor', 'learner', 'admin'])) {
            $query->where('role', $this->role);
        }

        $users = $query->orderBy('name')->paginate(10);

        return view('livewire.admin-users-list', [
            'users' => $users
        ]);
    }
}
