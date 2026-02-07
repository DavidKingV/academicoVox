<?php

namespace App\Filament\Resources\ExternalCourses\Pages;

use App\Filament\Resources\ExternalCourses\ExternalCourseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageExternalCourses extends ManageRecords
{
    protected static string $resource = ExternalCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
