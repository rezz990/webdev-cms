const menu = document.querySelector('.menu');
const links = document.querySelector('#navlinks');
menu?.addEventListener('click', () => { const open = links.classList.toggle('open'); menu.setAttribute('aria-expanded', String(open)); });
