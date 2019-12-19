var sidebartoggle = document.getElementById("sidebar-toggle");
var sidebar = document.getElementById("sidebar");
var visible = 1;

sidebartoggle.addEventListener("click", myFunction);

function myFunction() {
  if (sidebar.style.display == 'flex') {
    sidebar.style.display = 'none';
  } else if (sidebar.style.display = 'none') {
    sidebar.style.display = 'flex';
  }
}
