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
                    <a href="{{route('admin.rental.index')}}"><i class='bx bx-list-ul'></i>Rental</a>
                </li>
                <li>
                    <a href="{{route('admin.rental.divisi')}}"><i class='lni lni-apartment'></i>Rental Divisi</a>
                </li>
                <li>
                    <a href="{{route('admin.rental.history')}}"><i class='bx bx-history'></i>Riwayat Rental</a>
                </li>
                <li>
                    <a href="{{route('admin.rental.problems')}}"><i class='lni lni-warning'></i>Pelanggaran</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="lni lni-users"></i>
                </div>
                <div class="menu-title">Pelanggan</div>
            </a>
            <ul>
                <li><a href="{{route('admin.customer.index')}}"><i class='lni lni-users'></i>Pelanggan</a>
                </li>
                <li><a href="{{route('admin.customer.create')}}"><i class='bx bx-user-plus'></i>Register
                        Pelanggan</a>
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
        <li class="menu-label">Item</li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-box'></i>
                </div>
                <div class="menu-title">Alat</div>
            </a>
            <ul>
                <li>
                    <a href="{{route('admin.item.index')}}">
                        <i class='bx bx-box'></i>
                        Alat
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.cat.index')}}">
                        <i class='bx bx-category'></i>
                        Kategori
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{route('admin.acces.index')}}">
                <div class="parent-icon">
                    <i class='bx bx-collection'></i>
                </div>
                <div class="menu-title">Aksesoris</div>
            </a>
        </li>
        <li>
            <a href="{{route('admin.sale')}}">
                <div class="parent-icon"><i class='bx bx-dollar'></i>
                </div>
                <div class="menu-title">Alat Dijual</div>
            </a>
        </li>
        <li>
            <a href="{{route('admin.access.sale')}}">
                <div class="parent-icon"><i class='bx bx-dollar'></i>
                </div>
                <div class="menu-title">Aksesoris Dijual</div>
            </a>
        </li>
        <li>
            <a href="{{route('admin.mainten.index')}}">
                <div class="parent-icon"><i class='bx bx-shield-quarter'></i>
                </div>
                <div class="menu-title">Perawatan</div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-cog'></i>
                </div>
                <div class="menu-title">Servis</div>
            </a>
            <ul>
                <li>
                    <a href="{{route('admin.service.index')}}">
                        <i class='bx bx-list-ul '></i>
                        Servis
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.service.history')}}">
                        <i class='bx bx-history'></i>
                        Riwayat Servis
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-label">Kelola Laporan</li>
        <li>
            <a href="{{route('admin.rental.report')}}">
                <div class="parent-icon"><i class='bx bx-file'></i>
                </div>
                <div class="menu-title">Laporan Rental</div>
            </a>
        </li>
        <li>
            <a href="{{route('admin.problem.report')}}">
                <div class="parent-icon"><i class='bx bx-file'></i>
                </div>
                <div class="menu-title">Laporan Pelanggaran</div>
            </a>
        </li>
        <li>
            <a href="{{route('admin.mainten.report')}}">
                <div class="parent-icon"><i class='bx bx-file'></i>
                </div>
                <div class="menu-title">Laporan Perawatan</div>
            </a>
        </li>
        <li>
            <a href="{{route('admin.report.service.index')}}">
                <div class="parent-icon"><i class='bx bx-file'></i>
                </div>
                <div class="menu-title">Laporan Servis</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->

</div>
