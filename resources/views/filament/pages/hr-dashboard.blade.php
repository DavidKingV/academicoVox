<x-filament-panels::page>
    <div class="flex flex-wrap items-end gap-4 mb-6">
        <div>
            <label for="period" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Period') }}</label>
            <select wire:model.live="selectedPeriodId" id="period" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                @foreach(\App\Models\Period::all() as $period)
                    <option value="{{ $period->id }}">{{ $period->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Start Date') }}</label>
            <input type="date" wire:model.live="startDate" id="startDate" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
        </div>

        <div>
            <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('End Date') }}</label>
            <input type="date" wire:model.live="endDate" id="endDate" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
        </div>
    </div>

    <x-filament::section>
        <x-slot name="heading">{{ __('Teacher Hours') }}</x-slot>

        @if(count($teacherHours) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2">{{ __('Teacher') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('Planned Hours') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('Remote Hours') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('Total Hours') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('Leave Days') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teacherHours as $teacher)
                            <tr class="border-b dark:border-gray-600">
                                <td class="px-4 py-2 font-medium">{{ $teacher['teacherName'] }}</td>
                                <td class="px-4 py-2 text-right">{{ $teacher['plannedHours'] }}</td>
                                <td class="px-4 py-2 text-right">{{ $teacher['remoteHours'] }}</td>
                                <td class="px-4 py-2 text-right font-semibold">{{ $teacher['totalHours'] }}</td>
                                <td class="px-4 py-2 text-right">
                                    @if($teacher['leaveDays'] > 0)
                                        <x-filament::badge color="warning">
                                            {{ $teacher['leaveDays'] }}
                                        </x-filament::badge>
                                    @else
                                        <span class="text-gray-400">0</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700 font-semibold">
                        <tr>
                            <td class="px-4 py-2">{{ __('Total') }}</td>
                            <td class="px-4 py-2 text-right">{{ collect($teacherHours)->sum('plannedHours') }}</td>
                            <td class="px-4 py-2 text-right">{{ collect($teacherHours)->sum('remoteHours') }}</td>
                            <td class="px-4 py-2 text-right">{{ collect($teacherHours)->sum('totalHours') }}</td>
                            <td class="px-4 py-2 text-right">{{ collect($teacherHours)->sum('leaveDays') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-500">{{ __('No teachers found.') }}</p>
        @endif
    </x-filament::section>
</x-filament-panels::page>
