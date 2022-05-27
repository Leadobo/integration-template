<?php

namespace Leadobo\IntegrationTemplate\Actions;

use App\Actions\Leadobo\Action;

class ProcessJob extends Action
{
    public function handle()
    {
        sleep(5);
        ray('do something async');
    }
}
