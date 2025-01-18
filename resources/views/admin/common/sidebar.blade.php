<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo" style="font-size: 17px;">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                {{-- Logo Here --}}
            </span>
            <span class="app-brand-text demo menu-text fw-bolder my-2">Admin Control</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner">
        <!-- Dashboard -->
        <li class="menu-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics" style="font-size: 0.8rem;">Dashboard</div>
            </a>
        </li>

        <!-- Admin Section -->
        <li
            class="menu-item {{ Request::routeIs('admin.office') || Request::routeIs('admin.designation') || Request::routeIs('admin.department') || Request::routeIs('admin.role') || Request::routeIs('admin.users') || Request::routeIs('admin.usermanagement') || Request::routeIs('admin.rolemanager') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="Master" style="font-size: 0.8rem;">Admin</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('admin.users') ? 'active' : '' }}">
                    <a href="{{ route('admin.users') }}" class="menu-link">
                        <div data-i18n="Users" style="font-size: 0.8rem;">Users</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('admin.usermanagement') ? 'active' : '' }}">
                    <a href="{{ route('admin.usermanagement') }}" class="menu-link">
                        <div data-i18n="User Role Management" style="font-size: 0.8rem;">User Role Management</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('admin.rolemanager') ? 'active' : '' }}">
                    <a href="{{ route('admin.rolemanager') }}" class="menu-link">
                        <div data-i18n="User Role Manager" style="font-size: 0.8rem;">User Role Manager</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('admin.office') ? 'active' : '' }}">
                    <a href="{{ route('admin.office') }}" class="menu-link">
                        <div data-i18n="Office" style="font-size: 0.8rem;">Office</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('admin.designation') ? 'active' : '' }}">
                    <a href="{{ route('admin.designation') }}" class="menu-link">
                        <div data-i18n="Designation" style="font-size: 0.8rem;">Designation</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('admin.department') ? 'active' : '' }}">
                    <a href="{{ route('admin.department') }}" class="menu-link">
                        <div data-i18n="Department" style="font-size: 0.8rem;">Department</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('admin.role') ? 'active' : '' }}">
                    <a href="{{ route('admin.role') }}" class="menu-link">
                        <div data-i18n="Role" style="font-size: 0.8rem;">Role</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Application Master Section --}}
        <li
            class="menu-item {{ Request::routeIs('admin.appmaster.*') ||
            Request::routeIs('admin.allcropdetails') ||
            Request::routeIs('admin.cropprotectiondetails') ||
            Request::routeIs('admin.cropsymptomdetails') ||
            Request::routeIs('admin.cropvarietydetails') ||
            Request::routeIs('admin.agriMedicinalProducts')
                ? 'active'
                : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layer"></i>
                <div data-i18n="Application-Master" style="font-size: 0.8rem;">Application Master</div>
            </a>

            <ul class="menu-sub">
                {{-- <li class="menu-item {{ Request::routeIs('admin.appmaster.croptype') ? 'active' : '' }}">
                    <a href="{{ route('admin.appmaster.croptype') }}" class="menu-link">
                        <div data-i18n="Crop Type" style="font-size: 0.8rem;">Crop Type</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('admin.appmaster.cropinformation') ? 'active' : '' }}">
                    <a href="{{ route('admin.appmaster.cropinformation') }}" class="menu-link">
                        <div data-i18n="Crop Information" style="font-size: 0.8rem;">Crop Information</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('admin.appmaster.cropdisease') ? 'active' : '' }}">
                    <a href="{{ route('admin.appmaster.cropdisease') }}" class="menu-link">
                        <div data-i18n="Crop Disease" style="font-size: 0.8rem;">Crop Disease</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('admin.appmaster.suitability') ? 'active' : '' }}">
                    <a href="{{ route('admin.appmaster.suitability') }}" class="menu-link">
                        <div data-i18n="Suitability" style="font-size: 0.8rem;">Suitability</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('admin.appmaster.symptom') ? 'active' : '' }}">
                    <a href="{{ route('admin.appmaster.symptom') }}" class="menu-link">
                        <div data-i18n="Symptom" style="font-size: 0.8rem;">Symptom</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('admin.appmaster.recommendation') ? 'active' : '' }}">
                    <a href="{{ route('admin.appmaster.recommendation') }}" class="menu-link">
                        <div data-i18n="Recommendation" style="font-size: 0.8rem;">Recommendation</div>
                    </a>
                </li> --}}

                <li class="menu-item {{ Request::routeIs('admin.allcropdetails') ? 'active' : '' }}">
                    <a href="{{ route('admin.allcropdetails') }}" class="menu-link">
                        <div data-i18n="Crop Management" style="font-size: 0.8rem;">Crop Management</div>
                    </a>
                </li>

                <li class="menu-item {{ Request::routeIs('admin.cropprotectiondetails') ? 'active' : '' }}">
                    <a href="{{ route('admin.cropprotectiondetails') }}" class="menu-link">
                        <div data-i18n="Control Measure" style="font-size: 0.8rem;">Control Measure Management</div>
                    </a>
                </li>

                <li class="menu-item {{ Request::routeIs('admin.cropsymptomdetails') ? 'active' : '' }}">
                    <a href="{{ route('admin.cropsymptomdetails') }}" class="menu-link">
                        <div data-i18n="Crop Symptom" style="font-size: 0.8rem;">Crop Symptom</div>
                    </a>
                </li>

                <li class="menu-item {{ Request::routeIs('admin.cropvarietydetails') ? 'active' : '' }}">
                    <a href="{{ route('admin.cropvarietydetails') }}" class="menu-link">
                        <div data-i18n="Crop Variety" style="font-size: 0.8rem;">Crop Variety</div>
                    </a>
                </li>

                <li class="menu-item {{ Request::routeIs('admin.agriMedicinalProducts') ? 'active' : '' }}">
                    <a href="{{ route('admin.agriMedicinalProducts') }}" class="menu-link">
                        <div data-i18n="Medicinal Products" style="font-size: 0.8rem;">Medicinal Products</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Freshlee Market --}}
        <li class="menu-item {{ Request::routeIs('admin.user.order') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layer"></i>
                <div data-i18n="Application-Master" style="font-size: 0.8rem;">Freshlee Market</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('admin.user.order') ? 'active' : '' }}">
                    <a href="{{ route('admin.user.order') }}" class="menu-link">
                        <div data-i18n="Application-Master" style="font-size: 0.8rem;">User Order Details</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Freshlee Master --}}
        <li class="menu-item {{ Request::routeIs('admin.freshlee.master.item') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layer"></i>
                <div data-i18n="Application-Master" style="font-size: 0.8rem;">Freshlee Master</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('admin.freshlee.master.item') ? 'active' : '' }}">
                    <a href="{{ route('admin.freshlee.master.item') }}" class="menu-link">
                        <div data-i18n="Application-Master" style="font-size: 0.8rem;">Item Details</div>
                    </a>
                </li>
            </ul>
        </li>

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

        <!-- Verify Diagnosis -->
        <li class="menu-item {{ Request::routeIs('admin.reviewcropimage') ? 'active' : '' }}">
            <a href="{{ route('admin.reviewcropimage') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-user-circle'></i>
                <div data-i18n="Analytics" style="font-size: 0.8rem;">Verify Diagnosis</div>
            </a>
        </li>

        <!-- File Manager -->
        <li class="menu-item {{ Request::routeIs('admin.filemanager') ? 'active' : '' }}">
            <a href="{{ route('admin.filemanager') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-folder-open'></i>
                <div data-i18n="Analytics" style="font-size: 0.8rem;">File Manager</div>
            </a>
        </li>

        <!-- Moderate Query -->
        <li class="menu-item {{ Request::routeIs('moderator.queries') ? 'active' : '' }}">
            <a href="{{ route('moderator.queries') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxl-quora'></i>
                <div data-i18n="Analytics" style="font-size: 0.8rem;">Moderate Query</div>
            </a>
        </li>

        <!-- Answer Query -->
        <li class="menu-item {{ Request::routeIs('agriexpert.queriestoanswer') ? 'active' : '' }}">
            <a href="{{ route('agriexpert.queriestoanswer') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-pen'></i>
                <div data-i18n="Analytics" style="font-size: 0.8rem;">Answer Query</div>
            </a>
        </li>

        <!-- Agri News Manager -->
        <li
            class="menu-item {{ Request::routeIs('agrinews.newsmanager') || Request::routeIs('agrinews.categorymanager') ? 'active' : '' }}">
            <a href="{{ route('agrinews.newsmanager') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-news'></i>
                <div data-i18n="Analytics" style="font-size: 0.8rem;">Agri News Manager</div>
            </a>
        </li>

        <!-- Disease Report -->
        <li class="menu-item {{ Request::routeIs('misHomePage') ? 'active' : '' }}">
            <a href="{{ route('misHomePage') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-file-export'></i>
                <div data-i18n="Analytics" style="font-size: 0.8rem;">Disease Report</div>
            </a>
        </li>
    </ul>
</aside>
