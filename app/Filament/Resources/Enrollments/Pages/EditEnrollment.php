<?php

namespace App\Filament\Resources\Enrollments\Pages;

use App\Filament\Resources\Enrollments\EnrollmentResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditEnrollment extends EditRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cancel')
                ->label(__('Cancel Enrollment'))
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->requiresConfirmation()
                ->modalDescription('This will cancel the enrollment and delete associated attendance records. This action cannot be undone.')
                ->action(function () {
                    $this->record->cancel();
                    $this->redirect(EnrollmentResource::getUrl('index'));
                }),
        ];
    }
}
