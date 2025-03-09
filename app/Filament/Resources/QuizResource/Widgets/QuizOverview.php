<?php

namespace App\Filament\Resources\QuizResource\Widgets;

use App\Models\Quiz;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Permission\Models\Role;

class QuizOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $quiz = Quiz::count();
        $teachers = Role::where('name', 'teacher')->exists()
            ? User::role('teacher')->count()
            : 0;

        $students = Role::where('name', 'student')->exists()
            ? User::role('student')->count()
            : 0;
        return [
            Stat::make('Quiz', Quiz::count())
                ->description($quiz . ' increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Students', $students)
                ->description($students . ' increase')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('warning'),
            Stat::make('Teacher', $teachers)
                ->description($teachers . ' increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('gray'),
        ];
    }
}
