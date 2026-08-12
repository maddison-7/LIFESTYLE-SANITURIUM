import Alpine from 'alpinejs';

document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        current: localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
        toggle() {
            this.current = this.current === 'dark' ? 'light' : 'dark';
            localStorage.setItem('theme', this.current);
            document.documentElement.classList.toggle('dark', this.current === 'dark');
        },
    });
});

window.Alpine = Alpine;

Alpine.start();
