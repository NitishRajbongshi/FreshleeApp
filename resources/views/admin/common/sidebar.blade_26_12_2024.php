<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                {{-- Logo Here --}}
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">Admin Control</span>
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
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.users') ? 'active' : '' }}">
            <a href="{{ route('admin.users') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-user-circle'></i>
                <div data-i18n="Analytics">Users</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.usermanagement') ? 'active' : '' }}">
            <a href="{{ route('admin.usermanagement') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-user-circle'></i>
                <div data-i18n="Analytics">User Role Management</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.allcropdetails') ? 'active' : '' }}">
            <a href="{{ route('admin.allcropdetails') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-user-circle'></i>
                <div data-i18n="Analytics">Crop Management</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.cropvarietydetails') ? 'active' : '' }}">
            <a href="{{ route('admin.cropvarietydetails') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-user-circle'></i>
                <div data-i18n="Analytics">Crop Variety</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.cropprotectiondetails') ? 'active' : '' }}">
            <a href="{{ route('admin.cropprotectiondetails') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-user-circle'></i>
                <div data-i18n="Analytics">Crop Control Measure</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.cropsymptomdetails') ? 'active' : '' }}">
            <a href="{{ route('admin.cropsymptomdetails') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-user-circle'></i>
                <div data-i18n="Analytics">Crop Symptom</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.office') ||
            Request::routeIs('admin.designation') ||
            Request::routeIs('admin.department') ||
            Request::routeIs('admin.role')
                ? 'open'
                : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="Master">Master</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('admin.office') ? 'active' : '' }}">
                    <a href="{{ route('admin.office') }}" class="menu-link">
                        <div data-i18n="Master">Office</div>
                    </a>
                </li>

                <li class="menu-item {{ Request::routeIs('admin.designation') ? 'active' : '' }}">
                    <a href="{{ route('admin.designation') }}" class="menu-link">
                        <div data-i18n="Master">Designation</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('admin.department') ? 'active' : '' }}">
                    <a href="{{ route('admin.department') }}" class="menu-link">
                        <div data-i18n="Master">Department</div>
                    </a>
                </li>

                <li class="menu-item {{ Request::routeIs('admin.role') ? 'active' : '' }}">
                    <a href="{{ route('admin.role') }}" class="menu-link">
                        <div data-i18n="Master">Role</div>
                    </a>
                </li>

            </ul>
        </li>

        {{-- Application Master --}}
        <li class="menu-item {{ Request::routeIs('admin.appmaster.croptype') ||
            Request::routeIs('admin.appmaster.cropinformation') ||
            Request::routeIs('admin.appmaster.cropdisease') ||
            Request::routeIs('admin.appmaster.suitability') ||
            // Request::routeIs('admin.appmaster.symptom') ||
            Request::routeIs('admin.appmaster.recommendation')
                ? 'open'
                : '' }}">

            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layer"></i>
                <div data-i18n="Application-Master">Application Master</div>
            </a>

            {{-- Corp Type --}}
            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('admin.appmaster.croptype') ? 'active' : '' }}">
                    <a href="{{ route('admin.appmaster.croptype') }}" class="menu-link">
                        <div data-i18n="Application-Master">Crop Type</div>
                    </a>
                </li>
            </ul>

            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('admin.appmaster.cropinformation') ? 'active' : '' }}">
                    <a href="{{ route('admin.appmaster.cropinformation') }}" class="menu-link">
                        <div data-i18n="Application-Master">Crop Name</div>
                    </a>
                </li>
            </ul>

            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('admin.appmaster.cropdisease') ? 'active' : '' }}">
                    <a href="{{ route('admin.appmaster.cropdisease') }}" class="menu-link">
                        <div data-i18n="Application-Master">Crop Disease</div>
                    </a>
                </li>
            </ul>

            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('admin.appmaster.suitability') ? 'active' : '' }}">
                    <a href="{{ route('admin.appmaster.suitability') }}" class="menu-link">
                        <div data-i18n="Application-Master">Suitability</div>
                    </a>
                </li>
            </ul>

            {{-- <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('admin.appmaster.symptom') ? 'active' : '' }}">
                    <a href="{{ route('admin.appmaster.symptom') }}" class="menu-link">
                        <div data-i18n="Application-Master">Symptom</div>
                    </a>
                </li>
            </ul> --}}

            <ul class="menu-sub">
                <li class="menu-item {{ Request::routeIs('admin.appmaster.recommendation') ? 'active' : '' }}">
                    <a href="{{ route('admin.appmaster.recommendation') }}" class="menu-link">
                        <div data-i18n="Application-Master">Recommendation</div>
                    </a>
                </li>
            </ul>

        </li>

        <li class="menu-item {{ Request::routeIs('admin.rolemanager') ? 'active' : '' }}">
            <a href="{{ route('admin.rolemanager') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-terminal'></i>
                <div data-i18n="Analytics">User Role Manager</div>
            </a>
        </li>

        {{-- Freshlee Market --}}
        <li class="menu-item {{ Request::routeIs('admin.user.order') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layer"></i>
                <div data-i18n="Application-Master" style="font-size: 0.9rem;">Freshlee Market</div>
            </a>

            <ul class="menu-sub">
                <a href="{{ route('admin.user.order') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-layer"></i>
                    <div data-i18n="Application-Master" style="font-size: 0.9rem;">User Order Details</div>
                </a>
            </ul>
        </li>
        {{-- Freshlee Master --}}
        <li class="menu-item {{ Request::routeIs('admin.freshlee.master.item') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layer"></i>
                <div data-i18n="Application-Master" style="font-size: 0.9rem;">Freshlee Master</div>
            </a>

            <ul class="menu-sub">
                <a href="{{ route('admin.freshlee.master.item') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-layer"></i>
                    <div data-i18n="Application-Master" style="font-size: 0.9rem;">Item Details</div>
                </a>
            </ul>
        </li>
        <li class="menu-item {{ Request::routeIs('admin.reviewcropimage') ? 'active' : '' }}">
            <a href="{{ route('admin.reviewcropimage') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-user-circle'></i>
                <div data-i18n="Analytics">Verify Diagnosis</div>
            </a>
        </li>



        <li class="menu-item {{ Request::routeIs('admin.filemanager') ? 'active' : '' }}">
            <a href="{{ route('admin.filemanager') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-folder-open'></i>
                <div data-i18n="Analytics">File Manager</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('moderator.queries') ? 'active' : '' }}">
            <a href="{{ route('moderator.queries') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxl-quora'></i>
                <div data-i18n="Analytics">Moderate Query</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('agriexpert.queriestoanswer') ? 'active' : '' }}">
            <a href="{{ route('agriexpert.queriestoanswer') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-pen'></i>
                <div data-i18n="Analytics">Answer Query</div>
            </a>
        </li>

        <li
            class="menu-item {{ Request::routeIs('agrinews.newsmanager') || Request::routeIs('agrinews.categorymanager') ? 'active' : '' }}">
            <a href="{{ route('agrinews.newsmanager') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-news'></i>
                <div data-i18n="Analytics">Agri News Manager</div>
            </a>
        </li>

        {{-- <li class="menu-item">
            <a href="{{ route('misHomePage') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-file-export'></i>
                <div data-i18n="Analytics">Disease Report</div>
            </a>
        </li> --}}

        <li class="menu-item {{ Request::routeIs('misHomePage') ? 'active' : '' }}">
            <a href="{{ route('misHomePage') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-file-export'></i>
                <div data-i18n="Analytics">Disease Report</div>
            </a>
        </li>

    </ul>
</aside>