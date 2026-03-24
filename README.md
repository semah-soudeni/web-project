# web-project
## to-do list:

## ⚠️ Important:
- [ ] change the index page design

### index page:
- [ ] change the background images
- [x] fix the clubs' hover effect
- [x] add IEEE, ACM
- [x] add the "selected link" effect in the nav bar
- [x] fixed the flickering hover effect

### sign-in & sign-up pages:
- [x] fix the back button behavior

### events page:
- [x] add the "selected link" effect in the nav bar
- [x] add pointer effect to the filters
- [x] render events using js not hardcoded html
- [ ] make the js renderer account for events organized by +2 clubs (currently it doesn't)

### map:
- [x] get or design a good map of the campus
- [ ] build the page
  - [x] Set up the layout for the page.
  - [x] Implement the functionality for the markers.
  - [ ] Fill in the side bars
  - [ ] Add the ability to close the sidebar when you press again on the club's marker or when pressing outside the sidebar

### admin page:
- [x] add a nav option for the admin
- [x] design the pageq
- [ ] add a pop-up that appears when you hover on the icons in the navbar to indicate their purpose ("membership request", "broadcast a message")
- [ ] improve the design

### Securinets page:
- [x] fix the ugly design
- [ ] add footer
- [ ] add glitch effect to text
- [ ] make nav bar compatible with theme

### hedha li zedou amine 7asb copilot:
**Summary — what was added / new**

- Backend added: a lightweight PHP session/API layer plus seeded DB schema. Key files:
  - db.php: PDO singleton — DB connection config (host, name, user, pass) and getDB().
  - cors.php: CORS handling (reflects Origin and allows credentials) and preflight handling.
  - insat_clubs.sql: Full DB schema and seed data — `clubs`, `users`, `memberships` tables and seeded club rows + seeded users and membership officer assignments.
  - register.php: POST endpoint to create a user, validate, hash password, insert into `users`, auto-login (starts session) and returns JSON.
  - login.php: POST endpoint to authenticate, start session, attach club officer info to session where applicable, returns JSON.
  - logout.php: Destroys session, returns JSON success.
  - session_check.php: Returns current session/login state as JSON (used by frontend to show auth UI).
  - join_club.php: Authenticated POST to add logged-in user to a club (by `club_slug`), prevents global admins from joining.
  - get_members.php: Returns club membership roster for authorized roles (president, vp, secretary, treasurer, admin); uses session to determine club.
  - update_membership.php: President-only endpoint to change a member's role with business rules (1-year tenure for promotions, one exec role per club, exclusivity across clubs).

**How the backend is intended to be used (front ↔ back flow)**

- Frontend will call:
  - register.php to sign up (POST JSON/form).
  - login.php to sign in (POST).
  - session_check.php on page load to toggle Sign In / Sign Out UI.
  - join_club.php when a logged-in user clicks "Join" on a club page.
  - get_members.php and update_membership.php for club officers admin pages (roster management).
- Sessions are PHP server sessions (cookie-based). cors.php allows cross-origin requests and credentials by reflecting the Origin header.

**Notable implementation details & safety notes**

- Passwords: registration uses `password_hash` (bcrypt) — good.
- DB access: prepared statements are used (PDO) — protects against SQL injection where used.
- DB credentials are in db.php currently (including a plaintext value) — move to env/config outside webroot for production.
- cors.php reflects origin; acceptable for dev but you should whitelist allowed origins in production (reflecting any origin with credentials is unsafe).
- insat_clubs.sql seeds a lot of demo users and membership data. Review seeds before deploying.

**Frontend gaps / mismatches to address**

- Broken link: index.html points to acm.html but repo has `pages/clubs/acmtest.html` → ACM link is broken. (Fix by renaming or updating link.)
- Events page:
  - events.js contains hardcoded `eventsData[]`. The page imports it, but:
    - `filterEvents` references `event.target` but is called without an event — bug to fix.
    - There is a `registerForEvent(id)` call used by "Register" buttons, but that function is not implemented.
  - Consider replacing hardcoded events with a backend events API if you want persistence.
- Auth pages:
  - signup.html and signin.html contain forms but there is no client-side JS wired to POST to register.php / login.php and to handle the session responses.
  - auth.js is present (index includes it) — check if it calls session_check.php. If not, implement it to toggle UI and handle login/logout flows.
- Map page: `map.js` provides `openBar(loc)` UI but sidebar content is placeholder. Backend could provide club details via an endpoint if desired.

**What each backend file does (one-line)**
- db.php: DB connection helper (PDO).
- cors.php: CORS and preflight handling + credentials config.
- insat_clubs.sql: DB schema + seed data for clubs, users, memberships.
- register.php: Create user, hash password, auto-login (session).
- login.php: Authenticate user, set session, attach club officer info.
- logout.php: Destroy session, return success.
- session_check.php: Return session state & basic user info JSON.
- join_club.php: Add logged-in user to a club by slug.
- get_members.php: Return members of current admin's club (ROLES required).
- update_membership.php: President-only role changes with business rules.

**Recommended next steps (short, actionable)**

- Fix quick frontend bugs and wire auth:
  - Implement client POSTs from signup.html → register.php and signin.html → login.php, then call session_check.php on page load to update UI.
  - Add `registerForEvent(id)` or implement an `events` backend (create endpoints and DB table) if events should be persisted.
  - Fix `events.js` filter bug: pass the event or change filter to use the clicked element's data attribute.
  - Fix ACM link mismatch (update index.html or rename `acmtest.html`).
- Security & deployment:
  - Move DB credentials out of db.php into a config file outside webroot or use environment vars.
  - Restrict cors.php to allowed origins in production.
  - Add HTTPS and secure session cookie flags (HttpOnly, Secure, SameSite).
  - Consider CSRF protection for POST endpoints (or require Authorization header + token).
- Admin features:
  - Hook admin page form to POST to a new `events` CRUD endpoint or extend existing backend to accept event creation.
  - Create a frontend roster UI that calls get_members.php and update_membership.php (only accessible to presidents).
- Optional UX improvements:
  - Replace hardcoded `eventsData` with an API-backed retrieval.
  - Populate Map sidebar with club info from DB (new endpoint like `club_info.php?slug=...`).

If you'd like, I can:
- Implement the front-end wiring for signup/login + `session_check` usage and show the exact JS changes (I can patch auth.js and the signup/signin pages), or
- Fix `events.js` filtering bug and add a stub `registerForEvent` that POSTs to a new `register_event.php` (or simply shows a modal).