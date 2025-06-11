<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ url('/dashboard') }}" style="color: #6777ef;">Peminjaman</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('/dashboard') }}">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="rounded" height="32">
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Umum</li>
            <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('/dashboard') }}"><i class="fas fa-fire"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            @if (auth()->check())
                @php
                    $role = auth()->user()->role;
                    $isAdminOrVillageHead = in_array($role, ['admin', 'village_head']);
                    $isAdminOrMidwifeOrVillageHead = in_array($role, ['admin', 'midwife', 'village_head']);
                    $isAdminOrOfficerOrVillageHead = in_array($role, ['admin', 'officer', 'village_head']);
                @endphp

                <li class="menu-header">Master</li>

                @if ($role !== 'peminjam')
                    <li class="{{ Request::is('categories*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/categories') }}">
                            <i class="fas fa-tags"></i>
                            <span>Master Kategori</span>
                        </a>
                    </li>
                @endif
                
                @if ($role !== 'peminjam')
                    <li class="{{ Request::is('items*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/items') }}">
                            <i class="fas fa-box"></i>
                            <span>Master Barang</span>
                        </a>
                    </li>
                @endif

                <li class="{{ Request::is('loans*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/loans') }}">
                        <i class="fas fa-hand-holding"></i>
                        <span>Peminjaman</span>
                    </a>
                </li>

                <li class="{{ Request::is('return*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/returns') }}">
                        <i class="fas fas fa-undo"></i>
                        <span>Pengembalian</span>
                    </a>
                </li>

                
            @endif

          
        </ul>

        <div class="my-4 px-3 hide-sidebar-mini">
            <!-- Help center modal button -->
            {{-- <button type="button" class="btn btn-success btn-lg btn-block btn-icon-split" data-toggle="modal"
                data-target="#helpCenterModal">
                <i class="fa-solid fa-comments"></i> Pusat Bantuan
            </button> --}}
        </div>
    </aside>
</div>
