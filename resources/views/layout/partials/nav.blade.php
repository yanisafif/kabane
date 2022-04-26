<div class="page-main-header">
    <div class="main-header-right row m-0">
      <div class="main-header-left">
        <div class="logo-wrapper"><a href="{{ route('kanban.board') }}"><img class="img-fluid" src="{{asset('assets/images/logo/logo.png')}}" alt=""></a></div>
        <div class="dark-logo-wrapper"><a href="#"><img class="img-fluid" src="{{asset('assets/images/logo/dark-logo.png')}}" alt=""></a></div>
        <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center" id="sidebar-toggle">    </i></div>
      </div>
        <div class="nav-right col pull-right right-menu p-0">
            <ul class="nav-menus">
            <li>
                <a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a>
            </li>
            <li>
                <div class="mode"><i class="fa fa-moon-o"></i></div>
            </li>
            @if (auth()->user()->is_admin)
                <li class="onhover-dropdown p-0">
                    <a href="{{route('admin.panel')}}"><button class="btn btn-primary-light" type="button"><i data-feather="log-out"></i>Admin panel</button></a>
                </li>
            @endif

            <li class="onhover-dropdown p-0">
                <a href="{{route('user.logout')}}"><button class="btn btn-primary-light" type="button"><i data-feather="log-out"></i>Log out</button></a>
            </li>
        </ul>
    </div>
    <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
    </div>
</div>
