<x-filament-panels::page>
    @if(!$event)
        <x-filament::section>
            <p class="text-sm text-gray-500">{{ __('No event selected.') }}</p>
        </x-filament::section>
    @else
        <x-filament::section>
            <x-slot name="heading">
                {{ $event->course?->name ?? '' }} — {{ $event->start?->format('d/m/Y H:i') }}
            </x-slot>

            @if(count($students) > 0)
                <div class="space-y-3">
                    @foreach($students as $student)
                        <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-800">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $student['studentName'] }}
                            </span>
                            <div class="flex gap-1">
                                @foreach($attendanceTypes as $type)
                                    @php
                                        $isActive = $student['currentTypeId'] === $type['id'];
                                        $color = match($type['id']) {
                                            1 => 'success',
                                            2 => 'info',
                                            3 => 'warning',
                                            4 => 'danger',
                                            default => 'gray',
                                        };
                                    @endphp
                                    <x-filament::button
                                        wire:click="toggleAttendance({{ $student['studentId'] }}, {{ $type['id'] }})"
                                        :color="$isActive ? $color : 'gray'"
                                        :outlined="!$isActive"
                                        size="xs"
                                    >
                                        {{ $type['name'] }}
                                    </x-filament::button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">{{ __('No students enrolled in this course.') }}</p>
            @endif
        </x-filament::section>
    @endif
</x-filament-panels::page>
