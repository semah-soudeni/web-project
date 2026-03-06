/**
 * auth.js – Shared authentication utilities
 * Include this script on every page that needs auth-aware UI.
 *
 * What it does:
 *  1. On load: calls /backend/session_check.php
 *  2. If logged in  → shows user name + Logout button, hides Sign In / Sign Up
 *  3. If logged out → shows Sign In / Sign Up buttons, hides user area
 *
 * Expects the navbar to contain elements with these IDs:
 *   #nav-signin-btn   – "Sign In" button
 *   #nav-signup-btn   – "Sign Up" button
 *   #nav-user-area    – hidden div shown when logged in
 *   #nav-user-name    – span that shows "Hi, Foulen"
 *   #nav-logout-btn   – logout button inside #nav-user-area
 *
 * Also provides:
 *   window.joinClub(clubSlug)  – call from Join button onclick
 */

(function () {
    // ── Resolve backend URL robustly ────────────────────────────
    let scriptSrc = '';
    if (document.currentScript) {
        scriptSrc = document.currentScript.src;
    } else {
        // Fallback for async/deferred scripts or certain environments
        const scripts = document.getElementsByTagName('script');
        for (let s of scripts) {
            if (s.src && s.src.includes('assets/js/auth.js')) {
                scriptSrc = s.src;
                break;
            }
        }
    }

    const lastIdx = scriptSrc.lastIndexOf('/assets/js/');
    const projectRoot = lastIdx !== -1 ? scriptSrc.substring(0, lastIdx) : window.location.origin;

    function backendUrl(file) {
        const url = projectRoot + '/backend/' + (file || '');
        // console.log('[Auth] Backend URL:', url); // Debug
        return url;
    }

    // Root URL helper for internal navigation
    function projectUrl(path) {
        const url = projectRoot + '/' + path.replace(/^\//, '');
        // console.log('[Auth] Project URL:', url); // Debug
        return url;
    }

    // ── Update the navbar based on session state ────────────────
    function updateNavbar(user) {
        const signinBtn = document.getElementById('nav-signin-btn');
        const signupBtn = document.getElementById('nav-signup-btn');
        const userArea = document.getElementById('nav-user-area');
        const userNameEl = document.getElementById('nav-user-name');
        const logoutBtn = document.getElementById('nav-logout-btn');

        if (!userArea) return; // nav not present on this page

        if (user) {
            if (signinBtn) signinBtn.style.display = 'none';
            if (signupBtn) signupBtn.style.display = 'none';
            userArea.style.display = 'flex';
            if (userNameEl) userNameEl.textContent = 'Hi, ' + user.first_name;

            // Inject "My Clubs" link if not already present
            const navMenu = document.querySelector('.nav-menu');
            if (navMenu && !document.getElementById('nav-my-clubs')) {
                const myClubsLink = document.createElement('a');
                myClubsLink.id = 'nav-my-clubs';
                myClubsLink.href = projectUrl('pages/my-clubs.html');
                myClubsLink.className = 'nav-link';
                myClubsLink.textContent = 'My Clubs';
                navMenu.appendChild(myClubsLink);
            }
        } else {
            if (signinBtn) signinBtn.style.display = '';
            if (signupBtn) signupBtn.style.display = '';
            userArea.style.display = 'none';
        }

        if (logoutBtn) {
            logoutBtn.onclick = function () {
                fetch(backendUrl('logout.php'), { method: 'POST', credentials: 'include' })
                    .then(() => { window.location.href = projectUrl('index.html'); });
            };
        }
    }

    // ── Check session on every page load ────────────────────────
    window.currentUser = null;

    fetch(backendUrl('session_check.php'), { credentials: 'include' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.loggedIn) {
                window.currentUser = data.user;
                updateNavbar(data.user);
            } else {
                updateNavbar(null);
            }
        })
        .catch(function () { updateNavbar(null); });

    // ── joinClub(slug) – called from each club page's Join button ─
    window.joinClub = function (clubSlug) {
        fetch(backendUrl('join_club.php'), {
            method: 'POST',
            credentials: 'include',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ club_slug: clubSlug })
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.error === 'not_logged_in') {
                    // Redirect to sign-in with a redirect-back param
                    const redirect = encodeURIComponent(window.location.pathname);
                    window.location.href = projectUrl('pages/signin.html?redirect=' + redirect);
                } else if (data.success && data.already_member) {
                    showJoinFeedback('You are already a member of this club! 🎉', 'info');
                } else if (data.success) {
                    showJoinFeedback('You have successfully joined the club! 🎉', 'success');
                } else {
                    showJoinFeedback(data.error || 'Something went wrong.', 'error');
                }
            })
            .catch(function () {
                showJoinFeedback('Network error. Please try again.', 'error');
            });
    };

    // ── Small toast-style feedback ───────────────────────────────
    function showJoinFeedback(message, type) {
        let toast = document.getElementById('join-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'join-toast';
            toast.style.cssText = [
                'position:fixed', 'bottom:32px', 'left:50%',
                'transform:translateX(-50%)',
                'padding:14px 28px', 'border-radius:10px',
                'font-size:1rem', 'font-weight:600',
                'box-shadow:0 4px 20px rgba(0,0,0,0.25)',
                'z-index:99999', 'transition:opacity .4s'
            ].join(';');
            document.body.appendChild(toast);
        }
        const colors = {
            success: '#22c55e',
            info: '#3b82f6',
            error: '#ef4444'
        };
        toast.style.background = colors[type] || colors.info;
        toast.style.color = '#fff';
        toast.style.opacity = '1';
        toast.textContent = message;
        clearTimeout(toast._timer);
        toast._timer = setTimeout(function () { toast.style.opacity = '0'; }, 3500);
    }
})();
