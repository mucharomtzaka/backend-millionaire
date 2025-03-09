<?php

namespace App\Filament\Resources\PuzzleQuestionResource\Pages;

use App\Filament\Resources\PuzzleQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CreatePuzzleQuestion extends CreateRecord
{
    protected static string $resource = PuzzleQuestionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Session::put('puzzle_id', $data['puzzle_id']);
        $data['puzzle_id'] = $data['puzzle_id'];

        return $data;
    }
}
