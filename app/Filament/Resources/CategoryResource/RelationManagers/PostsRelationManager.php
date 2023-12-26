<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Post Info')->schema([

                    TextInput::make('title')->required()->maxLength(50),
                    RichEditor::make('content')->required()->maxLength(256)->columnSpanFull(),
                    FileUpload::make('image')->image()->required()->columnSpanFull(),
                ])->columns(2)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                ImageColumn::make('image')->circular(),
                TextColumn::make('title')->searchable(),
                TextColumn::make('user.name')->searchable()->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('category.name')->searchable(),
                TextColumn::make('created_at')->date()->sortable()->toggleable(isToggledHiddenByDefault:true),
                       ])
            ->filters([
                SelectFilter::make('user')->relationship('user','name')->native(false),
                SelectFilter::make('category')->relationship('category','name')->native(false),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();
             
                    return $data;
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),

            ])
            ->bulkActions([
            
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    
}
