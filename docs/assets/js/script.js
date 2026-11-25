// PustakaMart - Global Script & Static Auth Guard

// Immediate Auth Check Helper (called from <head> of protected pages)
function checkAuthGuard(requiredRole) {
    const role = localStorage.getItem('pm_auth_role');
    if (!role) {
        sessionStorage.setItem('pm_flash_error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        window.location.replace('login.html');
        return false;
    }
    if (requiredRole === 'admin' && role !== 'admin') {
        sessionStorage.setItem('pm_flash_error', 'Akses ditolak! Halaman tersebut khusus Administrator.');
        window.location.replace('user-dashboard.html');
        return false;
    }
    if (requiredRole === 'user' && role !== 'user') {
        window.location.replace('admin-dashboard.html');
        return false;
    }
    return true;
}

function pmLogout(e) {
    if (e) e.preventDefault();
    if (!confirm('Apakah Anda yakin ingin keluar dari akun PustakaMart?')) return;
    localStorage.removeItem('pm_auth_role');
    localStorage.removeItem('pm_auth_name');
    localStorage.removeItem('pm_auth_email');
    window.location.href = 'index.html';
}

document.addEventListener('DOMContentLoaded', () => {
    // Auto hide flash alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-8px)';
            setTimeout(() => alert.remove(), 500);
        }, 4500);
    });

    // Mobile hamburger menu
    const hamburger = document.getElementById('navbar-hamburger');
    const mobileMenu = document.getElementById('navbar-mobile-menu');
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', (e) => {
            e.stopPropagation();
            mobileMenu.classList.toggle('open');
            const isOpen = mobileMenu.classList.contains('open');
            hamburger.setAttribute('aria-expanded', isOpen);
        });
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.remove('open');
            }
        });
    }

    // Bind Logout links
    document.querySelectorAll('.logout-link, a').forEach(link => {
        if (link.textContent.trim() === 'Logout' && link.getAttribute('href') === 'index.html') {
            link.addEventListener('click', pmLogout);
        }
    });

    // Update dynamic user name display if logged in
    const authName = localStorage.getItem('pm_auth_name');
    if (authName) {
        document.querySelectorAll('[data-auth-name]').forEach(el => {
            el.textContent = authName;
        });
    }

    // Update Landing Page Navbar if already logged in
    const authRole = localStorage.getItem('pm_auth_role');
    const navActions = document.getElementById('public-nav-actions');
    const mobileAuthLinks = document.getElementById('mobile-auth-links');
    if (authRole && navActions) {
        const dashUrl = authRole === 'admin' ? 'admin-dashboard.html' : 'user-dashboard.html';
        navActions.innerHTML = `<a href="${dashUrl}" class="btn btn-outline btn-sm" style="border:1.5px solid #800000; color:#800000; font-weight:700; padding:6px 18px; border-radius:8px; text-decoration:none; background:white;">Dashboard</a>`;
        if (mobileAuthLinks) {
            mobileAuthLinks.innerHTML = `<a href="${dashUrl}" style="color:var(--primary); font-weight:700;">Dashboard Saya</a>`;
        }
    }
});
