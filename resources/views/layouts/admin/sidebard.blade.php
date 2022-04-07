<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="sidebar-background" style=""></div>
    <div class="scrollbar-inner" style="position: relative;z-index: 4">
        <div class="sidenav-header  align-items-center">
            <a class="navbar-brand" href="">
                <img src="{{ $companyCurrent->src_logo }}" class="navbar-brand-img" alt="" style="max-height: 55px; max-width: 150px; object-fit: contain">
            </a>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">
                    @includeWhen($userCurrent->isAdminOrSupervisor()  && request()->segment(1) === 'admin','layouts.admin.partials.general-sidebar')
                    @includeWhen($userCurrent->isCollaborator() && request()->segment(1) === 'user','layouts.admin.partials.my-sidebard')
                </ul>
            </div>
        </div>
    </div>

</nav>
