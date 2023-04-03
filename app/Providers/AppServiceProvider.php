<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\ServiceProvider;
use App\Filament\Resources\AccountResource;

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
        Paginator::defaultView('vendor.pagination.tailwind');

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
