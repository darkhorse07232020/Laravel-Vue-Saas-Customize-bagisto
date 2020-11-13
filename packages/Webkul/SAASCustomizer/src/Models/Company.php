<?php

namespace Webkul\SAASCustomizer\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Webkul\SAASCustomizer\Contracts\Company as CompanyContract;
use Webkul\Core\Models\ChannelProxy;

class Company extends Model implements CompanyContract
{
    use Notifiable;

    protected $table = 'companies';

    protected $fillable = ['name', 'code', 'username','description', 'email', 'logo', 'domain', 'more_info', 'is_active', 'cname'];

    public function details()
    {
        return $this->hasOne(CompanyDetailsProxy::modelClass());
    }

    public function channels()
    {
        return $this->hasMany(ChannelProxy::modelClass());
    }
}