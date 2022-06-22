<?php

namespace Leadobo\IntegrationTemplate\Actions;

use App\Actions\Leadobo\Action;

class PostbackS2S extends Action
{
    public function __invoke()
    {
        if (
            get_class($this->parent)!==\App\Models\ModelAction::class
            && $this->trigger==='listener'
        ) {
            $route = route('integration.action', [$this->modelAction]);
            return "fetch('{$route}')";
        }

        ray('DOING THE SCRIPT!');
        // DO THE SCRIPT

        return null; // TODO: Return Something?
    }
}
