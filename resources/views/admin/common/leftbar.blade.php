<div class="iq-sidebar  rtl-iq-sidebar sidebar-default ">
    <div class="iq-sidebar-logo d-flex align-items-center justify-content-between">
        <a href="{{route('index')}}" class="header-logo">
            <img src="{{ asset('admin/images/logo.png') }}" class="img-fluid rounded-normal light-logo"
                alt="logo">
            {{-- <img src="{{ asset('admin/images/logo.png') }}" class="img-fluid rounded-normal darkmode-logo"
                alt="logo"> --}}
        </a>
        <div class="iq-menu-bt-sidebar">
            <i class="fa fa-bars wrapper-menu"></i>
        </div>
    </div>
    <!-- LEFT -->
    <div class="data-scrollbar" data-scroll="1">
        <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="iq-menu">
                <li class="{{ request()->routeIs(['home', 'index', 'dashboard']) ? 'active' : '' }}">
                    <a href="{{route('dashboard')}}">
                        <i class="fa fa-chart-pie"></i><span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs(['billsListing']) ? 'active' : '' }}">
                    <a href="{{route('billsListing')}}">
                        <i class="fas fa-list"></i><span>Energy Bills</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs(['telcoListing']) ? 'active' : '' }}">
                    <a href="{{route('telcoListing')}}">
                        <i class="fas fa-list"></i><span>Telco Bills</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="p-3"></div>
    </div>
</div>
