const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const publicMenu = document.querySelector('.menu');
const publicLinks = document.querySelector('#navlinks');
publicMenu?.addEventListener('click', () => {
    const isOpen = publicLinks?.classList.toggle('open') ?? false;
    publicMenu.setAttribute('aria-expanded', String(isOpen));
});

const adminMenu = document.querySelector('.admin-menu');
const adminSidebar = document.querySelector('#admin-sidebar');
adminMenu?.addEventListener('click', () => {
    const isOpen = adminSidebar?.classList.toggle('open') ?? false;
    adminMenu.setAttribute('aria-expanded', String(isOpen));
});

if (!reducedMotion) {
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('[data-reveal]').forEach((element) => revealObserver.observe(element));

    document.querySelectorAll('[data-tilt]').forEach((card) => {
        card.addEventListener('pointermove', (event) => {
            const bounds = card.getBoundingClientRect();
            const rotateX = ((event.clientY - bounds.top) / bounds.height - 0.5) * -8;
            const rotateY = ((event.clientX - bounds.left) / bounds.width - 0.5) * 8;
            card.style.transform = `perspective(900px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
        });
        card.addEventListener('pointerleave', () => { card.style.transform = ''; });
    });

    const glow = document.querySelector('.cursor-glow');
    window.addEventListener('pointermove', (event) => {
        if (glow) glow.style.transform = `translate(${event.clientX - 160}px, ${event.clientY - 160}px)`;
    }, { passive: true });
}

document.querySelector('[data-password-toggle]')?.addEventListener('click', (event) => {
    const input = document.querySelector(`#${event.currentTarget.getAttribute('aria-controls')}`);
    if (!(input instanceof HTMLInputElement)) return;
    const shouldShow = input.type === 'password';
    input.type = shouldShow ? 'text' : 'password';
    event.currentTarget.textContent = shouldShow ? 'Sembunyi' : 'Lihat';
});

const commandDialog = document.querySelector('[data-command-dialog]');
const commandInput = document.querySelector('[data-command-input]');
const openCommand = () => {
    if (!(commandDialog instanceof HTMLDialogElement)) return;
    commandDialog.showModal();
    commandInput?.focus();
};
const closeCommand = () => {
    if (commandDialog instanceof HTMLDialogElement) commandDialog.close();
};
document.querySelector('[data-command-open]')?.addEventListener('click', openCommand);
document.querySelector('[data-command-close]')?.addEventListener('click', closeCommand);
document.addEventListener('keydown', (event) => {
    if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
        event.preventDefault();
        openCommand();
    }
    if (event.key === 'Escape') closeCommand();
});
commandInput?.addEventListener('input', (event) => {
    const query = event.currentTarget.value.toLowerCase();
    document.querySelectorAll('.command-list a').forEach((link) => {
        link.hidden = !link.textContent.toLowerCase().includes(query);
    });
});
