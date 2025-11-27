document.addEventListener('DOMContentLoaded', function () {
  const checkbox = document.getElementById('check');
  const navList = document.querySelector('nav ul');

  if (!checkbox || !navList) {
    return;
  }

  navList.addEventListener('click', function (event) {
    const clickedTag = event.target.tagName;

    if ((clickedTag === 'LI' || clickedTag === 'A') && window.innerWidth < 800) {
      checkbox.checked = false;
      navList.style.left = '';
    }
  });
});
