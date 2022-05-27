<?php

namespace Leadobo\IntegrationTemplate\Actions;

use App\Models\Session;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\ModelAction;
use App\Models\Action;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Database\Eloquent\Model;

class ProcessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ModelAction $modelAction;
    public Action $action;
    public DatabaseCollection $teamIntegrations;
    public string $trigger; // TODO: ENUM

    public Model $parent;
    public Session $session;

    public function __construct(ModelAction $modelAction, Model $parent, Session $session) {
        $this->modelAction = $modelAction;
        $this->action = $modelAction->action;
        $this->teamIntegrations = $modelAction->teamIntegrations;
        $this->trigger = $modelAction->trigger;

        $this->parent = $parent;
        $this->session = $session;
    }

    public function __invoke() {
        $this::dispatch($this->modelAction, $this->parent, $this->session);
    }

    public function handle()
    {
        sleep(5);
        ray('do something async');
    }
}
