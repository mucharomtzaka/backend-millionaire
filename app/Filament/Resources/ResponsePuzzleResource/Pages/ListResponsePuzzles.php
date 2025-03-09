<?php

namespace App\Filament\Resources\ResponsePuzzleResource\Pages;

use App\Filament\Resources\ResponsePuzzleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResponsePuzzles extends ListRecords
{
    protected static string $resource = ResponsePuzzleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
