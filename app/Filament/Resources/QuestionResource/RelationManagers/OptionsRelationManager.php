<?php

namespace App\Filament\Resources\QuestionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'options';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label_option')
                                    ->required()
                                    ->maxLength(10)
                                    ->dehydrateStateUsing(fn (string $state): string => ucwords($state))
                                    ->helperText('Example, A, B, C or Else'),
                Forms\Components\Toggle::make('is_correct')
                    ->label('Is Correct?')
                    ->inline(false),
                Forms\Components\Toggle::make('is_false')
                    ->label('Is False?')
                    ->inline(false),
                Forms\Components\TextInput::make('percent')
                    ->numeric()
                    ->minValue(0),
                Forms\Components\Textarea::make('text_option')
                    ->label('Text Option')
                    ->required()
                    ->maxLength(255)->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->directory('soal')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label_option')
            ->columns([
                Tables\Columns\TextColumn::make('label_option'),
                Tables\Columns\TextColumn::make('text_option')->wrap(),
                Tables\Columns\ToggleColumn::make('is_correct'),
                Tables\Columns\ToggleColumn::make('is_false'),
                Tables\Columns\TextColumn::make('percent'),
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
