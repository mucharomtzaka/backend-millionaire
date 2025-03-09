<?php

namespace App\Filament\Resources\GroupResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JoinsRelationManager extends RelationManager
{
    protected static string $relationship = 'joins';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Group')
                    ->required(),
                Forms\Components\Select::make('group_id')
                    ->label('Group')
                    ->relationship('group', 'name')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user_id')
            ->columns([
                Tables\Columns\TextColumn::make('group.name'),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\ToggleColumn::make('isAllow')
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state) Notification::make()
                            ->title('User On!')
                            ->success()
                            ->icon('heroicon-o-globe-alt')
                            ->iconColor('success')
                            ->send();
                        else
                            Notification::make()
                                ->title('User Off')
                                ->success()
                                ->icon('heroicon-o-archive-box')
                                ->iconColor('info')
                                ->send();
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
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
