<?php

namespace App\Filament\Resources\OpnameResource\Pages;

use App\Enums\OpnameStatus;
use App\Filament\Resources\OpnameResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOpname extends EditRecord
{
    protected static string $resource = OpnameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $status = $this->record->status->value;
        $opnameProducts = $this->record->opnameProducts;

        if ($status == OpnameStatus::Approved->value) {
            foreach ($opnameProducts as $opnameProduct) {
                $product = Product::find($opnameProduct->product_id);
                $product->stock = $opnameProduct->counted_quantity;
                $product->save();
            }
        }
    }
}
