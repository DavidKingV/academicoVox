<?php

namespace App\Filament\Resources\Leaves\Pages;

use App\Filament\Resources\Leaves\LeaveResource;
use App\Models\Leave;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;

    protected function getFormSchema(): array
    {
        return [
            Select::make('teacher_ids')
                ->label(__('Teachers'))
                ->relationship('teacher', 'id')
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                ->multiple()
                ->required()
                ->preload()
                ->searchable(),
            Select::make('leave_type_id')
                ->relationship('leaveType', 'name')
                ->required()
                ->preload(),
            DatePicker::make('start_date')
                ->label(__('Start date'))
                ->required(),
            DatePicker::make('end_date')
                ->label(__('End date'))
                ->required(),
        ];
    }

    protected function handleRecordCreation(array $data): Model
    {
        $teacherIds = $data['teacher_ids'] ?? [];
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $period = CarbonPeriod::create($startDate, $endDate);

        $lastLeave = null;

        foreach ($teacherIds as $teacherId) {
            foreach ($period as $date) {
                $lastLeave = Leave::create([
                    'teacher_id' => $teacherId,
                    'leave_type_id' => $data['leave_type_id'],
                    'date' => $date->format('Y-m-d'),
                ]);
            }
        }

        return $lastLeave ?? Leave::make();
    }
}
