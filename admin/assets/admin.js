document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    const sidebar = document.querySelector('.sidebar');
    const menuButton = document.querySelector('[data-menu]');
    const overlay = document.querySelector('[data-overlay]');

    const closeMenu = () => {
        body.classList.remove('sidebar-open');
    };

    menuButton?.addEventListener('click', () => body.classList.toggle('sidebar-open'));
    overlay?.addEventListener('click', closeMenu);
    sidebar?.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));

    document.querySelectorAll('[data-confirm]').forEach((element) => {
        element.addEventListener('click', (event) => {
            if (!window.confirm(element.dataset.confirm)) event.preventDefault();
        });
    });

    document.querySelectorAll('[data-filter]').forEach((input) => {
        input.addEventListener('input', () => {
            const query = input.value.toLowerCase().trim();
            document.querySelectorAll(input.dataset.filter).forEach((row) => {
                row.hidden = query && !row.textContent.toLowerCase().includes(query);
            });
        });
    });
});