        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">MobWash</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Menu
            </div>

            <!-- Nav Item - Transaction -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('trx.create') }}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>New Transaction</span></a>
            </li>
            <!-- Nav Item - Recap -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.recap') }}">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Recap</span></a>
            </li>

            <!-- Heading -->
            <div class="sidebar-heading">
                Setting
            </div>

            <!-- Nav Item - Transaction -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('crew') }}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Crew</span></a>
            </li>

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->
