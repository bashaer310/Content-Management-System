<?php

namespace App\Filament\Resources;

use Filament\Forms;

use App\Models\Post;
use Filament\Tables;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use function Laravel\Prompts\select;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Resources\PostResource\Pages\ViewPost;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use Illuminate\Support\Facades\Auth;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    
    protected static ?string $navigationGroup = 'Content';
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Post Info')->schema([

                    TextInput::make('title')->required()->maxLength(50),
                    Select::make('category_id')->required()
                    ->relationship('category','name')
                    ->native(false),
                    RichEditor::make('content')->required()->maxLength(256)->columnSpanFull(),
                    FileUpload::make('image')->image()->required()->columnSpanFull(),
                    //like
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
            ]);

    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'view' => ViewPost::route('/{record}'),
            //'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
