<?php

namespace App\Filament\Clusters\Settings;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class SettingsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.clusters.settings';

    public static function getNavigationLabel(): string
    {
        return __('Settings');
    }

    public function mount(): void
    {
        // Show the index page instead of redirecting to the first child
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('Settings');
    }
}
