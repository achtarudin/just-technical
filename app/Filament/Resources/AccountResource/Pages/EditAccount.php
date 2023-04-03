<?php

namespace App\Filament\Resources\AccountResource\Pages;

use Closure;
use Filament\Forms\Components\Card;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\AccountResource;

class EditAccount extends EditRecord
{
    protected static string $resource = AccountResource::class;

    protected function getActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $newPassword = $data['new_password'] ?? null;
        $result = array_merge($data, [
            'password' => $newPassword ? Hash::make($newPassword) : $this->getRecord()->password,
            'updated_at' => now()
        ]);

        return $result;
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make()->columns('2')
                ->schema([
                    TextInput::make('name')
                        ->autocomplete('off')
                        ->label('Name')
                        ->required()
                        ->rules([
                            'required',
                            'string',
                            'min:4',
                        ]),

                    TextInput::make('email')
                        ->label('Email')
                        ->disabled()
                        ->dehydrated(false),
                ]),

            Card::make()->columns('2')
                ->schema([
                    TextInput::make('new_password')
                        ->autocomplete('off')
                        ->label('New password')
                        ->reactive()
                        ->rules([
                            'required',
                            'min:8',
                            'same:data.password_confirmation',
                        ], function (callable $get) {
                            return $get('new_password') != null;
                        })
                        ->dehydrated(function (callable $get) {
                            return $get('new_password') != null;
                        }),

                    TextInput::make('password_confirmation')
                        ->autocomplete('off')
                        ->label('Password confirmation')
                        ->rules(['required'], function (callable $get) {
                            return $get('new_password') != null;
                        })
                        ->disabled(function (callable $get) {
                            return $get('new_password') == null;
                        })
                        ->required(function (callable $get) {
                            return $get('new_password') != null;
                        })
                        ->hidden(fn (Closure $get) => $get('new_password') == null)
                        ->dehydrated(function (callable $get) {
                            return false;
                        })
                ])
        ];
    }
}
