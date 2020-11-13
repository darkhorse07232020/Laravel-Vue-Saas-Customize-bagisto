<?php

namespace Webkul\SAASCustomizer\Observers\Core;

use Webkul\SAASCustomizer\Models\Core\Channel;

use Company;

class ChannelObserver
{
    public function creating(Channel $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }

    public function updating(Channel $channel)
    {
        if (! auth()->guard('super-admin')->check()) {
            $company = Company::getCurrent();

            if (($channel->hostname != $company->domain) && ($company->channel_id == $channel->id)) {
                session()->flash('warning', trans('saas::app.tenant.custom-errors.channel-hostname'));

                throw new \Exception('illegal_action');
            }
        }
    }

    public function deleting(Channel $channel)
    {
        if (! auth()->guard('super-admin')->check()) {
            $company = Company::getCurrent();

            if (($channel->hostname == $company->domain) && ($company->channel_id == $channel->id)) {
                throw new \Exception('illegal_action');
            }
        }
    }
}