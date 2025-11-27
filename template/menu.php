<ul class="nav nav-pills flex-column mb-auto">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
        <i class="fas fa-users-cog fa-2x me-2" style="color: var(--accent-200);"></i>
        <span class="fs-4">Susu Admin</span>
    </a>
    <hr>
    <li class="nav-item">
        <a href="?_main=dashboard" class="nav-link <?php echo ($_REQUEST['_main'] == 'dashboard') ? 'active' : ''; ?>"
            aria-current="page">
            <i class="fas fa-tachometer-alt fa-fw me-2"></i>
            Dashboard
        </a>
    </li>
    <li>
        <a href="?_main=members" class="nav-link <?php echo ($_REQUEST['_main'] == 'member') ? 'active' : ''; ?>">
            <i class="fas fa-users fa-fw me-2"></i>
            Members
        </a>
    </li>
    <li>
        <a href="?_main=contribution"
            class="nav-link <?php echo ($_REQUEST['_main'] == 'contribution') ? 'active' : ''; ?>">
            <i class="fas fa-hand-holding-usd fa-fw me-2"></i>
            Contribution
        </a>
    </li>
    <li>
        <a href="?_main=payout" class="nav-link <?php echo ($_REQUEST['_main'] == 'payout') ? 'active' : ''; ?>">
            <i class="fas fa-money-bill-wave fa-fw me-2"></i>
            Payout
        </a>
    </li>
    <li>
        <a href="?_main=report" class="nav-link <?php echo ($_REQUEST['_main'] == 'report') ? 'active' : ''; ?>">
            <i class="fas fa-chart-line fa-fw me-2"></i>
            Report
        </a>
    </li>
    <li>
        <a href="?_main=staffs" class="nav-link <?php echo ($_REQUEST['_main'] == 'staffs') ? 'active' : ''; ?>">
            <i class="fas fa-user-tie fa-fw me-2"></i>
            Staffs  
        </a>
    </li>
</ul>
<hr>
<div class="dropdown">
    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1"
        data-bs-toggle="dropdown" aria-expanded="false">
        <img src="https://via.placeholder.com/32" alt="" width="32" height="32" class="rounded-circle me-2">
        <strong><?=$username?></strong>
    </a>
    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
        <li><a class="dropdown-item" href="?_main=profile">Profile</a></li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item" href="?user=log-out">Sign out</a></li>
    </ul>
</div>