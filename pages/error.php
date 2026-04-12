<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Error</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet" />
   <link  rel="stylesheet" href="../assets/css/error.css" />
</head>
<body>

<div class="noise"></div>
<div class="grid-lines"></div>

<main>
  <div class="card" role="alert" aria-live="assertive">
    <div class="card-header">
      <div class="icon-wrap" aria-hidden="true">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      </div>
      <div>
        <div class="code-block" id="error-code">Error</div>
        <h1 class="error-title" id="error-title">Something went wrong</h1>
      </div>
    </div>
    <div class="card-body">
      <p class="description" id="error-description">
        An unexpected error has occurred. Please try again or contact support if the problem persists.
      </p>
      <hr class="divider" />
      <div class="meta-row">
        <div class="meta-item">
          <span class="meta-label">Request ID</span>
          <span class="meta-value" id="request-id">—</span>
        </div>
        <div class="meta-item" style="text-align:right;">
          <span class="meta-label">Timestamp</span>
          <span class="meta-value" id="timestamp">—</span>
        </div>
      </div>
      <div class="actions">
        <button class="btn btn-primary" onclick="history.back()">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
          Go back
        </button>
        <button class="btn btn-ghost" onclick="location.href='/'">
          Home page
        </button>
      </div>
    </div>
  </div>
</main>

<footer id="footer-text">If this keeps happening, please reach out to support.</footer>

<script>
  const ERROR_MAP = {
    400: { title: "Bad Request",                desc: "The server could not understand your request. Please check the syntax and try again." },
    401: { title: "Unauthorized",               desc: "You need to be authenticated to access this resource. Please check your credentials and try again." },
    403: { title: "Forbidden",                  desc: "You don't have permission to view this page. Contact an administrator if you think this is a mistake." },
    404: { title: "Page Not Found",             desc: "The page you're looking for doesn't exist or may have been moved. Double-check the URL and try again." },
    405: { title: "Method Not Allowed",         desc: "The HTTP method used is not supported for this endpoint. Please check the API documentation." },
    408: { title: "Request Timeout",            desc: "The server waited too long for a request that didn't arrive in time. Please try again." },
    409: { title: "Conflict",                   desc: "The request conflicts with the current state of the resource. Resolve the conflict and try again." },
    410: { title: "Gone",                       desc: "The resource you requested has been permanently removed and is no longer available." },
    422: { title: "Unprocessable Entity",       desc: "The request was well-formed but contained semantic errors. Check your input data and try again." },
    429: { title: "Too Many Requests",          desc: "You've sent too many requests in a short period. Please slow down and try again after a moment." },
    500: { title: "Internal Server Error",      desc: "Something went wrong on our end. Our team has been notified and is working on a fix." },
    501: { title: "Not Implemented",            desc: "The server does not support the functionality required to fulfill the request." },
    502: { title: "Bad Gateway",                desc: "The server received an invalid response from an upstream server. Please try again shortly." },
    503: { title: "Service Unavailable",        desc: "The service is temporarily unavailable, likely due to maintenance or overload. Please try again later." },
    504: { title: "Gateway Timeout",            desc: "The upstream server failed to respond in time. This is usually a temporary issue — please retry." },
  };

  function pad(n) { return String(n).padStart(2, '0'); }

  function generateRequestId() {
    return 'req_' + Math.random().toString(36).slice(2, 10).toUpperCase();

  function formatTimestamp(d) {
  }
    return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())} `
         + `${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
  }

  function getColor(code) {
      if (code >= 500) 
          return { accent: '#9a6000', light: '#fff9ee', mid: '#f5dca8' };
      if (code >= 400) 
          return { accent: '#b5290f', light: '#fff0ee', mid: '#f7c5bb' };
    return                  { accent: '#1a6b3c', light: '#edfbf3', mid: '#a8e4c4' };
  }

  function applyTheme(code) {
    const c = getColor(code);
    const root = document.documentElement;
    root.style.setProperty('--accent', c.accent);
    root.style.setProperty('--accent-light', c.light);
    root.style.setProperty('--accent-mid', c.mid);
  }

  const params   = new URLSearchParams(location.search);
  const rawCode  = params.get('code');
  const reqId    =  generateRequestId();

  const code     = parseInt(rawCode, 10) || 0;
  const known    = ERROR_MAP[code] || {};

  const title    = known.title || (code ? `HTTP ${code}` : 'Something went wrong');
  const desc     = known.desc  || 'An unexpected error has occurred. Please try again or contact support if the problem persists.';
  const codeStr  = code        ? `HTTP ${code}` : 'Error';

  document.getElementById('error-code').textContent        = codeStr;
  document.getElementById('error-title').textContent       = title;
  document.getElementById('error-description').textContent = desc;
  document.getElementById('request-id').textContent        = reqId;
  document.getElementById('timestamp').textContent         = formatTimestamp(new Date());
  document.title = code ? `${code} — ${title}` : title;

  if (code) applyTheme(code);

</script>
</body>
</html>
