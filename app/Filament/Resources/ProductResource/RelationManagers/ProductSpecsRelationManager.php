<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProductSpecsRelationManager extends RelationManager
{
    protected static string $relationship = 'specs';

    protected static ?string $title = '商品規格';

    protected static ?string $modelLabel = '規格';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('規格名稱')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->label('價格')
                    ->required()
                    ->numeric()
                    ->prefix('NT$'),
                Forms\Components\TextInput::make('special_price')
                    ->label('優惠價')
                    ->numeric()
                    ->prefix('NT$')
                    ->nullable(),
                Forms\Components\TextInput::make('stock')
                    ->label('庫存')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                Forms\Components\TextInput::make('sort')
                    ->label('排序')
                    ->numeric()
                    ->default(0)
                    ->helperText('數字越小越靠前'),
                Forms\Components\Toggle::make('is_active')
                    ->label('啟用狀態')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('規格名稱')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('價格')
                    ->formatStateUsing(fn($state) => $state ? number_format($state, 0) : null)
                    ->prefix('NT ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('special_price')
                    ->label('優惠價')
                    ->formatStateUsing(fn($state) => $state ? number_format($state, 0) : null)
                    ->prefix('NT ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('庫存')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort')
                    ->label('排序')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('啟用狀態')
                    ->boolean(),
            ])
            ->defaultSort('sort', 'asc')
            ->reorderable('sort')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('規格狀態')
                    ->placeholder('全部規格')
                    ->trueLabel('已啟用')
                    ->falseLabel('已停用'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('新增規格'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('編輯'),
                Tables\Actions\DeleteAction::make()
                    ->label('刪除'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('刪除所選'),
                ]),
            ]);
    }
}
