<div class="py-6 animate-fade-in">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Settings</h1>
        </div>
    </div>

    <div class="space-y-6 ">
        {{-- <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200 p-6">
            <div class="flex items-center mb-4">
                <x-lucide-calendar class="h-5 w-5 text-green-600 dark:text-green-400 mr-2" />
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">General Settings</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-input label='Academic Year' wire:model.live='academic_year' />
                <x-input label='Semester' wire:model.live='semester' />
            </div>
        </div> --}}


        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200 p-6">
            <div class="flex items-center mb-4">
                <x-lucide-clock class="h-5 w-5 text-green-600 dark:text-green-400 mr-2" />
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Time Slot Configuration</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-4">
                <x-time-picker label="Standard Start Time" wire:model.live='standardStartTime' military-time
                    without-seconds />
                <x-time-picker label="Standard End Time" wire:model.live='standardEndTime' military-time
                    without-seconds />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <x-number label='Slot Duration (minutes)' wire:model.live='slotDuration'
                    description='Duration of each teaching slot (15-180 minutes)' />
                <x-number label='Break Duration (minutes)' wire:model.live='breakDuration'
                    description=' Break time between slots (0-60 minutes)' />
            </div>
        </div>

        {{-- Working days --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Working Days Schedule</h2>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ count(array_filter($scheduleDays, fn($d) => $d['enabled'])) }}
                </span>
            </div>

            <div class="space-y-4">
                @foreach ($scheduleDays as $day)
                    <div
                        class="p-4 rounded-lg border transition-all duration-200 {{ $day['enabled'] ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-700' : 'bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:change="toggleDay({{ $day['id'] }})"
                                        @checked($day['enabled'])
                                        class="rounded border-gray-300 dark:border-gray-600 text-green-600 dark:text-green-400 focus:ring-green-500 dark:focus:ring-green-400 bg-white dark:bg-gray-800" />
                                    <span
                                        class="ml-3 text-sm font-medium {{ $day['enabled'] ? 'text-green-800 dark:text-green-300' : 'text-gray-500 dark:text-gray-400' }}">
                                        {{ $day['name'] }}
                                    </span>
                                </label>
                            </div>

                            @if ($day['enabled'])
                                <div class="flex items-center space-x-4 ">
                                    <div class="flex items-center space-x-2">
                                        <label class="text-xs text-gray-600 dark:text-gray-400">Start:</label>
                                        <x-time-picker military-time
                                            wire:change="updateTime({{ $day['id'] }}, 'startTime', $event.target.value)"
                                            value="{{ $day['start_time'] }}" without-seconds class="min-w-[180px]" />
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <label class="text-xs text-gray-600 dark:text-gray-400">End:</label>
                                        <x-time-picker military-time
                                            wire:change="updateTime({{ $day['id'] }}, 'endTime', $event.target.value)"
                                            value="{{ $day['end_time'] }}" without-seconds />
                                    </div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 min-w-[80px] text-right">
                                        {{ $this->calculateTotalSlots($day) }} slots
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($day['enabled'])
                            <div class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                                Duration:
                                {{ $this->formatDuration($this->calculateDurationMinutes($day)) }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6 p-4  dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Schedule Summary</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Working Days:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-white">
                            {{ count(array_filter($scheduleDays, fn($d) => $d['enabled'])) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Total Slots:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-white">
                            {{ array_reduce($scheduleDays, fn($total, $day) => $total + $this->calculateTotalSlots($day), 0) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Slot Duration:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-white">
                            {{ $this->formatDuration($slotDuration) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Break Duration:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-white">
                            {{ $this->formatDuration($breakDuration) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @if ($hasUnsavedChanges)
            <div class="flex justify-end">
                <x-button primary label="Save Settings" wire:click="save" />
            </div>
        @endif
    </div>
</div>
