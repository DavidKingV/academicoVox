<x-filament-panels::page>
    @if(count($partnersData) > 0)
        <x-filament::section>
            <x-slot name="heading">{{ __('Partners') }}</x-slot>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2">{{ __('Name') }}</th>
                            <th class="px-4 py-2">{{ __('Started On') }}</th>
                            <th class="px-4 py-2">{{ __('Expired On') }}</th>
                            <th class="px-4 py-2 text-center">{{ __('Auto Renewal') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('Courses') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($partnersData as $partner)
                            <tr class="border-b dark:border-gray-600">
                                <td class="px-4 py-2">{{ $partner['name'] }}</td>
                                <td class="px-4 py-2">{{ $partner['started_on'] }}</td>
                                <td class="px-4 py-2">{{ $partner['expired_on'] }}</td>
                                <td class="px-4 py-2 text-center">
                                    @if($partner['auto_renewal'])
                                        <x-heroicon-s-check-circle class="w-5 h-5 text-green-500 inline" />
                                    @else
                                        <x-heroicon-s-x-circle class="w-5 h-5 text-gray-400 inline" />
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-right">{{ $partner['courses_count'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    @else
        <x-filament::section>
            <p class="text-sm text-gray-500">{{ __('No partners found.') }}</p>
        </x-filament::section>
    @endif
</x-filament-panels::page>
