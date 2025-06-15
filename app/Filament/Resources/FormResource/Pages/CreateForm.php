<?php

namespace App\Filament\Resources\FormResource\Pages;

use App\Filament\Resources\FormResource;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateForm extends CreateRecord
{
    use HasWizard;

    protected static string $resource = FormResource::class;

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->contained(false),
            ])
            ->columns(null);
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Product Request Details')
                ->schema([
                    Section::make()->schema(FormResource::getDetailsFormSchema())->columns(),
                ]),

            Step::make('Form Items')
                ->schema([
                    Section::make()->schema([
                        FormResource::getProductsRepeater(),
                    ])->columns(),
                ]),
        ];
    }
}
