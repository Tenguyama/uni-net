<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupResource\Pages;
use App\Filament\Resources\GroupResource\RelationManagers;
use App\Models\Fakult;
use App\Models\Group;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $languages = Language::all();
        $result = array();
        $result[] = Forms\Components\Section::make('Основне')
            ->schema([
                Forms\Components\Select::make('fakult_id')
                    ->label('Факультет')
                    ->options(Fakult::with('fakultDescription')->get()->map(function ($fakult) {
                        return [
                            'id' => $fakult->id,
                            'name' => $fakult->fakultDescription->implode('name', '/')
                        ];
                    })->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('fakult.fakultDescription.name')
                    ->label('Факультет')
                    ->sortable(),
                Tables\Columns\TextColumn::make('groupDescription.language.locale')
                    ->label('Moва')
                    ->sortable(),
                Tables\Columns\TextColumn::make('groupDescription.name')
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
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
        ];
    }
}
