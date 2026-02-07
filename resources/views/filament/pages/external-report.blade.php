<x-filament-panels::page>
    <x-filament::section>
        <div class="flex flex-wrap items-center gap-4 mb-4">
            <div>
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Start from period:') }}</span>
                <select wire:model.live="startFromPeriodId" class="ml-1 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                    @foreach($allPeriods as $period)
                        <option value="{{ $period['id'] }}">{{ $period['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Partner:') }}</span>
                <select wire:model.live="selectedPartnerId" class="ml-1 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                    <option value="">{{ __('All Partners') }}</option>
                    @foreach($partners as $partner)
                        <option value="{{ $partner['id'] }}">{{ $partner['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </x-filament::section>

    @if(count($reportData) > 0)
        <x-filament::section class="mb-6">
            <x-slot name="heading">{{ __('Per Period') }}</x-slot>
            <div
                wire:ignore
                x-data="{ chart: null }"
                x-init="
                    const loadChartJs = () => new Promise(resolve => {
                        if (window.Chart) { resolve(); return; }
                        const s = document.createElement('script');
                        s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js';
                        s.onload = resolve;
                        document.head.appendChild(s);
                    });

                    const render = (data) => {
                        if (chart) { chart.destroy(); }
                        const canvas = $el.querySelector('canvas');
                        if (!canvas || !data.labels || !data.labels.length) return;
                        chart = new Chart(canvas, {
                            type: 'bar',
                            data: data,
                            options: {
                                responsive: true,
                                scales: { y: { beginAtZero: true } },
                            }
                        });
                    };

                    loadChartJs().then(() => render($wire.chartData));
                    $wire.$watch('chartData', (newData) => render(newData));
                "
            >
                <canvas></canvas>
            </div>
        </x-filament::section>

        <x-filament::section class="mb-6">
            <x-slot name="heading">{{ __('Per Year') }}</x-slot>
            <div
                wire:ignore
                x-data="{ chart: null }"
                x-init="
                    const loadChartJs = () => new Promise(resolve => {
                        if (window.Chart) { resolve(); return; }
                        const s = document.createElement('script');
                        s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js';
                        s.onload = resolve;
                        document.head.appendChild(s);
                    });

                    const render = (data) => {
                        if (chart) { chart.destroy(); }
                        const canvas = $el.querySelector('canvas');
                        if (!canvas || !data.labels || !data.labels.length) return;
                        chart = new Chart(canvas, {
                            type: 'bar',
                            data: data,
                            options: {
                                responsive: true,
                                scales: { y: { beginAtZero: true } },
                            }
                        });
                    };

                    loadChartJs().then(() => render($wire.yearChartData));
                    $wire.$watch('yearChartData', (newData) => render(newData));
                "
            >
                <canvas></canvas>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">{{ __('Data') }}</x-slot>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2">{{ __('Period') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('Courses') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('Partnerships') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('Enrollments') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('Students') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('Taught Hours') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('Sold Hours') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData as $row)
                            <tr class="border-b dark:border-gray-600 {{ $row['isYearSummary'] ? 'font-bold bg-gray-50 dark:bg-gray-800' : '' }}">
                                <td class="px-4 py-2">{{ $row['name'] }}</td>
                                <td class="px-4 py-2 text-right">{{ $row['courses'] }}</td>
                                <td class="px-4 py-2 text-right">{{ $row['partnerships'] }}</td>
                                <td class="px-4 py-2 text-right">{{ $row['enrollments'] }}</td>
                                <td class="px-4 py-2 text-right">{{ $row['students'] }}</td>
                                <td class="px-4 py-2 text-right">{{ $row['taught_hours'] }}</td>
                                <td class="px-4 py-2 text-right">{{ $row['sold_hours'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    @else
        <x-filament::section>
            <p class="text-sm text-gray-500">{{ __('No data available.') }}</p>
        </x-filament::section>
    @endif
</x-filament-panels::page>
