// Active Page of NavBar

let activePage = window.location.pathname;
let nav_elem = document.querySelectorAll("aside a");

nav_elem.forEach(link => {
    if (link.href.includes(activePage)) {
        link.classList.add("active");
        link.parentElement.classList.add("active");
    }
});