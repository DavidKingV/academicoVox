<?php

namespace App\Filament\Pages;

use App\Models\Period;
use BackedEnum;
use Filament\Pages\Page;

class RhythmsReport extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-musical-note';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.pages.rhythms-report';

    public ?int $selectedPeriodId = null;

    /** @var array<int, array<string, mixed>> */
    public array $reportData = [];

    /** @var array<string, mixed> */
    public array $chartData = [];

    public function mount(): void
    {
        $this->selectedPeriodId = Period::get_default_period()?->id;
        $this->loadData();
    }

    public function updatedSelectedPeriodId(): void
    {
        $this->loadData();
    }

    protected function loadData(): void
    {
        if (! $this->selectedPeriodId) {
            return;
        }

        $period = Period::find($this->selectedPeriodId);

        if (! $period) {
            return;
        }

        $courseGroups = $period->courses()
            ->where('parent_course_id', null)
            ->with('rhythm')
            ->withCount('enrollments')
            ->get()
            ->where('enrollments_count', '>', 0)
            ->groupBy('rhythm_id');

        $data = [];
        $labels = [];
        $values = [];
        $colors = ['#f59e0b', '#3b82f6', '#10b981', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16', '#f97316', '#6366f1'];

        $i = 0;
        foreach ($courseGroups as $rhythmId => $courses) {
            $rhythmName = $courses->first()->rhythm->name ?? __('Other');
            $enrollmentCount = $courses->sum('enrollments_count');

            $data[] = [
                'rhythm' => $rhythmName,
                'enrollments' => $enrollmentCount,
            ];

            $labels[] = $rhythmName;
            $values[] = $enrollmentCount;
            $i++;
        }

        $this->reportData = $data;
        $this->chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $values,
                    'backgroundColor' => array_slice($colors, 0, count($values)),
                ],
            ],
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Reports');
    }

    public static function getNavigationLabel(): string
    {
        return __('Rhythms Report');
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return __('Rhythms Report');
    }
}
