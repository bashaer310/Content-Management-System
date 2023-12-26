<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Comment;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CommentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CommentResource\RelationManagers;
use App\Filament\Resources\CommentResource\Pages\ManageComments;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-oval-left';
      
    protected static ?string $navigationGroup = 'My activity';
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('user_id',Auth()->user()->id)->count();
    }

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
            ->columns([
                TextColumn::make('comment'),
                TextColumn::make('post.title')->searchable(),
                TextColumn::make('created_at')->date()->sortable()->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                SelectFilter::make('post_id')
                ->label('post')
                ->relationship('post','title')
                ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', Auth()->user()->id));

    }

    public static function getPages(): array
    {
        return [
            'index' => ManageComments::route('/'),
        ];
    }
}
