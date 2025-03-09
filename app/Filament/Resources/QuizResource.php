<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource\RelationManagers;
use App\Http\Controllers\QuizDuplicateController;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Infolists;
use Filament\Tables\Contracts\HasTable;
use stdClass;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationLabel = 'Quiz';

    protected static ?string $navigationGroup = "Module";

    protected static ?int $navigationSort = 21;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
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
                        Forms\Components\DateTimePicker::make('start_time'),
                        Forms\Components\DateTimePicker::make('end_time'),
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
                Tables\Columns\TextColumn::make('No')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string) (
                            $rowLoop->iteration +
                            ((int) $livewire->getTableRecordsPerPage() * (
                                $livewire->getTablePage() - 1
                            ))
                        );
                    }
                ),
                Tables\Columns\TextColumn::make('title')
                    ->description(fn(Quiz $record): string => Str::limit($record->description, 30))->wrap()
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state) Notification::make()
                            ->title('Quiz Published!')
                            ->success()
                            ->icon('heroicon-o-globe-alt')
                            ->iconColor('success')
                            ->send();
                        else
                            Notification::make()
                                ->title('Draft Quiz Success!')
                                ->success()
                                ->icon('heroicon-o-archive-box')
                                ->iconColor('info')
                                ->send();
                    }),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('questions_count')
                    ->label('Questions')
                    ->counts('questions'),
                Tables\Columns\TextColumn::make('start_time')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('end_time')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nominal'),
                Tables\Columns\TextColumn::make('duration')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('response_count')
                    ->label('Responses')
                    ->counts('response'),
                Tables\Columns\TextColumn::make('user.name'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'exercise' => 'Exercise',
                        'exam' => 'Exam'
                    ])
            ])
            ->actions([
                Tables\Actions\Action::make('Duplicate')
                    ->color('success')
                    ->action(function (Quiz $record) {
                        $dupl =  new QuizDuplicateController();
                        if($dupl->duplicate($record->id)){
                            Notification::make()
                                ->title('Duplicate Quiz Success!')
                                ->success()
                                ->icon('heroicon-o-check-circle')
                                ->iconColor('success')
                                ->send();
                        }
                        else {
                            Notification::make()
                                ->title('Duplicate Quiz Failed!')
                                ->success()
                                ->icon('heroicon-o-x-circle')
                                ->iconColor('danger')
                                ->send();
                        }
                    }),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('title'),
                Infolists\Components\TextEntry::make('description')
                    ->columnSpanFull(),
                Infolists\Components\TextEntry::make('start_time'),
                Infolists\Components\TextEntry::make('end_time'),
                Infolists\Components\TextEntry::make('status'),
                Infolists\Components\TextEntry::make('type'),
                Infolists\Components\TextEntry::make('user.name')

            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
    }


}
