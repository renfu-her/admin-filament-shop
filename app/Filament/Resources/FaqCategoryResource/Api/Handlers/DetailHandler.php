<?php

namespace App\Filament\Resources\FaqCategoryResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\FaqCategoryResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\FaqCategoryResource\Api\Transformers\FaqCategoryTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = FaqCategoryResource::class;


    /**
     * Show FaqCategory
     *
     * @param Request $request
     * @return FaqCategoryTransformer
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');
        
        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        return new FaqCategoryTransformer($query);
    }
}
