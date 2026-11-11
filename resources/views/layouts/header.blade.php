@php
    $jumlahKeranjang = array_sum(array_map('intval', (array) session('keranjang', [])));
@endphp

<header class="main-header">
    <a href="{{ route('beranda') }}" class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Sansetya Florist" class="logo-image">
        <span class="logo-name">Sansetya Florist</span>
    </a>

    <div class="header-right">
        <nav class="navbar">
            <a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'aktif' : '' }}">Beranda</a>
            <a href="{{ route('beranda') }}#katalog">Katalog</a>
        </nav>

        <div class="header-actions">
            <a href="#" class="icon-button" title="Akun">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M5 21a7 7 0 0 1 14 0"/>
                </svg>
            </a>

            <a href="{{ route('keranjang.index') }}" class="icon-button" title="Keranjang">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3h2l2.4 12.5a2 2 0 0 0 2 1.5h8.8a2 2 0 0 0 2-1.5L22 8H6"/>
                    <circle cx="10" cy="21" r="1"/>
                    <circle cx="19" cy="21" r="1"/>
                </svg>
                @if ($jumlahKeranjang > 0)
                    <span class="badge-keranjang">{{ $jumlahKeranjang }}</span>
                @endif
            </a>
        </div>
    </div>
</header>