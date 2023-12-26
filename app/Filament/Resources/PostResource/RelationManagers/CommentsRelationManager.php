<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use Filament\Forms;
use App\Models\Post;
use Filament\Forms\Components\Hidden;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Comment Info')->schema([

                TextInput::make('comment')->required()->maxLength(255),
                ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->columns([
                TextColumn::make('comment'),
                TextColumn::make('user.name')->searchable(),
                TextColumn::make('created_at')->date()->sortable()->toggleable(isToggledHiddenByDefault:true),

            ])
            ->filters([
                SelectFilter::make('user_id')
                ->label('user')
                ->relationship('user','name')
                ->native(false),
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
