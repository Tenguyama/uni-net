<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FakultResource\Pages;
use App\Filament\Resources\FakultResource\RelationManagers;
use App\Models\Fakult;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FakultResource extends Resource
{
    protected static ?string $model = Fakult::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $languages = Language::all();
        $result = array();
        $result[] = Forms\Components\Section::make('Основне')
            ->schema([
                TextInput::make('url')
                    ->required()
                    ->label('Посилання'),

            ]);
        foreach ($languages as $language) {
            $result[] = Forms\Components\Section::make($language->locale)
                ->id($language->id)
                ->schema([
                    TextInput::make('languages.'.$language->id.'.name')//'name')//
                        ->label('Назва (' . $language->locale . ')')
                        ->required(),
                ]);
        }

        return $form->schema($result);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->sortable()
                    ->url(fn ($record) => $record->url),
                Tables\Columns\TextColumn::make('fakultDescription.language.locale')
                    ->label('Moва')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fakultDescription.name')
                    ->label('Назва')
                    ->sortable(),
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
            'index' => Pages\ListFakults::route('/'),
            'create' => Pages\CreateFakult::route('/create'),
            'edit' => Pages\EditFakult::route('/{record}/edit'),
        ];
    }
}
