<?php

namespace App\Filament\Resources\PuzzleQuestionResource\Pages;

use App\Filament\Resources\PuzzleQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPuzzleQuestions extends ListRecords
{
    protected static string $resource = PuzzleQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
