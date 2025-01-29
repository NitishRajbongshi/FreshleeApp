<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo" style="font-size: 17px;">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                {{-- Logo Here --}}
            </span>
            <span class="app-brand-text demo menu-text fw-bolder my-2 text-capitalize">Freshlee</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner">
        <!-- Dashboard -->
        <li class="menu-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics" style="font-size: 0.8rem;">Dashboard</div>
            </a>
        </li>

        {{-- Only for Admin --}}
        @if (session()->has('roles'))
            @foreach (Session::get('roles') as $role)
                @if ($role == 'C') 
                    {{-- Freshlee Market --}}
                    <li class="menu-item {{ Request::routeIs('admin.user.order') ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-layer"></i>
                            <div data-i18n="Application-Master" style="font-size: 0.8rem;">Freshlee Market</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ Request::routeIs('admin.user.order') ? 'active' : '' }}">
                                <a href="{{ route('admin.user.order') }}" class="menu-link">
                                    <div data-i18n="Application-Master" style="font-size: 0.8rem;">User Order Details
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Freshlee Master --}}
                    <li class="menu-item {{ Request::routeIs('admin.master.item') ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-layer"></i>
                            <div data-i18n="Application-Master" style="font-size: 0.8rem;">Freshlee Master</div>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item {{ Request::routeIs('admin.master.item') ? 'active' : '' }}">
                                <a href="{{ route('admin.master.item') }}" class="menu-link">
                                    <div data-i18n="Application-Master" style="font-size: 0.8rem;">Item Details</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            @endforeach
        @endif

        {{-- Farmers Inventory --}}
        {{-- <li class="menu-item {{ Request::routeIs('farmer.stock') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layer"></i>
                <div data-i18n="Application-Master" style="font-size: 0.8rem;">Farmer's Inventory</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('farmer.stock') ? 'active' : '' }}">
                    <a href="{{ route('farmer.stock') }}" class="menu-link">
                        <div data-i18n="Application-Master" style="font-size: 0.8rem;">Farmer's Stock</div>
                    </a>
                </li>
            </ul>
        </li> --}}
    </ul>
</aside>
