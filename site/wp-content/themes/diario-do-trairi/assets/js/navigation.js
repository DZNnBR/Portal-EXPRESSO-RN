(() => {
  const button = document.querySelector('.dt-menu-toggle');
  const nav = document.querySelector('.dt-nav');
  const mode = document.querySelector('.rn-mode');
  const syncMode = () => mode.setAttribute('aria-pressed', String(document.documentElement.dataset.theme === 'dark'));
  syncMode();
  mode.addEventListener('click', () => {
    const theme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
    document.documentElement.dataset.theme = theme;
    try { localStorage.setItem('expresso-theme', theme); } catch (_) {}
    syncMode();
  });
  const dockMenu = document.querySelector('.rn-menu');
  dockMenu.addEventListener('click', () => button.click());
  if (!button || !nav) return;
  button.addEventListener('click', () => {
    const open = nav.classList.toggle('is-open');
    button.setAttribute('aria-expanded', String(open));
    dockMenu.setAttribute('aria-expanded', String(open));
  });
  document.addEventListener('keyup', (event) => {
    if (event.key === 'Escape' && nav.classList.contains('is-open')) {
      nav.classList.remove('is-open');
      button.setAttribute('aria-expanded', 'false');
      dockMenu.setAttribute('aria-expanded', 'false');
      dockMenu.focus();
    }
  });
})();
