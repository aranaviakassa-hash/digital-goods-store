const drawer = document.querySelector('[data-mobile-drawer]');
const openButton = document.querySelector('[data-mobile-open]');
const closeButtons = document.querySelectorAll('[data-mobile-close]');

const setDrawer = (open) => {
    if (!drawer || !openButton) return;

    drawer.dataset.open = open ? 'true' : 'false';
    openButton.setAttribute('aria-expanded', open ? 'true' : 'false');
    document.body.classList.toggle('pc-drawer-open', open);

    if (open) {
        const firstFocusable = drawer.querySelector('a, button');
        window.setTimeout(() => firstFocusable?.focus(), 40);
    } else {
        openButton.focus();
    }
};

openButton?.addEventListener('click', () => setDrawer(true));
closeButtons.forEach((button) => button.addEventListener('click', () => setDrawer(false)));

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && drawer?.dataset.open === 'true') {
        setDrawer(false);
    }
});

window.addEventListener('resize', () => {
    if (window.innerWidth >= 768 && drawer?.dataset.open === 'true') {
        setDrawer(false);
    }
});
