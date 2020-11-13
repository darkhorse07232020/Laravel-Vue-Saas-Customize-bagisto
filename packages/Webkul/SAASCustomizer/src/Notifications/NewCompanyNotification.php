<?php

namespace Webkul\SAASCustomizer\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * New Company Notification Mail class
 *
 * @author  Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewCompanyNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Company
     */
    public $company;

    /**
     * @var Agent
     */
    public $agent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($company, $agent)
    {
        $this->company = $company;

        $this->agent = $agent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->agent->email)
                ->subject('New Company Registered')
                ->view('saas::emails.new-company')->with('company', $this->company);
    }
}