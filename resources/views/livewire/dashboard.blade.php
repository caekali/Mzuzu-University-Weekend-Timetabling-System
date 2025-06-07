<div>
    @switch($currentRole)
        @case('Admin')
            @livewire('dashboard.admin-panel')
        @break

        @case('Lecturer')
            @livewire('dashboard.lecturer-panel')
        @break

        @case('Student')
            @livewire('dashboard.student-panel')
        @break
    @endswitch
</div>
