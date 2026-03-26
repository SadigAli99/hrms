const html = document.documentElement;

document.addEventListener('DOMContentLoaded', () => {
  if (localStorage.getItem('theme') === 'light') {
    html.classList.add('light');
  }
});

function toggleTheme() {
  const isLight = html.classList.toggle('light');
  localStorage.setItem('theme', isLight ? 'light' : 'dark');
}
