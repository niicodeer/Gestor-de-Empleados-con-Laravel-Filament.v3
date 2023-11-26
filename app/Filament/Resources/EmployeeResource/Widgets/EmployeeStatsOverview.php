<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use App\Models\Country;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
      $arg = Country::where('country_code', 'ARG')->withCount('employees')->first();
      $uru = Country::where('country_code', 'URU')->withCount('employees')->first();
      $bra = Country::where('country_code', 'BRA')->withCount('employees')->first();

      return [
          Stat::make('Total Empleados', Employee::all()->count()),
          Stat::make('Empleados de '. $arg->name , $arg->employees_count),
          Stat::make('Empleados de '. $uru->name , $uru->employees_count),
          Stat::make('Empleados de '. $bra->name , $bra->employees_count),

        ];
    }
}
