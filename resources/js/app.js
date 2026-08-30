const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const publicMenu = document.querySelector('.menu');
const publicLinks = document.querySelector('#navlinks');
publicMenu?.addEventListener('click', () => {
    const isOpen = publicLinks?.classList.toggle('open') ?? false;
    publicMenu.setAttribute('aria-expanded', String(isOpen));
});

const adminMenu = document.querySelector('.admin-menu');
const adminSidebar = document.querySelector('#admin-sidebar');
const sidebarBackdrop = document.querySelector('[data-sidebar-close]');
const setAdminSidebar = (isOpen) => {
    adminSidebar?.classList.toggle('open', isOpen);
    adminMenu?.setAttribute('aria-expanded', String(isOpen));
    if (sidebarBackdrop instanceof HTMLButtonElement) sidebarBackdrop.hidden = !isOpen;
};
adminMenu?.addEventListener('click', () => setAdminSidebar(!adminSidebar?.classList.contains('open')));
sidebarBackdrop?.addEventListener('click', () => setAdminSidebar(false));
adminSidebar?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setAdminSidebar(false)));

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

const slugify = (value) => value
    .normalize('NFKD')
    .replace(/[\u0300-\u036f]/g, '')
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');

document.querySelectorAll('[data-content-form]').forEach((form) => {
    const title = form.querySelector('[data-title-input]');
    const slug = form.querySelector('[data-slug-input]');
    let slugWasEdited = Boolean(slug?.value);

    slug?.addEventListener('input', () => { slugWasEdited = true; });
    title?.addEventListener('input', () => {
        if (slug && !slugWasEdited) slug.value = slugify(title.value);
    });
    form.querySelector('[data-regenerate-slug]')?.addEventListener('click', () => {
        if (slug && title) slug.value = slugify(title.value);
        slugWasEdited = false;
    });

    form.querySelectorAll('[data-submit-status]').forEach((button) => {
        button.addEventListener('click', () => {
            const status = form.querySelector(`input[name="status"][value="${button.dataset.submitStatus}"]`);
            if (status) status.checked = true;
        });
    });

    const editor = form.querySelector('[data-markdown-editor]');
    const preview = form.querySelector('[data-markdown-preview]');
    const wordCount = form.querySelector('[data-word-count]');
    const updateWordCount = () => {
        if (!editor || !wordCount) return;
        const words = editor.value.trim() ? editor.value.trim().split(/\s+/).length : 0;
        wordCount.textContent = `${words} kata · ${Math.max(1, Math.ceil(words / 200))} menit baca`;
    };
    editor?.addEventListener('input', updateWordCount);
    updateWordCount();

    const escapeHtml = (value) => value.replace(/[&<>"']/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[character]));
    const renderMarkdown = (value) => {
        let html = escapeHtml(value);
        html = html
            .replace(/^### (.+)$/gm, '<h3>$1</h3>')
            .replace(/^## (.+)$/gm, '<h2>$1</h2>')
            .replace(/^# (.+)$/gm, '<h1>$1</h1>')
            .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.+?)\*/g, '<em>$1</em>')
            .replace(/`([^`]+)`/g, '<code>$1</code>')
            .replace(/^&gt; (.+)$/gm, '<blockquote>$1</blockquote>')
            .replace(/^- (.+)$/gm, '<li>$1</li>')
            .replace(/\[([^\]]+)\]\((https?:\/\/[^\s)]+)\)/g, '<a href="$2" rel="noopener noreferrer">$1</a>')
            .replace(/\n{2,}/g, '</p><p>')
            .replace(/\n/g, '<br>');
        return `<p>${html}</p>`.replace(/<p>\s*<(h[1-3]|li|blockquote)/g, '<$1').replace(/<\/(h[1-3]|li|blockquote)>\s*<\/p>/g, '</$1>');
    };

    form.querySelectorAll('[data-editor-tab]').forEach((tab) => {
        tab.addEventListener('click', () => {
            const showPreview = tab.dataset.editorTab === 'preview';
            form.querySelectorAll('[data-editor-tab]').forEach((item) => {
                const isActive = item === tab;
                item.classList.toggle('active', isActive);
                item.setAttribute('aria-selected', String(isActive));
            });
            if (editor) editor.hidden = showPreview;
            if (preview) {
                preview.hidden = !showPreview;
                if (showPreview) preview.innerHTML = renderMarkdown(editor?.value ?? '');
            }
        });
    });

    const markdownActions = {
        heading: ['## ', ''], bold: ['**', '**'], italic: ['*', '*'], link: ['[', '](https://)'],
        quote: ['> ', ''], list: ['- ', ''], code: ['`', '`'], image: ['![Alt text](', ')'],
    };
    form.querySelectorAll('[data-markdown]').forEach((button) => {
        button.addEventListener('click', () => {
            if (!editor) return;
            const [before, after] = markdownActions[button.dataset.markdown] ?? ['', ''];
            const start = editor.selectionStart;
            const end = editor.selectionEnd;
            const selected = editor.value.slice(start, end) || 'teks';
            editor.setRangeText(`${before}${selected}${after}`, start, end, 'end');
            editor.focus();
            editor.dispatchEvent(new Event('input'));
        });
    });

    const characterInput = form.querySelector('[data-character-input]');
    const characterCount = form.querySelector('[data-character-count]');
    const updateCharacters = () => { if (characterInput && characterCount) characterCount.textContent = characterInput.value.length; };
    characterInput?.addEventListener('input', updateCharacters);
    updateCharacters();

    const coverInput = form.querySelector('[data-cover-input]');
    const coverPreview = form.querySelector('[data-cover-preview]');
    coverInput?.addEventListener('change', () => {
        const file = coverInput.files?.[0];
        if (!file || !(coverPreview instanceof HTMLImageElement)) return;
        coverPreview.src = URL.createObjectURL(file);
        coverPreview.hidden = false;
        form.querySelector('[data-cover-placeholder]')?.setAttribute('hidden', '');
    });
});
