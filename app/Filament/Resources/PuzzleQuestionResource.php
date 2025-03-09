<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PuzzleQuestionResource\Pages;
use App\Filament\Resources\PuzzleQuestionResource\RelationManagers;
use App\Models\Puzzle;
use App\Models\PuzzleQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PuzzleQuestionResource extends Resource
{
    protected static ?string $model = PuzzleQuestion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = "Puzzle Quiz";

    protected static ?string $label = 'Questions Puzzle';

    protected static ?int $navigationSort = 42;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Select::make('puzzle_id')
                            ->label('Select Quiz')
                            ->options(Puzzle::where('user_id', Auth::user()->id)->pluck('title', 'id'))
                            ->reactive()
                            ->default(Session::get('puzzle_id'))
                            ->afterStateUpdated(function (Set $set, $state) {
                                if ($state) {
                                    $no = PuzzleQuestion::where('puzzle_id', $state)->get()->count();
                                    $set('no', $no + 1);
                                }
                            })
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('no')
                            ->label('Number')
                            ->numeric()
                            ->default(function () {
                                if (Session::get('puzzle_id'))
                                    return PuzzleQuestion::where('puzzle_id', Session::get('puzzle_id'))->get()->count() + 1;
                            })
                            ->readonly(),
                        Forms\Components\Textarea::make('clue')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('word')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('image_url')
                            ->label('Upload Image')
                            ->directory('questions')
                            ->helperText('If Image available')->columnSpanFull(),
                        Forms\Components\TextInput::make('point')
                            ->label('Point'),
                        Forms\Components\TextInput::make('letter_position')
                            ->numeric()
                            ->minValue(1)
                            ->label('Letter Position'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups(['puzzle.title'])
            ->defaultGroup('puzzle.title')
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No.'),
                Tables\Columns\TextColumn::make('clue')
                    ->label("Clue")
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('word')
                    ->label("Word")
                    ->searchable(),
                Tables\Columns\TextColumn::make('letter_position'),
                Tables\Columns\ImageColumn::make('image_url'),
                Tables\Columns\TextInputColumn::make('point')
                    ->label('Point')
                    ->disabled(fn($record) => auth()->user()->id == $record->user_id),
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
            'index' => Pages\ListPuzzleQuestions::route('/'),
            'create' => Pages\CreatePuzzleQuestion::route('/create'),
            'edit' => Pages\EditPuzzleQuestion::route('/{record}/edit'),
        ];
    }
}
