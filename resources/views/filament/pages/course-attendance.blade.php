<x-filament-panels::page>
    @if(!$course)
        <x-filament::section>
            <p class="text-sm text-gray-500">{{ __('No course selected.') }}</p>
        </x-filament::section>
    @else
        <x-filament::section>
            <x-slot name="heading">{{ $course->name }} — {{ __('Attendance Grid') }}</x-slot>

            @if(count($events) > 0 && count($students) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 sticky top-0">
                            <tr>
                                <th class="px-3 py-2 sticky left-0 bg-gray-50 dark:bg-gray-700 z-10">{{ __('Student') }}</th>
                                @foreach($events as $event)
                                    <th class="px-2 py-2 text-center whitespace-nowrap" title="{{ $event['date'] }}">
                                        {{ $event['name'] }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr class="border-b dark:border-gray-600">
                                    <td class="px-3 py-2 sticky left-0 bg-white dark:bg-gray-800 z-10 whitespace-nowrap font-medium">
                                        {{ $student['studentName'] }}
                                    </td>
                                    @foreach($events as $event)
                                        @php
                                            $att = $student['attendances'][$event['id']] ?? null;
                                            $color = match($att['typeId'] ?? null) {
                                                1 => 'success',   // Present
                                                2 => 'info',      // Late
                                                3 => 'warning',   // Justified absence
                                                4 => 'danger',    // Unjustified absence
                                                default => 'gray',
                                            };
                                        @endphp
                                        <td class="px-2 py-2 text-center">
                                            @if($att)
                                                <x-filament::badge :color="$color" size="xs">
                                                    {{ mb_substr($att['type'], 0, 1) }}
                                                </x-filament::badge>
                                            @else
                                                <span class="text-gray-300">—</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500">{{ __('No events or students found for this course.') }}</p>
            @endif
        </x-filament::section>
    @endif
</x-filament-panels::page>
