<?php

namespace App\Filament\Resources\OpnameResource\Pages;

use App\Filament\Resources\OpnameResource;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateOpname extends CreateRecord
{
    use HasWizard;

    protected static string $resource = OpnameResource::class;

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->contained(false)
            ])
            ->columns(null);
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Opname Details')
                ->schema([
                    Section::make()->schema(OpnameResource::getDetailsFormSchema())->columns(),
                ]),

            Step::make('Opname Items')
                ->schema([
                    Section::make()->schema([
                        OpnameResource::getProductsRepeater()
                    ])->columns(),
                ]),
        ];
    }
}
