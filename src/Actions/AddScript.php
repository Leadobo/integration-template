<?php

namespace Leadobo\IntegrationTemplate\Actions;

use App\Http\Controllers\Controller;
use App\Models\Funnel;
use App\Models\ModelAction;
use App\Models\Action;
use App\Models\Session;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Database\Eloquent\Model;

class AddScript extends Controller
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

        $initPixels = $this->teamIntegrations
            ->map(fn($tI) => $tI->settings->firstWhere('field','pixelId')->value ?? null)
            ->filter()->map(fn($pixel) => "fbq('init', '{$pixel}');")->implode("\n")
        ;

        // NOTE: This Only Makes Sense To Do On Render
        if ($this->trigger==='render' && get_class($this->parent)===Funnel::class) {
            return <<<JS
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');

            $initPixels
            JS;
        } elseif (get_class($this->parent)===\App\Models\Page::class) {
            $pageView = "fbq('track', 'PageView');";
            if ($this->trigger==='process') {
                session()->push('Actions.IntegrationTemplate.DoPixel', $pageView);
            }
            return $pageView;
        }

        return false;
    }
}
