@if(Auth::user()->profile_picture == null || empty(Auth::user()->profile_picture) || Auth::user()->profile_picture == '' )
@php $profile_pic = ''.url('vuexy/images/portrait/small/avatar-s-11.jpg').''; @endphp
@else
@php $profile_pic = ''.url('uploads/profile/'.Auth::user()->profile_picture).''; @endphp
@endif

<li class="nav-item navbar-dropdown dropdown-user dropdown">
    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
            <img src="{{$profile_pic}}" alt class="rounded-circle" />
        </div>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" href="#">
                <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar avatar-online">
                            <img src="{{$profile_pic}}" alt class="h-auto rounded-circle" />
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <span class="fw-semibold d-block">{{Auth::user()->name}}</span>
                        <small class="text-muted">Admin</small>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <div class="dropdown-divider"></div>
        </li>
        <li>
            <a class="dropdown-item" href="#">
                <i class="ti ti-user-check me-2 ti-sm"></i>
                <span class="align-middle">My Profile</span>
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="#">
                <i class="ti ti-settings me-2 ti-sm"></i>
                <span class="align-middle">Settings</span>
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="#">
                <span class="d-flex align-items-center align-middle">
                    <i class="flex-shrink-0 ti ti-credit-card me-2 ti-sm"></i>
                    <span class="flex-grow-1 align-middle">Billing</span>
                    <span class="flex-shrink-0 badge badge-center rounded-pill bg-label-danger w-px-20 h-px-20">2</span>
                </span>
            </a>
        </li>
        <li>
            <div class="dropdown-divider"></div>
        </li>
        <li>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button class="dropdown-item">
                    <i class="ti ti-logout me-2 ti-sm"></i>
                    Log Out
                </button>
            </form>
        </li>
    </ul>
</li>