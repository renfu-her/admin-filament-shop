<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = '網站管理';

    protected static ?string $navigationLabel = '會員管理';

    protected static ?string $modelLabel = '會員';

    protected static ?string $pluralModelLabel = '會員';

    // 這段程式碼結構正確，語法也沒問題，以下是詳細確認與建議：

    // 1. name、email、phone、address 欄位皆有設 required 及 maxLength(255)，這是常見做法，沒問題。
    // 2. email 欄位有加 ->email()，會自動驗證格式，正確。
    // 3. phone 欄位有加 ->tel()，這會讓輸入框型態為 tel，沒問題。
    // 4. gender 欄位用 Select，選項只有 male/female，且 required，沒問題。
    // 5. is_active 用 Toggle，且 columnSpanFull、inline(false)、required，這樣設計沒問題。
    // 6. address 欄位有 required 及 maxLength，沒問題。

    // 若要更嚴謹，可以考慮：
    // - 若未來 gender 可能有其他選項，建議 options 用 config 或 model 常數管理。
    // - 若 address 不是必填，可移除 required。
    // - 若 phone 有格式需求，可加 pattern 或自訂驗證規則。

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('姓名')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('電子郵件')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('電話')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('地址')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('gender')
                    ->label('性別')
                    ->options([
                        'male' => '男',
                        'female' => '女',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('啟用狀態')
                    ->columnSpanFull()
                    ->inline(false)
                    ->required(),
            ]);
    }

    // 這段 table 設定基本上沒有明顯問題，結構正確，語法也正確，常見需求都有覆蓋到。
    // 以下是簡單檢查與建議：

    // 1. gender 欄位的 formatStateUsing 只處理 'male' 與 'female'，若資料庫有其他值會報錯，建議加上 default。
    // 2. paginated([10,20,30,50,100,'all']) 這寫法正確，但要確認 'all' 是否支援於你目前的 Filament 版本。
    // 3. 其他部分如 actions、bulkActions、filters、toggleable 都沒問題。

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('姓名')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('電子郵件')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('電話')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('性別')
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        'male' => '男',
                        'female' => '女',
                        default => '未知',
                    }),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('啟用狀態'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('創建時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->label('性別')
                    ->options([
                        'male' => '男',
                        'female' => '女',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('啟用狀態'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10,20,30,50,100,'all'])
            ->defaultPaginationPageOption(20);
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

}
