<!-- footer halaman utama -->
  <script>
    // buka tutup dropdown navbar
    (function () {
      const buttons = document.querySelectorAll('[data-dropdown-toggle]');

      function closeAll(exceptId) {
        buttons.forEach((btn) => {
          const targetId = btn.getAttribute('data-dropdown-toggle');
          const menu = document.getElementById(targetId);
          if (!menu || targetId === exceptId) return;
          menu.classList.remove('open');
          btn.setAttribute('aria-expanded', 'false');
        });
      }

      buttons.forEach((btn) => {
        btn.addEventListener('click', (event) => {
          event.stopPropagation();
          const targetId = btn.getAttribute('data-dropdown-toggle');
          const menu = document.getElementById(targetId);
          if (!menu) return;
          const willOpen = !menu.classList.contains('open');
          closeAll(willOpen ? targetId : null);
          menu.classList.toggle('open', willOpen);
          btn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        });
      });

      document.addEventListener('click', () => closeAll(null));
      document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
          closeAll(null);
        }
      });
    })();
  </script>
  <script type="module"
    src="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
