<div class="py-6 flex flex-col">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray- dark:text-white">Courses</h1>
        <x-button icon="plus" label="Add Course" wire:click="openModal" primary />
    </div>

    <div
        class="bg-white p-6 dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200">
        <div class="pb-4 w-64">
            <x-input placeholder="Search course..." />
        </div>
        <div class="overflow-x-auto">
            @php
                $headers = [
                    'code' => 'Code',
                    'name' => 'Name',
                    'weekly_hours' => 'Weekly Hours',
                    'level' => 'Level',
                    'semester' => 'Semester',
                    'department' => 'Department',
                    'num_of_students' => 'No. of Students',
                ];

                $rows = $courses
                    ->map(function ($course) {
                        return [
                            'id' => $course->id,
                            'code' => $course->code,
                            'name' => $course->name,
                            'weekly_hours' => $course->weekly_hours,
                            'level' => $course->level,
                            'semester' => $course->semester,
                            'department' => $course->department->name ?? 'N/A',
                            'num_of_students' => $course->num_of_students,
                        ];
                    })
                    ->toArray();
            @endphp
            <x-table :headers="$headers" :rows="$rows" :actions="true" :paginate="false" />


        </div> {{ $courses->links() }}
    </div>

    <livewire:course.course-modal />
</div>
