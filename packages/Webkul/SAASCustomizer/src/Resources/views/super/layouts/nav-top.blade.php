<div class="navbar-top">
    <div class="navbar-top-left">
        <div class="brand-logo">
            <a href="{{ route('super.tenants.index') }}">
                @if (company()->getSuperConfigData('general.design.super.logo_image'))
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(company()->getSuperConfigData('general.design.super.logo_image')) }}" alt="{{ config('app.name') }}" style="height: 40px; width: 110px;"/>
                @else
                    <img src="{{ asset('vendor/webkul/ui/assets/images/logo.png') }}" alt="{{ config('app.name') }}"/>
                @endif
            </a>
        </div>
    </div>

    <div class="navbar-top-right">
        <div class="profile" style="padding-top: 5px;">
            @if(auth()->guard('super-admin')->check() && ! request()->is('company/*'))
                <div class="profile-info" style="padding:0px;">
                    <span class="list-heading" style="display: inline-block;padding: 8px;">{{ __('saas::app.super-user.layouts.locale') }}</span>
                    <?php
                        $query = parse_url(\Illuminate\Support\Facades\Request::path(), PHP_URL_QUERY);
                        $searchTerm = explode("&", $query);

                        foreach($searchTerm as $term){
                            if (strpos($term, 'term') !== false) {
                                $serachQuery = $term;
                            }
                        }

                        $current_locale = app()->getLocale();

                        $locales = app('Webkul\SAASCustomizer\Repositories\Super\LocaleRepository')->get();
                    ?>
                    <div class="form-container" style="width: 150px;display: inline-block;">
                        <div class="control-group" style="width: 150px;margin: 0px;">
                            <select class="control locale-switcher" onchange="window.location.href = this.value" @if (count($locales) == 1) disabled="disabled" @endif style="margin: 0px;border-radius: 0px;width:90%;">

                                @foreach ($locales as $locale)
                                    @if (isset($serachQuery))
                                        <option value="?{{ $serachQuery }}&super-locale={{ $locale->code }}" {{ $locale->code == $current_locale ? 'selected' : '' }}>{{ $locale->name }}</option>
                                    @else
                                        <option value="?super-locale={{ $locale->code }}" {{ $locale->code == $current_locale ? 'selected' : '' }}>{{ $locale->name }}</option>
                                    @endif
                                @endforeach

                            </select>
                        </div>
                    </div>
                </div>
                <div class="profile-info">
                    <div class="dropdown-toggle">
                        <div style="display: inline-block; vertical-align: middle;">
                            <span class="name">
                                {{ auth()->guard('super-admin')->user()->first_name }} {{ auth()->guard('super-admin')->user()->last_name }}
                            </span>
                        </div>
                        <i class="icon arrow-down-icon active"></i>
                    </div>

                    <div class="dropdown-list bottom-right">
                        <div class="dropdown-container">
                            <label>
                                {{ __('saas::app.super-user.layouts.account') }}
                            </label>

                            <ul>
                                <li>
                                    <a href="{{ route('saas.home.index') }}" target="_blank">{{ trans('saas::app.super-user.layouts.menu.view-front') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('super.agents.index') }}">{{ trans('saas::app.super-user.layouts.menu.account') }}</a>
                                </li>

                                <li>
                                    <a href="{{ route('super.session.destroy') }}">{{ trans('saas::app.super-user.layouts.menu.logout') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>