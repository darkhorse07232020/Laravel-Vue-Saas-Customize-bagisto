<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Super;

use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Event;
use Webkul\SAASCustomizer\Repositories\Super\ChannelRepository;

/**
 * ChannelController
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ChannelController extends Controller
{
    protected $_config;

    /**
     * SuperChannelRepository instance
     */
    protected $channelRepository;

    public function __construct(
        ChannelRepository $channelRepository
    )   {
        $this->_config = request('_config');

        $this->channelRepository = $channelRepository;

        $this->middleware('auth:super-admin');
    }

    /**
     * To show the login screen
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $superChannel = $this->channelRepository->findOrFail($id);

        return view($this->_config['view'], compact('superChannel'));
    }

    /**
     * To update the super channel
     */
    public function update($id)
    {
        $this->validate(request(), [
            'code' => ['required', 'unique:super_channel,code,' . $id, new \Webkul\SAASCustomizer\Contracts\Validations\Code],
            'name' => 'required',
            'locales' => 'required|array|min:1',
            'default_locale_id' => 'required',
            'currencies' => 'required|array|min:1',
            'base_currency_id' => 'required',
            'logo.*' => 'mimes:jpeg,jpg,bmp,png',
            'favicon.*' => 'mimes:jpeg,jpg,bmp,png',
            'hostname' => 'unique:super_channel,hostname,' . $id,
            'meta_title' => 'required|string|max:60',
            'meta_keywords' => 'required|string|max:160',
            'meta_description' => 'string|max:160',
        ]);
        
        $data = request()->all();

        $data['seo']['meta_title'] = $data['meta_title'];
        $data['seo']['meta_description'] = $data['meta_keywords'];
        $data['seo']['meta_keywords'] = $data['meta_description'];

        unset($data['meta_title']);
        unset($data['meta_keywords']);
        unset($data['meta_description']);

        $data['home_seo'] = json_encode($data['seo']);

        Event::dispatch('super.channel.update.before', $id);

        $channel = $this->channelRepository->update($data, $id);

        Event::dispatch('super.channel.update.after', $channel);

        session()->flash('success', trans('saas::app.super-user.settings.channels.update-success'));

        return redirect()->route($this->_config['redirect']);
    }
}