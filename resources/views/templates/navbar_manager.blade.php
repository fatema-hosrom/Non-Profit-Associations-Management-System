<div class="sidebar">
    <div class="sidebar-header">
        <img src="/assets/images/logos/logo.png" alt="شعار الجمعية">
        <h4>نظام إدارة الجمعيات</h4>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?php echo request()->is('manager/dashboard') ? 'active' : ''; ?>" href="<?php echo e(route('manager.dashboard')); ?>">
                <i class="fas fa-home"></i>
                الرئيسية
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo request()->is('manager/profile*') ? 'active' : ''; ?>" href="<?php echo e(route('manager.profile')); ?>">
                <i class="fas fa-user"></i>
                الملف الشخصي
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo request()->is('manager/activities*') ? 'active' : ''; ?>" href="<?php echo e(route('manager.activities.index')); ?>">
                <i class="fas fa-calendar-alt"></i>
                الفعاليات
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo request()->is('manager/organizations*') ? 'active' : ''; ?>" href="<?php echo e(route('manager.organizations.index')); ?>">
                <i class="fas fa-building"></i>
                الجمعيات
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo request()->is('manager/activity-volunteers*') ? 'active' : ''; ?>" href="<?php echo e(route('manager.activity_volunteers.index')); ?>">
                <i class="fas fa-user-plus"></i>
                إدارة المتطوعين بالفعالية
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo request()->is('manager/volunteers*') ? 'active' : ''; ?>" href="<?php echo e(route('manager.volunteers.index')); ?>">
                <i class="fas fa-users"></i>
                المتطوعين
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('logout.manager')); ?>">
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

    /* عرض الـ sidebar 280px — يجب أن يطابق الهامش حتى لا يختفي المحتوى خلفه */
    main.main-content {
        margin-right: 280px;
        padding: 16px;
        min-height: 100vh;
        position: relative;
        z-index: 1;
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
