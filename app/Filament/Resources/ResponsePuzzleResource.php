<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResponsePuzzleResource\Pages;
use App\Filament\Resources\ResponsePuzzleResource\RelationManagers;
use App\Models\ResponsePuzzle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResponsePuzzleResource extends Resource
{
    protected static ?string $model = ResponsePuzzle::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = "Puzzle Quiz";

    protected static ?int $navigationSort = 44;

    protected static ?string $label = 'Response Puzzle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('answer'),
                Tables\Columns\TextColumn::make('point'),
                Tables\Columns\TextColumn::make('puzzle.title'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResponsePuzzles::route('/'),
            'create' => Pages\CreateResponsePuzzle::route('/create'),
            'edit' => Pages\EditResponsePuzzle::route('/{record}/edit'),
        ];
    }
}
