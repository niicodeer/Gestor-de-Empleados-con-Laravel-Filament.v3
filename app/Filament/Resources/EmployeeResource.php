<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\City;
use Filament\Tables;
use App\Models\State;
use App\Models\Country;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Filament\Resources\EmployeeResource\Widgets\EmployeeStatsOverview;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Section::make('Empleados')
              ->schema([
                Select::make('country_id')
                ->label('Pais')
                ->options(Country::all()->pluck('name', 'id')->toArray())
                ->required()
                ->reactive()
                ->afterStateUpdated(fn(callable $set)=> $set('state_id', null)),
                Select::make('state_id')
                ->label('Provincia/Estado')
                ->options(function(callable $get){
                  $country = Country::find($get('country_id'));
                  if(!$country){
                    return State::all()->pluck('name', 'id');
                  }
                  return $country->states->pluck('name', 'id');
                })
                ->reactive()
                ->afterStateUpdated(fn(callable $set)=> $set('city_id', null))
                ->required(),
                Select::make('city_id')
                ->label('Ciudad')
                ->options(function(callable $get){
                  $state = State::find($get('state_id'));
                  if(!$state){
                    return City::all()->pluck('name', 'id');
                  }
                  return $state->cities->pluck('name', 'id');
                })
                ->reactive()
                //->afterStateUpdated(fn(callable $set)=> $set('city_id', null))
                ->required(),
                Select::make('department_id')
                ->relationship('department', 'name')
                ->label('Departamento')
                ->required(),
                TextInput::make('first_name')
                ->label('Nombre')
                ->required()
                ->maxLength(255),
                TextInput::make('last_name')
                ->label('Apellido')
                ->required()
                ->maxLength(255),
                TextInput::make('address')
                ->label('Direccion')
                ->required()
                ->maxLength(255),
                TextInput::make('zip_code')
                ->label('Codigo Postal')
                ->required()
                ->maxLength(5),
                DatePicker::make('birth_date')
                ->label('Fecha Nacimiento')
                ->required(),
                DatePicker::make('date_hired')
                ->label('Fecha contratacion')
                ->required(),
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
              TextColumn::make('first_name')
              ->label('Nombre')
              ->sortable()
              ->searchable(),
              TextColumn::make('last_name')
              ->label('Apellido')
              ->sortable()
              ->searchable(),
              TextColumn::make('department.name')
              ->label('Departamento')
              ->sortable(),
              TextColumn::make('date_hired')
              ->label('Fecha creacion')
              ->date('d M Y'),
              TextColumn::make('created_at')
              ->label('Fecha creacion')
              ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('department')
                ->relationship('department', 'name'),
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

    public static function getWidgets(): array
    {
      return [
        EmployeeStatsOverview::class,
      ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
