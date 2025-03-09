<?php

namespace App\Filament\Resources\ResponseResource\Pages;

use App\Filament\Resources\ResponseResource;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\Pages\EditRecord;

class EditResponse extends EditRecord
{
    protected static string $resource = ResponseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('User')
                            ->disabled(),
                        Forms\Components\Select::make('quiz_id')
                            ->relationship('quiz', 'title')
                            ->label('Quiz')
                            ->disabled(),
                        Forms\Components\TextInput::make('grade')
                            ->label('Grade')
                            ->disabled(),
                        Forms\Components\Toggle::make('finish')
                            ->label('Finish')
                            ->inline(false)
                            ->disabled(),
                    ])->columns(2)
            ]);
    }
}
