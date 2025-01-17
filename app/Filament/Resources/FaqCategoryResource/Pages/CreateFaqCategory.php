<?php

namespace App\Filament\Resources\FaqCategoryResource\Pages;

use App\Filament\Resources\FaqCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFaqCategory extends CreateRecord
{
    protected static string $resource = FaqCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
