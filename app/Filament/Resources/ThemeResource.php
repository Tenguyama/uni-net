<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThemeResource\Pages;
use App\Filament\Resources\ThemeResource\RelationManagers;
use App\Models\Language;
use App\Models\Theme;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use function Termwind\render;

class ThemeResource extends Resource
{
    protected static ?string $model = Theme::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $languages = Language::all();
        $result = array();
//        $result[] = Forms\Components\Section::make('ss')
//            ->schema([
//                TextInput::make('name')
//                ->label('Не назва'),
//            ]);
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
                Tables\Columns\TextColumn::make('themeDescription.language.locale')
                    ->label('Moва')
                    ->sortable(),
                Tables\Columns\TextColumn::make('themeDescription.name')
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
            'index' => Pages\ListThemes::route('/'),
            'create' => Pages\CreateTheme::route('/create'),
            'edit' => Pages\EditTheme::route('/{record}/edit'),
        ];
    }
}
