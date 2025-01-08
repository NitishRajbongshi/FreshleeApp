<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
      <a href="{{route('moderator.dashboard')}}" class="app-brand-link">
        <span class="app-brand-logo demo">
          {{-- Logo Here --}}
        </span>
        <span class="app-brand-text demo menu-text fw-bolder ms-2">Moderator</span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
      </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

      {{-- Back to Admin Dashboard --}}
      @if(Session::get('current_role') == 'ADMIN')
      <li class="menu-item {{Request::routeIs('admin.dashboard') ? 'active' : ''}}">
        <a href="{{route('admin.dashboard')}}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-left-arrow"></i>
          <div data-i18n="Analytics"> Back to Admin </div>
        </a>
      </li>
      @endif

      <!-- Dashboard -->
      <li class="menu-item {{Request::routeIs('moderator.dashboard') ? 'active' : ''}}">
        <a href="{{route('moderator.dashboard')}}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Analytics">Dashboard</div>
        </a>
      </li>

      {{-- Moderate Query --}}
      <li class="menu-item {{Request::routeIs('moderator.queries') ? 'active' : ''}}">
        <a href="{{route('moderator.queries')}}" class="menu-link">
            <i class='menu-icon tf-icons bx bxl-quora'></i>
          <div data-i18n="Analytics">Moderate Query</div>
        </a>
      </li>

      {{-- Answer Queries --}}
      {{-- <li class="menu-item {{Request::routeIs('moderator.queriestoanswer') ? 'active' : ''}}">
        <a href="{{route('moderator.queriestoanswer')}}" class="menu-link">
            <i class='menu-icon tf-icons bx bxl-quora'></i>
          <div data-i18n="Analytics">Answer Query</div>
        </a>
      </li> --}}
      
    </ul>
  </aside>