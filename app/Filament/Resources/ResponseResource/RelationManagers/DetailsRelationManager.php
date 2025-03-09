<?php

namespace App\Filament\Resources\ResponseResource\RelationManagers;

use App\Models\DetailResponse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('question_id')
                    ->relationship('questions', 'question_text')
                    ->label('Question')
                    ->disabled(),
                Forms\Components\TextInput::make('user_answer')
                    ->label('Answer')
                    ->disabled(),
                Forms\Components\TextInput::make('point')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->groups(['response.user.name'])
            ->recordTitleAttribute('response.user.name')
            ->columns([
                Tables\Columns\TextColumn::make('questions.question_text')
                    ->label('Questions')
                    ->wrap(),
                Tables\Columns\TextColumn::make('user_answer')->label('Answer'),
                Tables\Columns\TextColumn::make('correct_answer')->label('Correct'),
                Tables\Columns\TextColumn::make('point'),
                Tables\Columns\ToggleColumn::make('is_doubtful')
                    ->disabled(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
