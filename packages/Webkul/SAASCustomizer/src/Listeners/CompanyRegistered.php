<?php

namespace Webkul\SAASCustomizer\Listeners;

use Company;
use Illuminate\Support\Facades\Mail;
use Webkul\SAASCustomizer\Notifications\NewCompanyNotification;
use Webkul\SAASCustomizer\Repositories\Super\AgentRepository;

/**
 * New company registered events handler
 *
 * @author  Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CompanyRegistered
{
    /**
     * AgentRepository Repository Object
     *
     * @var object
     */
    protected $agentRepository;

    /**
     * Create a new listener instance.
     *
     * @param  Webkul\SAASCustomizer\Repositories\Super\AgentRepository $agentRepository
     * @return void
     */
    public function __construct(
        AgentRepository $agentRepository
    )
    {
        $this->agentRepository = $agentRepository;
    }

    public function handle()
    {
        $agent = $this->agentRepository->all()->first();

        $company = Company::getCurrent();

        foreach(config('purge-pool') as $key => $pool) {
            $poolInstance = app($pool);

            try {
                $poolInstance->handle($company->id);
            } catch (\Exception $e) {
                continue;
            }
        }

        try {
            Mail::queue(new NewCompanyNotification($company, $agent));
        } catch (\Exception $e) {

        }
    }
}