<?php

namespace App\Listeners;

use App\Domain;
use App\Workflow;
use App\Environment;
use Illuminate\Auth\Events\Registered;

class CreateInitialDomainAndEnvironment
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $this->createInitialDomain(
            $event,
            $this->createInitialEnvironment($event),
        );

        $this->createInitialWorkflow($event);
    }

    /**
     * Create the initial environment.
     *
     * @param  \Illuminate\Auth\Events\Registered $event
     * @return \App\Environment
     */
    protected function createInitialEnvironment(Registered $event): Environment
    {
        $environment = new Environment([
            'name' => 'Live',
        ]);

        $environment->team()->associate($event->user->currentTeam());

        return tap($environment)->save();
    }

    /**
     * Create the initial workflow.
     *
     * @param  \Illuminate\Auth\Events\Registered $event
     * @return \App\Workflow
     */
    protected function createInitialWorkflow(Registered $event): Workflow
    {
        $workflow = new Workflow([
            'name' => 'Default workflow',
        ]);

        $workflow->team()->associate($event->user->currentTeam());

        return tap($workflow)->save();
    }

    /**
     * Create the initial domain.
     *
     * @param  \Illuminate\Auth\Events\Registered $event
     * @param  \App\Environment $environment
     * @return \App\Domain
     */
    protected function createInitialDomain(Registered $event, Environment $environment): Domain
    {
        $domain = new Domain([
            'domain_name' => Domain::createVanityDomainName(),
        ]);

        $domain->team()->associate($event->user->currentTeam());
        $domain->environment()->associate($environment);

        return tap($domain)->save();
    }
}
