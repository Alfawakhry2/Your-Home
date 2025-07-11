<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Estate;
use App\Models\Category;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class dashboardWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            #admins
            Stat::make('Admin' , User::where('type' , 'admin')->count())
            ->description('Adminstrator Of Website ')
            ->descriptionIcon('heroicon-o-user-circle' , IconPosition::Before)
            ->chart([1,1])
            ->color('info')
            ,

            #seller
            Stat::make('Seller' , User::where('type' , 'seller')->count())
            ->description('Seller That Own Estates ')
            ->descriptionIcon('heroicon-o-user' , IconPosition::Before)
            ->chart([1,2,3,4])
            ->color('success')
            ,


            #buyer
            Stat::make('Member' , User::where('type' , 'buyer')->count())
            ->description('Member That Can buy or rent estate ')
            ->descriptionIcon('heroicon-m-user-group' , IconPosition::Before)
            ->chart([1,2,3,4,5])
            ->color('danger')
            ,

            #Category
            Stat::make('Available Estate Types' , Category::count())
            ->description('This How Many Categories of Estates In our Website')
            ->descriptionIcon('heroicon-m-building-office-2' , IconPosition::Before)
            ->chart([3,1,2,3])
            ->color('success'),

            #Category
            Stat::make('Available Estates' , Estate::count())
            ->description('This How Many Estates In our Website')
            ->descriptionIcon('heroicon-m-building-office' , IconPosition::Before)
            ->chart([3,1,2,3])
            ->color('success')
            ,



        ];
    }
}
