@guest
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 0.5rem;">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height: 8rem; width: 8rem; object-fit: contain; border-radius: 1.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); border: 1px solid rgba(229, 231, 235, 0.5);">
        <h1 style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.025em; text-align: center; color: #0284c7;" class="dark:text-sky-400">
            {{ filament()->getBrandName() }}
        </h1>
    </div>
@else
    <div style="display: flex; align-items: center; gap: 0.75rem;">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height: 2.5rem; width: 2.5rem; object-fit: contain;">
        <span style="font-size: 1.25rem; font-weight: 700; letter-spacing: -0.025em;" class="text-gray-900 dark:text-white">
            {{ filament()->getBrandName() }}
        </span>
    </div>
@endguest
