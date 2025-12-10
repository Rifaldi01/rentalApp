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
            <a href="{{url('employe/dashboard/')}}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        <li>

            <a href="{{route('employe.rental')}}">
                <div class="parent-icon"><i class='bx bx-history'></i>
                </div>
                <div class="menu-title">Rental
                    @if($rental > 0)
                        <span class="badge bg-danger">{{$rental}}</span>
                    @else
                    @endif
                </div>
            </a>
        <li>
        <li>
            <a href="{{route('employe.rental.history')}}">
                <div class="parent-icon">
                    <i class='bx bx-history'></i></div>
                <div class="menu-title">
                    History Rental
                </div>
            </a>
        </li>
        <a href="{{route('employe.rentaldivisi.index')}}">
            <div class="parent-icon"><i class='lni lni-apartment'></i>
            </div>
            <div class="menu-title">Rental Divisi</div>
        </a>
        </li>
        <li class="menu-label">Items</li>
        <li>
            <a href="{{route('employe.item.index')}}">
                <div class="parent-icon">
                    <i class='bx bx-box'></i>
                </div>
                <div class="menu-title">Items</div>
            </a>
        </li>
        <li>
            <a href="{{route('employe.acces')}}">
                <div class="parent-icon">
                    <i class='bx bx-collection'></i>
                </div>
                <div class="menu-title">Accessories</div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-history'></i>
                </div>
                <div class="menu-title">Riwayat Barang</div>
            </a>
            <ul>
                <li>
                    <a href="{{route('employe.item.itemin')}}"><i class='lni lni-timer'></i>Item</a>
                </li>
                <li>
                    <a href="{{route('employe.accessories.accesin')}}"><i class='bx bx-list-ul'></i>Accessories</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{route('employe.sale')}}">
                <div class="parent-icon"><i class='bx bx-dollar'></i>
                </div>
                <div class="menu-title">Item Sale</div>
            </a>
        </li>
        <li>
            <a href="{{route('employe.accesSale.index')}}">
                <div class="parent-icon"><i class='bx bx-dollar'></i>
                </div>
                <div class="menu-title">Accessories Sale</div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-shield-quarter'></i>
                </div>
                <div class="menu-title">Maintenance</div>
            </a>
            <ul>
                <li>
                    <a href="{{route('employe.mainten')}}"><i class="bx bx-box"></i> Item</a>
                    <a href="{{route('employe.mainten.access')}}"><i class="bx bx-collection"></i> Accessories</a>
                </li>
            </ul>
        </li>
    </ul>
    <!--end navigation-->
</div>
