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
            <a href="{{url('/admin/dashboard/')}}">
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
                    <a href="{{route('admin.rental.create')}}"><i class='lni lni-timer'></i>Rental</a>
                </li>
                <li>
                    <a href="{{route('admin.rental.index')}}"><i class='bx bx-list-ul'></i>List Rental</a>
                </li>
                <li>
                    <a href="{{route('admin.rental.divisi')}}"><i class='lni lni-apartment'></i>Rental Divisi</a>
                </li>
                <li>
                    <a href="{{route('admin.rental.history')}}"><i class='bx bx-history'></i>History Rental</a>
                </li>
                <li>
                    <a href="{{route('admin.rental.problems')}}"><i class='lni lni-warning'></i>Problem</a>
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
                <li><a href="{{route('admin.customer.create')}}"><i class='bx bx-user-plus'></i>Register
                        Customer</a>
                </li>
                <li><a href="{{route('admin.customer.index')}}"><i class='lni lni-users'></i>List Customers</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{route('admin.pembayaran.index')}}">
                <div class="parent-icon"><i class='bx bx-dollar'></i>
                </div>
                <div class="menu-title">Pembayaran</div>
            </a>
        </li>
{{--        <li>--}}
{{--            <a href="{{route('admin.pembayaran.index')}}">--}}
{{--                <div class="parent-icon"><i class='bx bx-coin'></i>--}}
{{--                </div>--}}
{{--                <div class="menu-title">Poin</div>--}}
{{--            </a>--}}
{{--        </li>--}}
        <li class="menu-label">Items</li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-box'></i>
                </div>
                <div class="menu-title">Items</div>
            </a>
            <ul>
                <li>
                    <a href="{{route('admin.item.index')}}">
                        <i class='bx bx-box'></i>
                        Items
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.cat.index')}}">
                        <i class='bx bx-category'></i>
                        Category
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{route('admin.acces.index')}}">
                <div class="parent-icon">
                    <i class='bx bx-collection'></i>
                </div>
                <div class="menu-title">Accessories</div>
            </a>
        </li>
        <li>
            <a href="{{route('admin.sale')}}">
                <div class="parent-icon"><i class='bx bx-dollar'></i>
                </div>
                <div class="menu-title">Item Sale</div>
            </a>
        </li>
        <li>
            <a href="{{route('admin.mainten.index')}}">
                <div class="parent-icon"><i class='bx bx-shield-quarter'></i>
                </div>
                <div class="menu-title">Maintenance</div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-cog'></i>
                </div>
                <div class="menu-title">Service</div>
            </a>
            <ul>
                <li>
                    <a href="{{route('admin.service.index')}}">
                        <i class='bx bx-list-ul '></i>
                        Service
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.service.history')}}">
                        <i class='bx bx-history'></i>
                        History Service
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-label">Managemen</li>
        <li>
            <a href="{{route('admin.rental.report')}}">
                <div class="parent-icon"><i class='bx bx-file'></i>
                </div>
                <div class="menu-title">Report Rental</div>
            </a>
        </li>
        <li>
            <a href="{{route('admin.problem.report')}}">
                <div class="parent-icon"><i class='bx bx-file'></i>
                </div>
                <div class="menu-title">Report Problem</div>
            </a>
        </li>
        <li>
            <a href="{{route('admin.mainten.report')}}">
                <div class="parent-icon"><i class='bx bx-file'></i>
                </div>
                <div class="menu-title">Report Maintenance</div>
            </a>
        </li>
        <li>
            <a href="{{route('admin.report.service.index')}}">
                <div class="parent-icon"><i class='bx bx-file'></i>
                </div>
                <div class="menu-title">Report Service</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->

</div>
