<?php

namespace Leadobo\IntegrationTemplate\Actions;

use App\Http\Controllers\Controller;
use App\Models\ModelAction;
use App\Models\Action;
use App\Models\Session;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Database\Eloquent\Model;

class DoPixel extends Controller
{
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

        $event = $this->modelAction->settings->firstWhere('field','event')->value ?? 'PageView';

        if ($this->modelAction->hasTeamIntegrations) {
            $scripts = $this->teamIntegrations->map(function($tI) use ($event) {
                $pixel = $tI->settings->firstWhere('field','pixelId')->value ?? null;
                return "fbq('trackSingle', '{$pixel}', '{$event}');";
            })->implode("\n");
        } else {
            $scripts = "fbq('track', '{$event}');";
        }

        if ($this->trigger==='process') {
            session()->push('Actions.IntegrationTemplate.DoPixel', $scripts);
        }

        return $scripts;
    }
}
