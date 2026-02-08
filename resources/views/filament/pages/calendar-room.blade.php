<x-filament-panels::page>
    <div class="flex flex-wrap items-end gap-4 mb-6">
        <div>
            <label for="room" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Room') }}</label>
            <select wire:model.live="selectedRoomId" id="room" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                <option value="">{{ __('Select a room...') }}</option>
                @foreach($rooms as $room)
                    <option value="{{ $room['id'] }}">{{ $room['name'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <x-filament::section>
        <div
            wire:ignore
            x-data="{ events: @js($events) }"
            x-init="
                const loadScript = (src) => new Promise((resolve) => {
                    if (document.querySelector(`script[src='${src}']`)) { resolve(); return; }
                    const s = document.createElement('script');
                    s.src = src;
                    s.onload = resolve;
                    document.head.appendChild(s);
                });

                const loadStyle = (href) => {
                    if (document.querySelector(`link[href='${href}']`)) return;
                    const l = document.createElement('link');
                    l.rel = 'stylesheet';
                    l.href = href;
                    document.head.appendChild(l);
                };

                loadStyle('https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css');

                const initFn = () => {
                    loadScript('https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js').then(() => {
                        const mapEvents = (evts) => evts.map(e => ({
                            id: e.id, title: e.title, start: e.start, end: e.end,
                            backgroundColor: e.color, borderColor: e.color,
                            extendedProps: { teacher: e.teacher, room: e.room }
                        }));

                        const calendar = new FullCalendar.Calendar($el, {
                            initialView: 'timeGridWeek',
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            },
                            events: mapEvents(events),
                            eventDidMount: function(info) {
                                const teacher = info.event.extendedProps.teacher;
                                if (teacher) {
                                    info.el.title = info.event.title + '\n' + teacher;
                                }
                            },
                            slotMinTime: '07:00:00',
                            slotMaxTime: '22:00:00',
                            allDaySlot: false,
                            locale: '{{ app()->getLocale() }}',
                            height: 'auto',
                        });
                        calendar.render();

                        Livewire.on('eventsUpdated', ({ events }) => {
                            calendar.removeAllEvents();
                            calendar.addEventSource(mapEvents(events));
                        });
                    });
                };

                initFn();
            "
        ></div>
    </x-filament::section>
</x-filament-panels::page>
