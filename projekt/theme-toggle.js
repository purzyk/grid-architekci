/* GRID — theme toggle.
   Applies the saved (or system) theme, then flips it on any click of a
   [data-theme-toggle] control. Delegated, so it survives re-renders.
   Guarded: the host may evaluate this file more than once per page. */
(function () {
  if (window.__gridTheme) { window.__gridTheme.apply(); return; }

  var KEY = 'grid-theme';

  function saved() {
    try { return localStorage.getItem(KEY); } catch (e) { return null; }
  }
  function store(v) {
    try { localStorage.setItem(KEY, v); } catch (e) {}
  }
  function prefersDark() {
    return !!(window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
  }
  function apply() {
    var pref = saved();
    document.documentElement.classList.toggle('dark', pref ? pref === 'dark' : prefersDark());
  }

  window.__gridTheme = { apply: apply };
  apply();

  // composedPath(), so the control is still found if the page renders in a shadow root
  document.addEventListener('click', function (e) {
    var path = e.composedPath ? e.composedPath() : [e.target];
    for (var i = 0; i < path.length; i++) {
      var n = path[i];
      if (n && n.nodeType === 1 && n.hasAttribute && n.hasAttribute('data-theme-toggle')) {
        var on = document.documentElement.classList.toggle('dark');
        store(on ? 'dark' : 'light');
        return;
      }
    }
  }, true);
})();
