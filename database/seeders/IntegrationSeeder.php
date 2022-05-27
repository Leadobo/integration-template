<?php

namespace Leadobo\IntegrationTemplate\Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Integration;
use App\Models\Action;
use App\Models\Requirement;

use App\Models\ModelAction;
use App\Models\TeamIntegration;

class IntegrationSeeder extends Seeder
{
    public function run()
    {
        // TODO: Probably Should Check If These Already Exists
        // NOTE: This Would Be Handled By The Migration Instead, So It Only Gets Seeded Once

        Integration::factory(['name'=>'IntegrationTemplate', 'type'=>'event', 'multiple'=>true])
            ->has(Requirement::factory(['field'=>'pixelId', 'label'=>'Pixel ID'])
                ->hasModels(['model_type'=>TeamIntegration::class, 'relationships'=>[Integration::class]]), 'requirements')
            ->has(Action::factory(['name'=>'addScript', 'class'=>\Leadobo\IntegrationTemplate\Actions\AddScript::class, 'multiple'=>false]))
            // TODO ^^^ Requirement To Have TeamIntegrations
            ->has(
                Action::factory(['name'=>'doPixel', 'class'=>\Leadobo\IntegrationTemplate\Actions\DoPixel::class])
                    // TODO: Requirement For The Page To Also Have The `addScript` Action
                    ->has(Requirement::factory(['field'=>'event', 'label'=>'Event'])
                        ->hasModels(['model_type'=>ModelAction::class, 'relationships'=>[Integration::class]]), 'requirements')
            )
            ->has(
                Action::factory(['name'=>'Postback', 'class'=>\Leadobo\IntegrationTemplate\Actions\PostbackS2S::class])
            )
            ->has(
                Action::factory(['name'=>'Process', 'class'=>\Leadobo\IntegrationTemplate\Actions\Process::class])
            )
            ->has(
                Action::factory(['name'=>'ProcessJob', 'class'=>\Leadobo\IntegrationTemplate\Actions\ProcessJob::class, 'async'=>true])
            )
            ->create();
    }
}
