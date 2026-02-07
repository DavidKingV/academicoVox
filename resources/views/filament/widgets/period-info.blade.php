<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">{{ __('Period Information') }}</x-slot>

        @php
            $data = $this->getData();
        @endphp

        <div class="space-y-4">
            @if($data['currentPeriod'])
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Current Period') }}</p>
                    <p class="text-lg font-semibold">{{ $data['currentPeriod']->name }}</p>
                </div>
            @endif

            @if($data['enrollmentsPeriod'])
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Enrollments Period') }}</p>
                    <p class="text-lg font-semibold">{{ $data['enrollmentsPeriod']->name }}</p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
