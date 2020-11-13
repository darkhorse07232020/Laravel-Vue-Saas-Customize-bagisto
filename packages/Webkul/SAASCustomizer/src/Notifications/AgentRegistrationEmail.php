<?php

namespace Webkul\SAASCustomizer\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Registration Mail class
 *
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AgentRegistrationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->data['email'])
                ->subject(trans('saas::app.super-user.mail.agent-registration'))
                ->view('saas::emails.super-admin.agent-registration')->with('agent', $this->data);
    }
}