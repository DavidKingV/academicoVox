<x-filament-panels::page>
    <x-filament::section>
        <div class="flex flex-wrap items-end gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Period') }}</label>
                <select wire:model.live="selectedPeriodId" class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                    @foreach(\App\Models\Period::all() as $period)
                        <option value="{{ $period->id }}">{{ $period->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Course') }}</label>
                <select wire:model.live="selectedCourseId" class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                    @foreach($availableCourses as $course)
                        <option value="{{ $course['id'] }}">{{ $course['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </x-filament::section>

    @if(!empty($chartData) && !empty($chartData['labels']))
        <x-filament::section class="mb-6">
            <x-slot name="heading">{{ $courseName }}</x-slot>
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
                                scales: {
                                    x: { stacked: true },
                                    y: { stacked: true, beginAtZero: true },
                                },
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
    @else
        <x-filament::section>
            <p class="text-sm text-gray-500">{{ __('No attendance data available for the selected course.') }}</p>
        </x-filament::section>
    @endif
</x-filament-panels::page>
