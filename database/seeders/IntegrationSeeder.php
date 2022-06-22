<?php

namespace Leadobo\IntegrationTemplate\Database\Seeders;

use App\Models\Answer;
use App\Models\Conditional;
use App\Models\ConditionalRule;
use App\Models\Customer;
use App\Models\Input;
use App\Models\InputOption;
use App\Models\Progress;
use App\Models\RequirementOption;
use App\Models\Team;
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
                    ->has(Requirement::factory(['field'=>'campaign', 'label'=>'campaign'])
                        ->hasModels(['model_type'=>ModelAction::class, 'relationships'=>[Integration::class]]), 'requirements')
                    // Inputs:
                    ->has(Requirement::factory(['type'=>'input', 'field'=>'state', 'label'=>'State'])
                        ->hasModels(['model_type'=>Input::class,'relationships'=>[Team::class,Integration::class]]), 'requirements')
                    ->has(Requirement::factory(['type'=>'input', 'field'=>'isHungry', 'label'=>'isHungry', 'default'=>'very!'])
                        ->hasModels(['model_type'=>Input::class,'relationships'=>[Team::class,Integration::class]]), 'requirements')
                    ->has(Requirement::factory(['type'=>'input', 'field' => 'creditevaluation', 'label' => 'Credit Score'])
                        ->hasOptions(['label'=>'Excellent','value'=>1], 'options')
                        ->hasOptions(['label'=>'Good','value'=>2], 'options')
                        ->hasOptions(['label'=>'Some Problems','value'=>3], 'options')
                        ->hasOptions(['label'=>'Major Problems','value'=>4], 'options')
                        ->hasModels(['model_type'=>Input::class,'relationships'=>[Team::class,Integration::class]]), 'requirements')
                    ->has(Requirement::factory(['type'=>'input', 'field' => 'animals', 'label' => 'Pets', 'multiple'=>true])
                        ->hasOptions(['label'=>'cat','value'=>'meow'], 'options')
                        ->hasOptions(['label'=>'dog','value'=>'woof'], 'options')
                        ->hasOptions(['label'=>'lizard','value'=>'lizard'], 'options')
                        ->hasModels(['model_type'=>Input::class,'relationships'=>[Team::class,Integration::class]]), 'requirements')
                    ->has(Requirement::factory(['type'=>'input', 'field' => 'militaryVA', 'label' => 'Military'])
                        ->hasOptions(['label'=>'true','value'=>'served'], 'options')
                        ->hasOptions(['label'=>'false','value'=>'normie', 'default'=>true], 'options')
                        ->hasModels(['model_type'=>Input::class,'relationships'=>[Team::class,Integration::class]]), 'requirements')
                    ->has(Requirement::factory(['type'=>'input', 'field' => 'safeDriver', 'label' => 'Safe Driver'])
                        ->hasOptions(['label'=>'true','value'=>'safe'], 'options')
                        ->hasOptions(['label'=>'false','value'=>'deadly', 'default'=>true], 'options')
                        ->hasModels(['model_type'=>Input::class,'relationships'=>[Team::class,Integration::class]]), 'requirements')
            )
            ->has(
                Action::factory(['name'=>'ModelEvent', 'class'=>\Leadobo\IntegrationTemplate\Actions\ModelEvent::class, 'async'=>true])
                    ->has(
                        Conditional::factory([
                            'match' => 'ALL',
                            'target' => Customer::class,
                        ])
                            ->has(ConditionalRule::factory(['attribute' => 'email']), 'rules')
                        , 'conditionals'
                    )
            )
            ->create();

            // NOTE: Adds Validation Option to Phone Input?
            RequirementOption::factory(['label' => 'Some Validation', 'value' => 'customValidation'])
                ->for(Requirement::firstWhere('field','validation'), 'requirement')
            ->create();
    }
}
