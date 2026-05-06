<div class="sidebar">
    <div class="sidebar-header">
        <img src="/assets/images/logos/logo.png" alt="شعار الجمعية">
        <h4>نظام إدارة الجمعيات</h4>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('supervisor/dashboard*') ? 'active' : '' }}"
                href="{{ route('supervisor.dashboard') }}" title="الذهاب إلى الرئيسية">
                <i class="fas fa-home"></i>
                الرئيسية
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('supervisor/profile*') ? 'active' : '' }}"
                href="{{ route('supervisor.profile') }}" title="عرض الملف الشخصي">
                <i class="fas fa-user"></i>
                الملف الشخصي
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('supervisor/managers*') ? 'active' : '' }}"
                href="{{ route('supervisor.managers.index') }}" title="إدارة المدراء">
                <i class="fas fa-users"></i>
                إدارة الأعضاء
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('supervisor/manager/assign-role') ? 'active' : '' }}"
                href="{{ route('supervisor.assignRole') }}" title="تحديد صلاحيات المدراء">
                <i class="fas fa-user-shield"></i>
                تحديد أدوار المدراء
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('supervisor/activities*') ? 'active' : '' }}"
                href="{{ route('supervisor.activities.index') }}" title="عرض الفعاليات">
                <i class="fas fa-bolt"></i>
                الفعاليات
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->is('supervisor/organizations*') ? 'active' : '' }}"
                href="{{ route('supervisor.organizations.index') }}" title="عرض الجمعيات">
                <i class="fas fa-building"></i>
                الجمعيات
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('supervisor/volunteers*') ? 'active' : '' }}"
                href="{{ route('supervisor.volunteers.index') }}" title="إدارة طلبات المتطوعين">
                <i class="fas fa-hands-helping"></i>
                طلبات المتطوعين
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout.supervisor') }}" title="تسجيل الخروج">
                <i class="fas fa-sign-out-alt"></i>
                تسجيل الخروج
            </a>
        </li>
    </ul>
</div>

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
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #3498db transparent;
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
        font-weight: 600;
    }

    .nav {
        padding-top: 10px;
    }

    .nav-item {
        margin: 5px 15px;
    }

    .nav-item:last-child {
        margin-top: auto;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 15px;
    }

    .nav-link {
        color: #ecf0f1 !important;
        padding: 12px 20px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        transition: all 0.3s;
        font-weight: 500;
    }

    .nav-link i {
        margin-left: 10px;
        width: 20px;
        text-align: center;
        font-size: 1.1rem;
        transition: all 0.3s;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white !important;
    }

    .nav-link.active {
        background: #3498db;
        color: white !important;
    }

    .nav-link.active i {
        color: white;
    }

    /* فقط عنصر الـ layout — تجنب تكرار الهامش عند وجود .main-content داخل الصفحة */
    main.main-content {
        margin-right: 280px;
        padding: 16px;
        min-height: 100vh;
        position: relative;
        z-index: 1;
    }
</style>
