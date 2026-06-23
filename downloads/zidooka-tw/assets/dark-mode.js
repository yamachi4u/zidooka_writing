(function(){
  var KEY = 'zdk_theme';
  var FLAG = 'zdk_dark_mode';
  var html = document.documentElement;

  function apply(theme) {
    html.setAttribute('data-theme', theme);
    try { localStorage.setItem(KEY, theme); } catch(e) {}
  }

  function toggle() {
    var current = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    apply(current);
    try {
      if (typeof posthog !== 'undefined' && posthog.capture) {
        posthog.capture('zdk_dark_mode_toggle', { theme: current, path: location.pathname });
      }
    } catch(e) {}
    return current;
  }

  // Determine initial theme: localStorage > OS preference > PostHog flag > light
  var stored = null;
  try { stored = localStorage.getItem(KEY); } catch(e) {}

  var theme = 'light';
  if (stored) {
    theme = stored;
  } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    theme = 'dark';
  }

  // PostHog feature flag check (deferred, non-blocking)
  function checkFlag() {
    try {
      if (typeof posthog === 'undefined' || !posthog.isFeatureEnabled) return;
      if (stored) return; // user already chose
      var enabled = posthog.isFeatureEnabled(FLAG, false);
      if (enabled && theme === 'dark') {
        apply('dark');
      }
    } catch(e) {}
  }

  // Apply initial theme (OS or stored)
  if (!stored && theme === 'dark') {
    apply('dark');
  } else if (stored) {
    apply(stored);
  }

  // Listen for OS theme changes
  if (window.matchMedia) {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
      if (stored) return; // user preference takes priority
      apply(e.matches ? 'dark' : 'light');
    });
  }

  // Create toggle button (appended to header)
  function addToggle() {
    var h = document.querySelector('.zdk-header-inner');
    if (!h) return;
    var btn = document.createElement('button');
    btn.className = 'zdk-theme-toggle';
    btn.setAttribute('aria-label', 'Toggle dark mode');
    btn.title = 'Toggle dark mode';
    btn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>';
    btn.style.cssText = 'background:none;border:none;cursor:pointer;color:inherit;padding:4px;margin-left:auto;display:flex;align-items:center;opacity:0.7;';
    btn.addEventListener('click', function(e) { e.preventDefault(); toggle(); });
    btn.addEventListener('mouseenter', function(){ this.style.opacity='1'; });
    btn.addEventListener('mouseleave', function(){ this.style.opacity='0.7'; });
    h.appendChild(btn);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function(){ addToggle(); checkFlag(); });
  } else {
    addToggle();
    checkFlag();
  }

  // Expose toggle globally
  window.zdkToggleTheme = toggle;
})();
