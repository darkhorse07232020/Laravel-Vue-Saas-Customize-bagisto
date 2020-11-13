<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Velocity\Http\Controllers\Admin\Controller;
use Webkul\Velocity\Repositories\VelocityMetadataRepository;

class ConfigurationController extends Controller
{
    /**
     * VelocityMetadataRepository object
     *
     * @var \Webkul\Velocity\Repositories\VelocityMetadataRepository
     */
    protected $velocityMetaDataRepository;

    /**
     * Locale
     */
    protected $locale;

    /**
     * Channel
     */
    protected $channel;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Velocity\Repositories\MetadataRepository  $velocityMetaDataRepository
     * @return void
     */
    public function __construct (VelocityMetadataRepository $velocityMetadataRepository)
    {
        $this->_config = request('_config');

        $this->velocityHelper = app('Webkul\Velocity\Helpers\Helper');
        $this->velocityMetaDataRepository = $velocityMetadataRepository;

        $this->locale = request()->get('locale') ?: app()->getLocale();
        $this->channel = request()->get('channel') ?: 'default';
    }

    /**
     * @return \Illuminate\View\View
     */
    public function renderMetaData()
    {
        $velocityMetaData = $this->velocityHelper->getVelocityMetaData($this->locale, $this->channel, false);

        if (! $velocityMetaData) {
            $this->createMetaData($this->locale, $this->channel);

            $velocityMetaData = $this->velocityHelper->getVelocityMetaData($this->locale, $this->channel);
        }

        $velocityMetaData->advertisement = $this->manageAddImages(json_decode($velocityMetaData->advertisement, true) ?: []);

        return view($this->_config['view'], [
            'metaData' => $velocityMetaData,
        ]);
    }

    /**
     * @param  array  $addImages
     *
     * @return array
     */
    public function manageAddImages($addImages)
    {
        $imagePaths = [];

        foreach ($addImages as $id => $images) {
            foreach ($images as $key => $image) {
                if ($image) {
                    continue;
                }

                $imagePaths[$id][] = [
                    'id'   => $key,
                    'type' => null,
                    'path' => $image,
                    'url'  => Storage::url($image),
                ];
            }
        }

        return $imagePaths;
    }

    private function createMetaData($locale, $channel)
    {
        $this->velocityMetaDataRepository->create([
            'locale'                   => $locale,
            'channel'                  => $channel,

            'home_page_content'        => "<p>@include('shop::home.advertisements.advertisement-four')@include('shop::home.featured-products') @include('shop::home.product-policy') @include('shop::home.advertisements.advertisement-three') @include('shop::home.new-products') @include('shop::home.advertisements.advertisement-two')</p>",
            'footer_left_content'      => __('velocity::app.admin.meta-data.footer-left-raw-content'),

            'footer_middle_content'    => '<div class="col-lg-6 col-md-12 col-sm-12 no-padding"><ul type="none"><li><a href="{!! url(\'page/about-us\') !!}">About Us</a></li><li><a href="{!! url(\'page/cutomer-service\') !!}">Customer Service</a></li><li><a href="{!! url(\'page/whats-new\') !!}">What&rsquo;s New</a></li><li><a href="{!! url(\'page/contact-us\') !!}">Contact Us </a></li></ul></div><div class="col-lg-6 col-md-12 col-sm-12 no-padding"><ul type="none"><li><a href="{!! url(\'page/return-policy\') !!}"> Order and Returns </a></li><li><a href="{!! url(\'page/payment-policy\') !!}"> Payment Policy </a></li><li><a href="{!! url(\'page/shipping-policy\') !!}"> Shipping Policy</a></li><li><a href="{!! url(\'page/privacy-policy\') !!}"> Privacy and Cookies Policy </a></li></ul></div>',
            'slider'                   => 1,

            'subscription_bar_content' => '<div class="social-icons col-lg-6"><a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-facebook" title="facebook"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-twitter" title="twitter"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-linked-in" title="linkedin"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-pintrest" title="Pinterest"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-youtube" title="Youtube"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-instagram" title="instagram"></i></a></div>',

            'product_policy'           => '<div class="row col-12 remove-padding-margin"><div class="col-lg-4 col-sm-12 product-policy-wrapper"><div class="card"><div class="policy"><div class="left"><i class="rango-van-ship fs40"></i></div> <div class="right"><span class="font-setting fs20">Free Shipping on Order $20 or More</span></div></div></div></div> <div class="col-lg-4 col-sm-12 product-policy-wrapper"><div class="card"><div class="policy"><div class="left"><i class="rango-exchnage fs40"></i></div> <div class="right"><span class="font-setting fs20">Product Replace &amp; Return Available </span></div></div></div></div> <div class="col-lg-4 col-sm-12 product-policy-wrapper"><div class="card"><div class="policy"><div class="left"><i class="rango-exchnage fs40"></i></div> <div class="right"><span class="font-setting fs20">Product Exchange and EMI Available </span></div></div></div></div></div>',
        ]);
    }
}