<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Country;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CountryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CountryResource\RelationManagers;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationGroup='System Managment';
    protected static ?int $navigationSort=1;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
          Section::make('Country')
            ->schema([
              TextInput::make('name')
              ->label('Nombre')
              ->required()
              ->maxLength(255),
              TextInput::make('country_code')
              ->label('Codigo pais')
              ->required()
              ->maxLength(3),
            ])
            ->columns(1)
          ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              TextColumn::make('id')
              ->sortable(),
              TextColumn::make('country_code')
              ->label('Codigo pais')
              ->sortable()
              ->searchable(),
                TextColumn::make('name')
                ->label('Nombre')
                ->sortable()
              ->searchable(),
                TextColumn::make('created_at')
                ->label('Fecha creacion')
                ->dateTime('d M Y'),
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
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
