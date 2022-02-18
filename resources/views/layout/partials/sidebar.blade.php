<header class="main-nav">
    <div class="sidebar-user text-center">
        <a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a><img class="img-90 rounded-circle" src="{{asset('assets/images/dashboard/1.png')}}" alt="" />
        <a href="user-profile">
            <h6 class="mt-3 f-14 f-w-600">
                Emay Walter
                {{-- TODO: Pseudo de l'utilisateur --}}
            </h6>
        </a>
    </div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav" href="#"><i data-feather="plus-square"></i><span>Create a new Kanban</span></a>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>My kanban</h6>
                        </div>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title  prefixActive('/dashboard') " href="javascript:void(0)"><i data-feather="home"></i><span>My first Kanban</span></a>
                        <ul class="nav-submenu menu-content" style="display:  prefixBlock('/dashboard') ;">
                            <li><a href="{{route('kanban.index')}}" class="routeActive('index')">Kanban</a></li>
                            <li><a href="{{route('kanban.chat')}}" class="routeActive('dashboard-02')">Chat & collab</a></li>
                            <li><a href="{{route('kanban.callendar')}}" class="routeActive('dashboard-02')">Callendar</a></li>
                            <li><a href="{{route('kanban.todo')}}" class="routeActive('dashboard-02')">To do</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title  prefixActive('/widgets') " href="javascript:void(0)"><i data-feather="airplay"></i><span>My second Kanban</span></a>
                        <ul class="nav-submenu menu-content"  style="display:  prefixBlock('/widgets') ;">
                            <li><a href="{{route('kanban.index')}}" class="routeActive('index')">Kanban</a></li>
                            <li><a href="{{route('kanban.chat')}}" class="routeActive('dashboard-02')">Chat & collab</a></li>
                            <li><a href="{{route('kanban.callendar')}}" class="routeActive('dashboard-02')">Callendar</a></li>
                            <li><a href="{{route('kanban.todo')}}" class="routeActive('dashboard-02')">To do</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Collaborative Kanban</h6>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title  prefixActive('/ui-kits') " href="javascript:void(0)"><i data-feather="box"></i><span>Create new house</span></a>
                        <ul class="nav-submenu menu-content" style="display:  prefixBlock('/ui-kits') ;">
                            <li><a href="{{route('kanban.index')}}" class="routeActive('index')">Kanban</a></li>
                            <li><a href="{{route('kanban.chat')}}" class="routeActive('dashboard-02')">Chat & collab</a></li>
                            <li><a href="{{route('kanban.callendar')}}" class="routeActive('dashboard-02')">Callendar</a></li>
                            <li><a href="{{route('kanban.todo')}}" class="routeActive('dashboard-02')">To do</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
