<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\ServiceProvider;
use App\Filament\Resources\AccountResource;
use Illuminate\Support\Facades\Hash;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function () {
            // dd(Hash::make('secret214'));

            Filament::registerUserMenuItems([
                'account' => UserMenuItem::make()
                    ->url(auth()->check() == false ? null : AccountResource::getUrl('edit', auth()->user()))
                    ->label('Account'),
            ]);
        });
    }
}
