window.addEventListener('DOMContentLoaded', (event) => {
    // Add the table of contents to the navbar
    var toc = document.getElementById('articleContent').querySelectorAll('section');
    var tocNavbar = document.getElementById('knowledgebase_navbar');

    toc.forEach(function (element) {
        var anchor = document.createElement('a');

        // Add the anchor to the navbar
        anchor.href = '#' + element.id;
        anchor.classList.add('nav-link');
        anchor.textContent = element.dataset.navTitle;

        tocNavbar.appendChild(anchor);
    });

    // Make the Sidebar with the Content of the Article sticky
    var sidebar = document.getElementById('knowledgebase_navbar_card');
    let navbar = document.getElementById('navbar');
    let activateFixedOn = 47 + navbar.offsetHeight;
    let width = sidebar.offsetWidth;
    window.onscroll = function () {
        if (window.pageYOffset > activateFixedOn) {
            sidebar.style.top = navbar.offsetHeight + 10 + 'px';
            sidebar.style.width = width + 'px';
            sidebar.classList.add('position-fixed');
        } else {
            sidebar.style.top = '0px';
            sidebar.classList.remove('position-fixed');
        }
    }
});