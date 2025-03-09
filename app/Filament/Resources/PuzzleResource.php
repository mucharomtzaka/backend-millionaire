<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PuzzleResource\Pages;
use App\Filament\Resources\PuzzleResource\RelationManagers;
use App\Models\Puzzle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use stdClass;

class PuzzleResource extends Resource
{
    protected static ?string $model = Puzzle::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = "Puzzle Quiz";

    protected static ?int $navigationSort = 41;

    protected static ?string $label = 'Title Puzzle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('type')
                            ->options([
                                'easy' => 'Easy',
                                'medium' => 'Medium',
                                'difficult' => 'Difficult'
                            ])
                            ->default('exam'),
                        Forms\Components\Toggle::make('status')
                            ->inline(false),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label("No")
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('title')
                    ->description(fn(Puzzle $record): string => Str::limit($record->description, 30))->wrap()
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state) Notification::make()
                            ->title('Puzzle Published!')
                            ->success()
                            ->icon('heroicon-o-globe-alt')
                            ->iconColor('success')
                            ->send();
                        else
                            Notification::make()
                                ->title('Draft Puzzle Success!')
                                ->success()
                                ->icon('heroicon-o-archive-box')
                                ->iconColor('info')
                                ->send();
                    }),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('question_puzzle_count')
                    ->label('Questions')
                    ->counts('question_puzzle'),
                Tables\Columns\TextColumn::make('user.name'),
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
            'index' => Pages\ListPuzzles::route('/'),
            'create' => Pages\CreatePuzzle::route('/create'),
            'edit' => Pages\EditPuzzle::route('/{record}/edit'),
        ];
    }
}
