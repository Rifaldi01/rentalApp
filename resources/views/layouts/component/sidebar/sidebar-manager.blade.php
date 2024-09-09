<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{URL::to('assets/images/icon.png')}}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text"><i>DND</i><i style="color:black">SURVEY</i></h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{url('/manager/dashboard/')}}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-grid-alt'></i>
                </div>
                <div class="menu-title">Rental</div>
            </a>
            <ul>
                <li>
                    <a href="{{route('manager.rental.index')}}"><i class='bx bx-list-ul'></i>List Rental</a>
                </li>
                <li>
                    <a href="{{route('manager.rental.hsty')}}"><i class='bx bx-history'></i>History Rental</a>
                </li>
                <li>
                    <a href="{{route('manager.rental.problems')}}"><i class='lni lni-warning'></i>Problem</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="lni lni-users"></i>
                </div>
                <div class="menu-title">Customer</div>
            </a>
            <ul>
                <li><a href="{{route('manager.customer.create')}}"><i class='bx bx-user-plus'></i>Register
                        Customer</a>
                </li>
                <li><a href="{{route('manager.customer.index')}}"><i class='lni lni-users'></i>List Customers</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-cog'></i>
                </div>
                <div class="menu-title">Service</div>
            </a>
            <ul>
                <li>
                    <a href="{{route('manager.service.index')}}">
                        <i class='bx bx-list-ul '></i>
                        Service
                    </a>
                </li>
                <li>
                    <a href="{{route('manager.service.history')}}">
                        <i class='bx bx-history'></i>
                        History Service
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-label">Items</li>
        <li>
            <a href="{{route('manager.item.index')}}">
                <div class="parent-icon"><i class='bx bx-box'></i>
                </div>
                <div class="menu-title">Items</div>
            </a>
        </li>
        <li>
            <a href="{{route('manager.acces.index')}}">
                <div class="parent-icon">
                    <i class='bx bx-collection'></i>
                </div>
                <div class="menu-title">Accessories</div>
            </a>
        </li>
        <li>
            <a href="{{route('manager.sale')}}">
                <div class="parent-icon"><i class='bx bx-dollar'></i>
                </div>
                <div class="menu-title">Item Sale</div>
            </a>
        </li>
        <li>
            <a href="{{route('manager.mainten.index')}}">
                <div class="parent-icon"><i class='bx bx-shield-quarter'></i>
                </div>
                <div class="menu-title">Maintenance</div>
            </a>
        </li>
        <li class="menu-label">Managemen</li>
        <li>
            <a href="{{route('manager.rental.report')}}">
                <div class="parent-icon"><i class='bx bx-file'></i>
                </div>
                <div class="menu-title">Report Rental</div>
            </a>
        </li>
        <li>
            <a href="{{route('manager.problem.report')}}">
                <div class="parent-icon"><i class='bx bx-file'></i>
                </div>
                <div class="menu-title">Report Problem</div>
            </a>
        </li>
        <li>
            <a href="{{route('manager.mainten.report')}}">
                <div class="parent-icon"><i class='bx bx-file'></i>
                </div>
                <div class="menu-title">Report Maintenance</div>
            </a>
        </li>
        <li>
            <a href="{{route('manager.report.service.index')}}">
                <div class="parent-icon"><i class='bx bx-file'></i>
                </div>
                <div class="menu-title">Report Service</div>
            </a>

    </ul>
</div>
