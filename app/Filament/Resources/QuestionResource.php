<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Filament\Resources\QuestionResource\RelationManagers\OptionsRelationManager;
use App\Models\Question;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationGroup = "Module";

    protected static ?int $navigationSort = 22;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('quiz_id')
                            ->label('Select Quiz')
                            ->options(Quiz::where('user_id', Auth::user()->id)->pluck('title', 'id'))
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if ($state) {
                                    $no = Question::where('quiz_id', $state)->get()->count();
                                    $set('no', $no + 1);
                                }
                            })
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('no')
                            ->label('Number')
                            ->numeric()
                            ->readonly(),
                        Forms\Components\Textarea::make('question_text')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('image_url')
                            ->label('Upload Image')
                            ->directory('questions')
                            ->helperText('If Image available')->columnSpanFull(),
                        Forms\Components\TextInput::make('audio_url')
                            ->label('Link Audio')
                            ->helperText('If audio url available')
                            ->columnSpan(2),
                        Forms\Components\Select::make('question_type')
                            ->options([
                                'choice' => 'Multiple Choice',
                                'esay' => 'Esay'
                            ])
                            ->required()
                            ->live(),
                        Forms\Components\TextInput::make('point')
                            ->label('Point'),
                        Forms\Components\RichEditor::make('answer')
                            ->label('The Answer'),
                    ])
                    ->columns(3),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups(['quiz.title'])
            ->defaultGroup('quiz.title')
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('Number'),
                Tables\Columns\TextColumn::make('question_text')
                    ->label("Question")
                    ->wrap()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image_url'),
                Tables\Columns\TextColumn::make('question_type')
                    ->label('Type'),
                Tables\Columns\TextInputColumn::make('point')
                    ->label('Point')
                    ->disabled(fn($record) => auth()->user()->id == $record->user_id),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y'),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->required(),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            OptionsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('quiz.title')->columnSpanFull(),
                        Infolists\Components\TextEntry::make('question_text')->columnSpanFull(),
                        Infolists\Components\TextEntry::make('question_type'),
                        Infolists\Components\ImageEntry::make('image_url'),
                        Infolists\Components\TextEntry::make('audio_url'),
                        Infolists\Components\TextEntry::make('point')

                    ])->columns(3),
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\ViewEntry::make('options')
                            ->view('infolists.components.view-options')
                    ])
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('quiz', function($query){
            $query->where('user_id', Auth::id());
        });
    }

}
