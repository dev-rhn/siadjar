<?php

namespace App\Filament\Resources\UserManagement\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserManagementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengguna')
                    ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->placeholder('Masukkan nama pengguna')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->placeholder('Masukkan email pengguna')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->label('Password')
                    ->placeholder('Masukkan password pengguna')
                    ->password()
                    ->required(),
                    ])->columnSpanFull()
            ]);
    }
}
