 <div class="sidenav-menu">

<!-- Brand Logo -->
<a href="index.html" class="logo">
    <span class="logo-light">
        <span class="logo-lg"><img src="{{ asset('backend/assets/images/logo.png') }}" alt="logo"></span>
        <span class="logo-sm"><img src="{{ asset('backend/assets/images/logo-sm.png') }}" alt="small logo"></span>
    </span>

    <span class="logo-dark">
        <span class="logo-lg"><img src="{{ asset('backend/assets/images/logo-dark.png') }}" alt="dark logo"></span>
        <span class="logo-sm"><img src="{{ asset('backend/assets/images/logo-sm.png') }}" alt="small logo"></span>
    </span>
</a>

<!-- Sidebar Hover Menu Toggle Button -->
<button class="button-sm-hover">
    <i class="ri-circle-line align-middle"></i>
</button>

<!-- Full Sidebar Menu Close Button -->
<button class="button-close-fullsidebar">
    <i class="ti ti-x align-middle"></i>
</button>

<div data-simplebar>

    <!--- Sidenav Menu -->
    <ul class="side-nav">
        <li class="side-nav-title">
            Menu
        </li>

        <li class="side-nav-item">
            <a href="{{ route('dashboard') }}" class="side-nav-link">
                <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                <span class="menu-text"> Dashboard </span>
                <span class="badge bg-danger rounded-pill">9+</span>
            </a>
        </li>

         <li class="side-nav-item">
            <a href="{{ route('user.chat.index') }}" class="side-nav-link">
                <span class="menu-icon"><i class="ti ti-message"></i></span>
                <span class="menu-text"> Chat With Influencers  </span>
            </a>
        </li>


         <li class="side-nav-title mt-2">
            Tokens & Payment
        </li>

        <li class="side-nav-item">
            <a href="{{ route('user.plans') }}" class="side-nav-link">
                <span class="menu-icon"><i class="ti ti-message"></i></span>
                <span class="menu-text"> Buy Tokens   </span>
            </a>
        </li>

         <li class="side-nav-item">
            <a href="{{ route('user.transactions') }}" class="side-nav-link">
                <span class="menu-icon"><i class="ti ti-message"></i></span>
                <span class="menu-text"> Transaction History    </span>
            </a>
        </li>

        <li class="side-nav-item">
            <a href="{{ route('user.token.balance') }}" class="side-nav-link">
                <span class="menu-icon"><i class="ti ti-message"></i></span>
                <span class="menu-text"> Token Balance   </span>
            </a>
        </li>
 
        <li class="side-nav-title mt-2">
           Professional Coaching 
        </li>

        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#sidebarInvoice" aria-expanded="false" aria-controls="sidebarInvoice" class="side-nav-link">
                <span class="menu-icon"><i class="ti ti-invoice"></i></span>
                <span class="menu-text"> Expert Coaches </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="sidebarInvoice">
                <ul class="sub-menu">
                    <li class="side-nav-item">
                        <a href="{{ route('all.coaches') }}" class="side-nav-link">
                            <span class="menu-text">All Coaches </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="apps-invoice-details.html" class="side-nav-link">
                            <span class="menu-text">Career Coach </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="apps-invoice-create.html" class="side-nav-link">
                            <span class="menu-text">Fitness Coach </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="apps-invoice-create.html" class="side-nav-link">
                            <span class="menu-text">Finance Advisor  </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="apps-invoice-create.html" class="side-nav-link">
                            <span class="menu-text">Personal Chef </span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        

    </ul>

    <!-- Help Box -->
    <div class="help-box text-center">
        <h5 class="fw-semibold fs-16">Unlimited Access</h5>
        <p class="mb-3 text-muted">Upgrade to plan to get access to unlimited reports</p>
        <a href="javascript: void(0);" class="btn btn-danger btn-sm">Upgrade</a>
    </div>

    <div class="clearfix"></div>
</div>
</div>