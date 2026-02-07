<x-filament-panels::page>
    <x-filament::section>
        <div class="flex flex-wrap items-center gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Start Date') }}</label>
                <input type="date" wire:model.live="startDate" class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('End Date') }}</label>
                <input type="date" wire:model.live="endDate" class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
            </div>
        </div>
    </x-filament::section>

    @if(count($summaryData) > 0)
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <x-filament::section>
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary-600">{{ $summaryData['courses'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('Courses') }}</div>
                </div>
            </x-filament::section>
            <x-filament::section>
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary-600">{{ $summaryData['enrollments'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('Enrollments') }}</div>
                </div>
            </x-filament::section>
            <x-filament::section>
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary-600">{{ $summaryData['students'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('Students') }}</div>
                </div>
            </x-filament::section>
            <x-filament::section>
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary-600">{{ $summaryData['taught_hours'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('Taught Hours') }}</div>
                </div>
            </x-filament::section>
            <x-filament::section>
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary-600">{{ $summaryData['sold_hours'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('Sold Hours') }}</div>
                </div>
            </x-filament::section>
        </div>

        @if(count($coursesData) > 0)
            <x-filament::section>
                <x-slot name="heading">{{ __('Course Details') }}</x-slot>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2">{{ __('Course') }}</th>
                                <th class="px-4 py-2">{{ __('Partner') }}</th>
                                <th class="px-4 py-2">{{ __('Rhythm') }}</th>
                                <th class="px-4 py-2">{{ __('Level') }}</th>
                                <th class="px-4 py-2">{{ __('Teacher') }}</th>
                                <th class="px-4 py-2">{{ __('Start') }}</th>
                                <th class="px-4 py-2">{{ __('End') }}</th>
                                <th class="px-4 py-2 text-right">{{ __('Students') }}</th>
                                <th class="px-4 py-2 text-right">{{ __('Volume') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coursesData as $course)
                                <tr class="border-b dark:border-gray-600">
                                    <td class="px-4 py-2">{{ $course['name'] }}</td>
                                    <td class="px-4 py-2">{{ $course['partner'] }}</td>
                                    <td class="px-4 py-2">{{ $course['rhythm'] }}</td>
                                    <td class="px-4 py-2">{{ $course['level'] }}</td>
                                    <td class="px-4 py-2">{{ $course['teacher'] }}</td>
                                    <td class="px-4 py-2">{{ $course['start_date'] }}</td>
                                    <td class="px-4 py-2">{{ $course['end_date'] }}</td>
                                    <td class="px-4 py-2 text-right">{{ $course['head_count'] }}</td>
                                    <td class="px-4 py-2 text-right">{{ $course['volume'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        @endif
    @else
        <x-filament::section>
            <p class="text-sm text-gray-500">{{ __('No data available.') }}</p>
        </x-filament::section>
    @endif
</x-filament-panels::page>
