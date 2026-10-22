import {
    createIcons,
    ArrowLeft, ArrowRight, ArrowUpRight, Award, BadgeCheck, Briefcase, Building2, Calendar, Check, CircleAlert,
    ChevronRight, CircleCheck, CodeXml, Database, Download, ExternalLink, Eye, FileText, FolderGit2, Globe, GraduationCap, Image,
    Inbox, LayoutDashboard, LayoutTemplate, Layers, Link, LogOut, Mail, MapPin, Menu, Moon, Network, Pencil, Phone,
    Plus, Printer, Send, Server, Settings, Share2, Star, Sun, Tags, Trash2, X,
} from 'lucide';

const icons = {
    ArrowLeft, ArrowRight, ArrowUpRight, Award, BadgeCheck, Briefcase, Building2, Calendar, Check, CircleAlert,
    ChevronRight, CircleCheck, CodeXml, Database, Download, ExternalLink, Eye, FileText, FolderGit2, Globe, GraduationCap, Image,
    Inbox, LayoutDashboard, LayoutTemplate, Layers, Link, LogOut, Mail, MapPin, Menu, Moon, Network, Pencil, Phone,
    Plus, Printer, Send, Server, Settings, Share2, Star, Sun, Tags, Trash2, X,
};

createIcons({ icons, attrs: { 'aria-hidden': 'true' } });

/* Theme toggle: dark by default, remembers the visitor's choice. */
document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
    const sync = () => button.setAttribute('aria-pressed', String(document.documentElement.classList.contains('dark')));
    sync();
    button.addEventListener('click', () => {
        const dark = document.documentElement.classList.toggle('dark');
        try {
            localStorage.setItem('theme', dark ? 'dark' : 'light');
        } catch {
            /* storage unavailable: the choice simply isn't remembered */
        }
        document.querySelectorAll('[data-theme-toggle]').forEach((b) => b.setAttribute('aria-pressed', String(dark)));
    });
});

/* Mobile navigation */
const menuButton = document.querySelector('[data-menu-button]');
const menu = document.querySelector('[data-menu]');

if (menuButton && menu) {
    const setOpen = (open) => {
        menuButton.setAttribute('aria-expanded', String(open));
        // The admin sidebar stays visible on large screens, so it toggles a data attribute instead of [hidden].
        if (menu.dataset.menu === 'attribute') menu.toggleAttribute('data-open', open);
        else menu.hidden = !open;
        menuButton.querySelector('[data-menu-open]').classList.toggle('hidden', open);
        menuButton.querySelector('[data-menu-close]').classList.toggle('hidden', !open);
        if (menu.dataset.menu !== 'attribute') document.body.classList.toggle('overflow-hidden', open);
    };

    menuButton.addEventListener('click', () => setOpen(menuButton.getAttribute('aria-expanded') !== 'true'));
    menu.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && menuButton.getAttribute('aria-expanded') === 'true') {
            setOpen(false);
            menuButton.focus();
        }
    });
    window.matchMedia('(min-width: 1024px)').addEventListener('change', (e) => e.matches && setOpen(false));
}

/* Header shadow once the page scrolls */
const header = document.querySelector('[data-header]');
if (header) {
    const onScroll = () => header.toggleAttribute('data-scrolled', window.scrollY > 8);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
}

/* Active-section indicator for in-page navigation (home page only) */
const sectionLinks = [...document.querySelectorAll('[data-nav-section]')];
const sections = sectionLinks
    .map((link) => document.getElementById(link.dataset.navSection))
    .filter((section, index, all) => section && all.indexOf(section) === index);

if (sections.length) {
    const visible = new Map();
    const setActive = (id) => {
        sectionLinks.forEach((link) => {
            if (link.dataset.navSection === id) link.setAttribute('aria-current', 'true');
            else link.removeAttribute('aria-current');
        });
    };

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => visible.set(entry.target.id, entry.isIntersecting ? entry.intersectionRatio : 0));
            const current = sections.find((s) => visible.get(s.id) > 0);
            if (current) setActive(current.id);
        },
        { rootMargin: '-45% 0px -50% 0px', threshold: [0, 0.01] },
    );

    sections.forEach((section) => observer.observe(section));
}

