<?php

namespace Webkul\SAASCustomizer\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\SAASCustomizer\Notifications\AgentRegistrationEmail;

/**
 * NewAgent registered events handler
 *
 * @author  Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AgentRegistered
{
    public function handle()
    {
        try {
            Mail::queue(new AgentRegistrationEmail(request()->all()));
        } catch (\Exception $e) {

        }
    }
}