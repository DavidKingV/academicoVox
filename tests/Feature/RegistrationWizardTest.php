<?php

namespace Tests\Feature;

use App\Livewire\RegistrationWizard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationWizardTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_wizard_accepts_photo_upload_without_missing_rules_error(): void
    {
        Livewire::test(RegistrationWizard::class)
            ->set('currentStep', 3)
            ->set('photo', UploadedFile::fake()->image('avatar.jpg'))
            ->assertHasNoErrors('photo');
    }
}
