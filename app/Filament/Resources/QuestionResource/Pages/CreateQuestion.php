<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use App\Models\Question;
use App\Models\Quiz;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CreateQuestion extends CreateRecord
{
    protected static string $resource = QuestionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Session::put('quiz_id', $data['quiz_id']);
        $data['quiz_id'] = $data['quiz_id'];

        return $data;
    }

    public function form(Form $form): Form
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
                            ->default(Session::get('quiz_id'))
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('no')
                            ->label('Number')
                            ->numeric()
                            ->default(function () {
                                if (Session::get('quiz_id'))
                                    return Question::where('quiz_id', Session::get('quiz_id'))->get()->count() + 1;
                            })
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
                            ->label('The Answer')
                            ->columnSpanFull()
                    ])->columns(3),

                //Repeater
                Forms\Components\Repeater::make('options')
                    ->relationship('options')
                    ->schema([
                        Forms\Components\Section::make()
                            ->columns([
                                'md' => 2,
                            ])
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('label_option')
                                            ->required()
                                            ->maxLength(10)
                                            ->dehydrateStateUsing(fn(string $state): string => ucwords($state))
                                            ->helperText('Example, A, B, C or Else'),
                                        Forms\Components\Toggle::make('is_correct')
                                            ->label('Is Correct?')
                                            ->inline(false),
                                        Forms\Components\Toggle::make('is_false')
                                            ->label('Is False?')
                                            ->inline(false),
                                    ])->columns(3),
                                Forms\Components\TextInput::make('percent')
                                    ->numeric(),
                                Forms\Components\Textarea::make('text_option')
                                    ->label('Text Option')
                                    ->required()
                                    ->maxLength(255)->columnSpanFull(),
                                Forms\Components\FileUpload::make('image')
                                    ->directory('soal')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->hidden(fn(Get $get): bool => $get('question_type') != 'choice')
                    ->columnSpanFull()
            ]);
    }
}
