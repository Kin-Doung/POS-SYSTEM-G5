// nav bar see more account, setting, log out-------------------------


document.addEventListener('DOMContentLoaded', () => {
  const profile = document.querySelector('.profile');
  const menu = document.querySelector('.menu');

  profile.addEventListener('click', () => {
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
  });

  // Close the menu when clicking outside
  document.addEventListener('click', (e) => {
    if (!profile.contains(e.target)) {
      menu.style.display = 'none';
    }
  });
});
