<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResponseResource\Pages;
use App\Filament\Resources\ResponseResource\RelationManagers\DetailsRelationManager;
use App\Models\Response;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use stdClass;

class ResponseResource extends Resource
{
    protected static ?string $model = Response::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            ->groups([
                'quiz.title'
            ])
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
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\ToggleColumn::make('is_finish')
                    ->label('Finish?')
                    ->disabled(),
                Tables\Columns\TextColumn::make('quiz.title')->wrap(),
                Tables\Columns\TextColumn::make('grade'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('quiz_id')
                    ->label('Title Of Quiz')
                    ->relationship('quiz', 'title',  function (Builder $query) {
                        return $query->where('status', Auth::user()->id);
                    }),
                Tables\Filters\Filter::make('is_finish')
                    ->toggle()
                    ->query(fn(Builder $query): Builder => $query->where('is_finish', true))
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
            DetailsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResponses::route('/'),
            // 'create' => Pages\CreateResponse::route('/create'),
            'edit' => Pages\EditResponse::route('/{record}/edit'),
        ];
    }

}
