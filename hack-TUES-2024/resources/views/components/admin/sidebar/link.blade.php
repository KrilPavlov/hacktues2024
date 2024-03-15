<div class="menu-item">
    <a class="menu-link {{ $active ? 'active' : '' }}" href="{{ $route }}">
        <span class="menu-icon">
            {!! $icon !!}
        </span>
        <span class="menu-title" style="font-size: 15px;">{{ $slot }}</span>
    </a>
</div>
