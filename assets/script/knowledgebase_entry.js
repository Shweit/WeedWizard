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

   // Make the navbar sticky if it reaches the top of the screen
   const curStickyPosition = tocNavbar.getBoundingClientRect().top + window.pageYOffset;
   let knowledgebase_navbar_card = document.getElementById('knowledgebase_navbar_card');
   const navbarHeight = document.getElementById('navbar').offsetHeight;

    window.onscroll = function() {
        if (window.pageYOffset > curStickyPosition - navbarHeight) {
           knowledgebase_navbar_card.style.position = 'fixed';
           knowledgebase_navbar_card.style.top = navbarHeight + 10 + 'px';
        } else {
           knowledgebase_navbar_card.style.position = 'relative';
              knowledgebase_navbar_card.style.top = '0px';
        }
    };
});