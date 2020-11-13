<?php

namespace Webkul\SAASCustomizer\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\SAASCustomizer\Notifications\NewOrderNotification;

/**
 * Order event handler
 *
 * @author    Vivek Shamra <viveksh047@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderAgentMail
{

    /**
     * @param mixed $order
     *
     * Send new order Mail to the saas agent
     */
    public function handle($order)
    {

        try {
            if (company()->getSuperConfigData('general.agent.super.email')) {
                Mail::queue(new NewOrderNotification($order));
            }
        } catch (\Exception $e) {
            report($e);
        }
    }
}