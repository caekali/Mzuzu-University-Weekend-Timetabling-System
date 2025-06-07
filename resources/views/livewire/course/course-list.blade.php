<div class="p-6 flex flex-col">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Courses</h1>
        <x-button icon="plus" label="Add Course" wire:click="openModal" primary />
    </div>

    <div class="flex-1 grow bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <div class="relative">
                <x-icons.search class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                <input type="text" placeholder="Search course..."
                    class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
            </div>
        </div>
        <div class="overflow-x-auto">
            @php
                $headers = [
                    'id' => 'ID',
                    'code' => 'Code',
                    'name' => 'Name',
                    'weekly_hours' => 'Weekly Hours',
                    'department' => 'Department',
                    'num_of_students' => 'No. of Students',
                ];

                 $rows = $courses->map(function ($course) {
        return [
            'id' => $course->id,
            'code' => $course->code,
            'name' => $course->name,
            'weekly_hours' => $course->weekly_hours,
            'department' => $course->department->name ?? 'N/A',
            'num_of_students' => $course->num_of_students,
        ];
    })->toArray();
            @endphp
            <x-table :headers="$headers" :rows="$rows" :actions="true" :paginate="false" />
        </div>
    </div>
    <livewire:course.course-modal />
</div>
