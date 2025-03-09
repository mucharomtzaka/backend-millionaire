<?php

namespace App\Filament\Resources\PuzzleQuestionResource\Pages;

use App\Filament\Resources\PuzzleQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPuzzleQuestion extends EditRecord
{
    protected static string $resource = PuzzleQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