/* Gentle reveal on scroll (disabled by CSS for reduced-motion users) */
const revealables = document.querySelectorAll('.reveal');
if ('IntersectionObserver' in window && revealables.length) {
    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        },
        { rootMargin: '0px 0px -8% 0px' },
    );
    revealables.forEach((el) => revealObserver.observe(el));
} else {
    revealables.forEach((el) => el.classList.add('is-visible'));
}

/* Project category filters: work as normal links without JS, filter instantly with it. */
const filterBar = document.querySelector('[data-project-filters]');
if (filterBar) {
    const cards = [...document.querySelectorAll('[data-project-category]')];
    const empty = document.querySelector('[data-projects-empty]');
    const count = document.querySelector('[data-projects-count]');

    const apply = (category) => {
        let shown = 0;
        cards.forEach((card) => {
            const match = category === 'all' || card.dataset.projectCategory === category;
            card.hidden = !match;
            if (match) shown += 1;
        });
        filterBar.querySelectorAll('[data-filter]').forEach((button) => {
            button.setAttribute('aria-pressed', String(button.dataset.filter === category));
        });
        if (empty) empty.hidden = shown > 0;
        if (count) count.textContent = `${shown} ${shown === 1 ? 'project' : 'projects'}`;
    };

    filterBar.addEventListener('click', (event) => {
        const button = event.target.closest('[data-filter]');
        if (!button) return;
        event.preventDefault();
        const category = button.dataset.filter;
        apply(category);
        const url = new URL(window.location.href);
        if (category === 'all') url.searchParams.delete('category');
        else url.searchParams.set('category', category);
        history.replaceState(null, '', url);
    });
}

/* Prevent double submission of forms */
document.querySelectorAll('form[data-submit-once]').forEach((form) => {
    form.addEventListener('submit', () => {
        const button = form.querySelector('[type="submit"]');
        if (button) {
            button.disabled = true;
            button.dataset.originalText = button.innerHTML;
            button.querySelector('[data-label]')?.replaceChildren('Sending…');
        }
    });
});

/* Confirmation for destructive admin actions */
document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) event.preventDefault();
    });
});

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* Hero 3D: loaded on demand, only where it's displayed and WebGL works; the SVG fallback stays otherwise. */
const heroVisual = document.querySelector('[data-hero-visual]');

function supportsWebGL() {
    try {
        const canvas = document.createElement('canvas');
        return !!(window.WebGLRenderingContext && (canvas.getContext('webgl2') || canvas.getContext('webgl')));
    } catch {
        return false;
    }
}

if (heroVisual && heroVisual.offsetParent !== null && !navigator.connection?.saveData && supportsWebGL()) {
    const mount = () =>
        import('./hero-scene.js')
            .then(({ mountHeroScene }) => mountHeroScene(heroVisual, { animate: !prefersReducedMotion }))
            .catch(() => {
                /* keep the static fallback */
            });
    // Let the page finish its critical work first.
    if ('requestIdleCallback' in window) requestIdleCallback(mount, { timeout: 1200 });
    else setTimeout(mount, 300);
}

/* Subtle 3D tilt on project previews for mouse/trackpad users. */
if (!prefersReducedMotion && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    document.querySelectorAll('[data-tilt]').forEach((card) => {
        const target = card.querySelector('[data-tilt-target]');
        if (!target) return;
        let frame = null;

        card.addEventListener('pointermove', (event) => {
            const rect = card.getBoundingClientRect();
            const x = (event.clientX - rect.left) / rect.width - 0.5;
            const y = (event.clientY - rect.top) / rect.height - 0.5;
            cancelAnimationFrame(frame);
            frame = requestAnimationFrame(() => {
                target.style.transform = `perspective(900px) translateY(-6px) rotateX(${(-y * 5).toFixed(2)}deg) rotateY(${(x * 6).toFixed(2)}deg)`;
            });
        });
        card.addEventListener('pointerleave', () => {
            cancelAnimationFrame(frame);
            target.style.transform = '';
        });
    });
}

/* Cursor-following light on bento project tiles (purely visual; skipped for touch). */
if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    document.querySelectorAll('[data-spotlight]').forEach((tile) => {
        tile.addEventListener('pointermove', (event) => {
            const rect = tile.getBoundingClientRect();
            tile.style.setProperty('--mx', `${event.clientX - rect.left}px`);
            tile.style.setProperty('--my', `${event.clientY - rect.top}px`);
        });
    });
}
