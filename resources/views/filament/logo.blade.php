<div style="display:flex;align-items:center;gap:0.625rem;">
    <img
        src="{{ asset('img/logo.png') }}"
        alt="Logo"
        style="height:2.25rem;width:2.25rem;object-fit:contain;flex-shrink:0;"
    >
    <div style="line-height:1.25;">
        <span style="
            font-size:1rem;
            font-weight:700;
            letter-spacing:-0.02em;
            display:block;
        " class="text-gray-900 dark:text-white logo-brand-title">
            {{ filament()->getBrandName() }}
        </span>
        <span style="font-size:0.65rem;letter-spacing:0.04em;text-transform:uppercase;font-weight:600;" class="logo-brand-subtitle text-gray-500 dark:text-gray-400">
            SIM PUSKESMAS
        </span>
    </div>
</div>
