<div class="footer">
        <div class="footer-content">
            <div class="footer-list-container">
                @if ( $channel )
                    {!! DbView::make($channel)->field('footer_page_content')->render() !!}
                @endif
    
                <div class="list-container">
                    <?php
                        $current_locale = app()->getLocale();
                        
                        $super_channel_locales = [];
                        if ( isset($channel->locales)) {
                            $super_channel_locales = $channel->locales;
                        }

                        $query = parse_url(\Illuminate\Support\Facades\Request::path(), PHP_URL_QUERY);
                        $searchTerm = explode("&", $query);
    
                        foreach($searchTerm as $term){
                            if (strpos($term, 'term') !== false) {
                                $serachQuery = $term;
                            }
                        }
                    ?>
    
                    <span class="list-heading">{{ __('saas::app.tenant.footer.locale') }}</span>
                    <div class="form-container">
                        <div class="control-group">
                            <select class="control locale-switcher" onchange="window.location.href = this.value" @if (count($super_channel_locales) == 1) disabled="disabled" @endif>
    
                                @foreach ($super_channel_locales as $locale)
                                    @if (isset($serachQuery))
                                        <option value="?{{ $serachQuery }}&company-locale={{ $locale->code }}" {{ $locale->code == $current_locale ? 'selected' : '' }}>{{ $locale->name }}</option>
                                    @else
                                        <option value="?company-locale={{ $locale->code }}" {{ $locale->code == $current_locale ? 'selected' : '' }}>{{ $locale->name }}</option>
                                    @endif
                                @endforeach
    
                            </select>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    