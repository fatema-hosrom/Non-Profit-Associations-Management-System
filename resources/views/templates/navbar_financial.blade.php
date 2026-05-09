<div class="sidebar">
    <div class="sidebar-header">
        <img src="/assets/images/logos/logo.png" alt="شعار الجمعية">
        <h4>النظام المالي</h4>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?php echo request()->is('financial/dashboard') ? 'active' : ''; ?>" href="<?php echo e(route('financial.dashboard')); ?>">
                <i class="fas fa-home"></i>
                الرئيسية
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo request()->is('financial/profile*') ? 'active' : ''; ?>" href="<?php echo e(route('financial.profile')); ?>">
                <i class="fas fa-user"></i>
                الملف الشخصي
            </a>
        </li>
        <!-- Additional Financial Links -->
        <li class="nav-item">
            <a class="nav-link <?php echo request()->is('financial/donations*') ? 'active' : ''; ?>" href="<?php echo e(route('financial.donations.index')); ?>">
                <i class="fas fa-hand-holding-usd"></i>
                إدارة التبرعات
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo request()->is('financial/expenses*') ? 'active' : ''; ?>" href="<?php echo e(route('financial.expenses.index')); ?>">
                <i class="fas fa-receipt"></i>
                إدارة المصاريف
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo request()->is('financial/reports*') ? 'active' : ''; ?>" href="<?php echo e(route('financial.reports.index')); ?>">
                <i class="fas fa-chart-bar"></i>
                التقارير
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('logout.financial')); ?>">
                <i class="fas fa-sign-out-alt"></i>
                تسجيل الخروج
            </a>
        </li>
    </ul>
</div>
<link rel="stylesheet" href="/assets/css/bootstrap.css">

<style>
    .sidebar {
        position: fixed;
        right: 0;
        top: 0;
        width: 280px;
        height: 100vh;
        background: #2c3e50;
        color: white;
        padding-top: 20px;
        z-index: 1000;
    }

    .sidebar-header {
        text-align: center;
        padding: 20px 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-header img {
        width: 80px;
        height: 80px;
        margin-bottom: 15px;
    }

    .sidebar-header h4 {
        color: white;
        margin: 0;
        font-size: 1.2rem;
        font-weight: 500;
    }

    .nav-item {
        margin: 5px 15px;
    }

    .nav-link {
        color: #ecf0f1 !important;
        padding: 12px 20px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        transition: all 0.3s;
    }

    .nav-link i {
        margin-left: 10px;
        width: 20px;
        text-align: center;
        font-size: 1.1rem;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white !important;
    }

    .nav-link.active {
        background: #3498db;
        color: white !important;
    }

    main.main-content {
        margin-right: 280px;
        padding: 20px;
        min-height: 100vh;
    }

    .nav {
        padding-top: 10px;
    }

    .nav-item:last-child {
        margin-top: auto;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 15px;
    }

    .nav-link.active i {
        color: white;
    }
</style>
