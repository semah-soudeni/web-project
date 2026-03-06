/**
 * my-clubs.js – Handles fetching and displaying the user's joined clubs
 */

document.addEventListener('DOMContentLoaded', function () {
    const clubsList = document.getElementById('clubs-list');

    // Wait for auth.js to initialize (since it's an IIFE)
    // We can check session status after a small delay or use the currentUser global
    setTimeout(() => {
        loadMyClubs();
    }, 500);

    function loadMyClubs() {
        // Use the backendUrl helper from auth.js if we could access it, 
        // but it's hidden in an IIFE. We'll reconstruct it or fetch directly.
        // Re-calculate project root from script tags or current location
        const scripts = document.getElementsByTagName('script');
        let scriptSrc = '';
        for (let s of scripts) {
            if (s.src && s.src.includes('auth.js')) {
                scriptSrc = s.src;
                break;
            }
        }
        const lastIdx = scriptSrc.lastIndexOf('/assets/js/');
        const projectRoot = lastIdx !== -1 ? scriptSrc.substring(0, lastIdx) : window.location.origin;
        const myClubsUrl = projectRoot + '/backend/my_clubs.php';

        fetch(myClubsUrl, { credentials: 'include' })
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    if (data.error === 'not_logged_in') {
                        clubsList.innerHTML = '<div class="empty-state">Please <a href="signin.html">Sign In</a> to view your clubs.</div>';
                    } else {
                        clubsList.innerHTML = '<div class="empty-state">Error: ' + data.error + '</div>';
                    }
                    return;
                }

                renderClubs(data.clubs);
            })
            .catch(err => {
                clubsList.innerHTML = '<div class="empty-state">Network error. Please try again later.</div>';
            });
    }

    function renderClubs(clubs) {
        if (!clubs || clubs.length === 0) {
            clubsList.innerHTML = '<div class="empty-state">You haven\'t joined any clubs yet. <a href="../index.html">Discover clubs</a></div>';
            return;
        }

        clubsList.innerHTML = '';
        clubs.forEach(club => {
            const joinedDate = new Date(club.joined_at).toLocaleDateString('en-GB', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            const card = document.createElement('div');
            card.className = 'club-card';
            card.innerHTML = `
                <div class="club-info">
                    <h3>${club.name}</h3>
                    <div class="club-meta">
                        <div class="club-date">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            Joined on ${joinedDate}
                        </div>
                    </div>
                </div>
                <div class="club-actions">
                    <a href="clubs/${club.slug}.html" class="view-btn">View Club Page →</a>
                </div>
            `;
            clubsList.appendChild(card);
        });
    }
});
