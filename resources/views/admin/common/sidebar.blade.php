<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                {{-- Logo Here --}}
            </span>
            <span class="app-brand-text demo menu-text text-lg fw-bolder my-2 text-capitalize">Freshlee</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner">
        <!-- Dashboard -->
        <li class="menu-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link text-sm">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        {{-- Only for Admin --}}
        @if (session()->has('roles'))
            @foreach (Session::get('roles') as $role)
                @if ($role == 'A')
                    {{-- Freshlee Market --}}
                    <li
                        class="menu-item {{ Request::routeIs('admin.user.order') || Request::routeIs('admin.proxy.user.list') ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link text-sm menu-toggle">
                            <i class='menu-icon tf-icons bx bx-store'></i>
                            <div data-i18n="Application-Master">Freshlee Market</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ Request::routeIs('admin.user.order') ? 'active' : '' }}">
                                <a href="{{ route('admin.user.order') }}" class="menu-link text-sm">
                                    <div data-i18n="Application-Master">Manage User Order
                                    </div>
                                </a>
                            </li>
                            <li class="menu-item {{ Request::routeIs('admin.proxy.user.list') ? 'active' : '' }}">
                                <a href="{{ route('admin.proxy.user.list') }}" class="menu-link text-sm">
                                    <div data-i18n="Application-Master">Place User Order
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="menu-item {{ Request::routeIs('admin.inventory') ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link text-sm menu-toggle">
                            <i class='menu-icon tf-icons bx bxs-cart-alt'></i>
                            <div data-i18n="Application-Master">Freshlee Inventory</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ Request::routeIs('admin.inventory') ? 'active' : '' }}">
                                <a href="{{ route('admin.inventory') }}" class="menu-link text-sm">
                                    <div data-i18n="Application-Master">Manage Inventory
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Freshlee Master --}}
                    <li class="menu-item {{ Request::routeIs('admin.master.item') ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link text-sm menu-toggle">
                            <i class="menu-icon tf-icons bx bxs-data"></i>
                            <div data-i18n="Application-Master">Freshlee Master</div>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item {{ Request::routeIs('admin.master.item') ? 'active' : '' }}">
                                <a href="{{ route('admin.master.item') }}" class="menu-link text-sm">
                                    <div data-i18n="Application-Master">Item Details</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            @endforeach
        @endif
    </ul>
</aside>
