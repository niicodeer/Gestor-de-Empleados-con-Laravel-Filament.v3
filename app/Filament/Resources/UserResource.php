<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup='User Managment';
    protected static ?int $navigationSort=5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Section::make('Usuario')
              ->schema([
                TextInput::make('name')
                ->label('Nombre')
                ->maxLength(255)
                ->required(),
                TextInput::make('email')
                ->label('Email')
                ->email()
                ->maxLength(255)
                ->required(),
                TextInput::make('password')
                ->label('Contraseña')
                ->password()
                ->minLength(8)
                ->same('passwordConfirmation')
                ->required(fn(Page $livewire): bool=> $livewire instanceof CreateRecord)
                ->dehydrateStateUsing(fn($state)=> Hash::make($state))
                ->dehydrated(fn($state)=> filled($state)),
                TextInput::make('passwordConfirmation')
                ->label('Confirmar contraseña')
                ->required(fn(Page $livewire): bool=> $livewire instanceof CreateRecord)
                ->minLength(8)
                ->password()
                ->dehydrated(false),
              ])
              ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              TextColumn::make('id')
              ->sortable(),
              TextColumn::make('name')
              ->label('Nombre')
              ->sortable()
              ->searchable(),
              TextColumn::make('email')
              ->label('Email')
              ->sortable()
              ->searchable(),
              TextColumn::make('created_at')
              ->label('Fecha creacion')
              ->dateTime(''),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
