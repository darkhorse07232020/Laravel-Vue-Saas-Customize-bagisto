@if (isset($config))
    <div class="aside-nav">
        <ul>
            @foreach ($config->items as $key => $item)
                <li class="{{ $item['key'] == request()->route('slug') ? 'active' : '' }}">
                    <a href="{{ route('super.configuration.index', $item['key']) }}">
                    {{ isset($item['name']) ? trans($item['name']) : '' }}

                        @if ($item['key'] == request()->route('slug'))
                            <i class="angle-right-icon"></i>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@else
    <div class="aside-nav">
        <ul>
            <?php $keys = explode('.', $menu->currentKey); ?>

            @if(isset($keys) && strlen($keys[0]))
                @foreach (\Illuminate\Support\Arr::get($menu->items, current($keys) . '.children') as $item)
                    <li class="{{ $menu->getActive($item) }}">
                        <a href="{{ $item['url'] }}">
                        {{ trans($item['name']) }}

                            @if ($menu->getActive($item))
                                <i class="angle-right-icon"></i>
                            @endif
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
@endif