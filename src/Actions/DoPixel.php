<?php

namespace Leadobo\IntegrationTemplate\Actions;

use App\Actions\Leadobo\Action;

class DoPixel extends Action
{
    public function __invoke()
    {
        $event = $this->modelAction->settings->firstWhere('field', 'event')->value ?? 'PageView';

        if ($this->modelAction->hasTeamIntegrations) {
            $scripts = $this->teamIntegrations->map(function ($tI) use ($event) {
                $pixel = $tI->settings->firstWhere('field', 'pixelId')->value ?? null;
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
