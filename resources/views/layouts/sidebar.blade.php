<!-- resources/views/layouts/sidebar.blade.php -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <i class="fas fa-building brand-icon"></i>
        <span class="brand-text font-weight-light">Property Manager</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Properties -->
                <li class="nav-item {{ Request::is('properties*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('properties*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>
                            Properties
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('properties.index') }}" class="nav-link {{ Request::is('properties') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Properties</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('properties.create') }}" class="nav-link {{ Request::is('properties/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Property</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Tenants -->
                <li class="nav-item {{ Request::is('tenants*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('tenants*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Tenants
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('tenants.index') }}" class="nav-link {{ Request::is('tenants') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Tenants</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tenants.create') }}" class="nav-link {{ Request::is('tenants/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Tenant</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Units -->
                <li class="nav-item">
                    <a href="{{ route('units.index') }}" class="nav-link {{ Request::is('units*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Units</p>
                    </a>
                </li>

                <!-- Payments -->
                <li class="nav-item {{ Request::is('payments*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('payments*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>
                            Payments
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('payments.index') }}" class="nav-link {{ Request::is('payments') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Payments</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('payments.create') }}" class="nav-link {{ Request::is('payments/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Record Payment</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Reports -->
                <li class="nav-item {{ Request::is('reports*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('reports*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>
                            Reports
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('reports.rent') }}" class="nav-link {{ Request::is('reports/rent') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rent Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reports.arrears') }}" class="nav-link {{ Request::is('reports/arrears') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Arrears Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reports.occupancy') }}" class="nav-link {{ Request::is('reports/occupancy') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Occupancy Report</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Expenses -->
                <li class="nav-item">
                    <a href="/expenses" class="nav-link">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>Expenses</p>
                    </a>
                </li>

                <!-- Staff Management -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Staff Management</p>
                    </a>
                </li>

                <!-- Notices -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>Notices</p>
                    </a>
                </li>

                <!-- Settings -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Settings</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>