let menuToggler = '';
if(menuToggler = document.querySelector('button[data-target="#navbarNav"]')) {
  menuToggler.onclick = () => {
    document.getElementById('navbarNav').classList.toggle('d-flex');
  };
}
