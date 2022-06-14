<?php

namespace Leadobo\IntegrationTemplate\Actions;

use App\Actions\Leadobo\Action;

class ModelEvent extends Action
{
    public function handle()
    {
        ray('MODEL EVENT');

        return true;
    }
}
