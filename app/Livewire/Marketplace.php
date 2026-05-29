<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subject;
use App\Models\TutorProfile;

class Marketplace extends Component
{
    use WithPagination;

    public $search = '';
    public $subject = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'subject' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSubject()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->subject = '';
        $this->resetPage();
    }

    public function render()
    {
        $allSubjects = Subject::orderBy('name')->get();

        $query = TutorProfile::with(['user', 'subjects', 'reviews'])
            ->whereNotNull('bio')
            ->whereNotNull('hourly_rate')
            ->whereNotNull('profile_photo');

        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                })
                ->orWhere('bio', 'like', "%{$search}%")
                ->orWhereHas('subjects', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                });
            });
        }

        if (!empty($this->subject)) {
            $subjectId = $this->subject;
            $query->whereHas('subjects', function ($q) use ($subjectId) {
                $q->where('subjects.id', $subjectId);
            });
        }

        $tutors = $query->latest()->paginate(9);

        return view('livewire.marketplace', [
            'tutors' => $tutors,
            'allSubjects' => $allSubjects,
        ]);
    }
}
