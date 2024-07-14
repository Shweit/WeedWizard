document.addEventListener('DOMContentLoaded', function() {
    const addBlogEntryForm = document.getElementById('addBlogEntryForm');

    addBlogEntryForm.addEventListener('submit', function(event) {
       event.preventDefault();

       const content = document.getElementById('addBlogEntry_content').value;

       fetch('/blog/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
           body: JSON.stringify({
                content: content
          })
       })
       .then(response =>  {
           return response.json()
       })
       .then(data => {
           if (data.error) {
               window.showToast(data.error, 'error');
           } else {
               window.location.reload();
           }
       })
    });

    const addBlogEntry_content = document.getElementById('addBlogEntry_content');
    addBlogEntry_content.addEventListener('keyup', function() {
        const content = addBlogEntry_content.value;

        const character_limit = document.getElementById('character_limit');
        character_limit.innerText = content.length + '/1000';

        let submit = document.getElementById('addBlogEntry_submit')

        if (content.length > 1000) {
            character_limit.style.color = 'red';
            submit.setAttribute('disabled', 'disabled');
        } else {
            character_limit.style.color = '';
            if (submit.hasAttribute('disabled')) {
                submit.removeAttribute('disabled');
            }
        }
    });

    const sortByOptions = document.getElementById('sortBy');
    if (sortByOptions) {
        sortByOptions.addEventListener('change', function() {
            const sortValue = sortByOptions.value;
            const blogEntries = document.getElementsByClassName('blogEntry');

            let sorted = null;

            switch (sortValue) {
                case 'id_ASC':
                    sorted = [...blogEntries].sort((a, b) => {
                        return new Date(b.dataset.createdAt) - new Date(a.dataset.createdAt);
                    });

                    sorted.forEach((entry) => {
                        entry.parentNode.appendChild(entry);
                    });
                    break;
                case 'id_DESC':
                    sorted = [...blogEntries].sort((a, b) => {
                        return new Date(a.dataset.createdAt) - new Date(b.dataset.createdAt);
                    });

                    sorted.forEach((entry) => {
                        entry.parentNode.appendChild(entry);
                    });
                    break;
                case 'likes_ASC':
                    sorted = [...blogEntries].sort((a, b) => {
                        return a.dataset.likeCount - b.dataset.likeCount;
                    });

                    sorted.forEach((entry) => {
                        entry.parentNode.appendChild(entry);
                    });
                    break;
                case 'likes_DESC':
                    sorted = [...blogEntries].sort((a, b) => {
                        return b.dataset.likeCount - a.dataset.likeCount;
                    });

                    sorted.forEach((entry) => {
                        entry.parentNode.appendChild(entry);
                    });
                    break;
                case 'comments_DESC':
                    sorted = [...blogEntries].sort((a, b) => {
                        return b.dataset.commentCount - a.dataset.commentCount;
                    });

                    sorted.forEach((entry) => {
                        entry.parentNode.appendChild(entry);
                    });
                    break;
                case 'comments_ASC':
                    sorted = [...blogEntries].sort((a, b) => {
                        return a.dataset.commentCount - b.dataset.commentCount;
                    });

                    sorted.forEach((entry) => {
                        entry.parentNode.appendChild(entry);
                    });
                    break;
            }
        });
    }

    // Make the fixed #fixed-blog-sidebar inherit the width of the sidebar, even on resize
    const sidebar = document.getElementById('blog-sidebar');
    const fixedBlogSidebar = document.getElementById('fixed-blog-sidebar');

    function setFixedBlogSidebarWidth() {
        if (fixedBlogSidebar && sidebar) {
            fixedBlogSidebar.style.width = sidebar.offsetWidth - 10 + 'px';
        }
    }

    window.addEventListener('resize', function() {
        setFixedBlogSidebarWidth();
    });
    setFixedBlogSidebarWidth();

    document.querySelectorAll('.blogEntry').forEach(function(card) {
        card.addEventListener('click', function(e) {
            // Überprüfe, ob der Klick innerhalb eines Links erfolgte
            if (!e.target.closest('a')) {
                // Leite zur URL um, die im data-href-Attribut des Karten-Divs gespeichert ist
                window.location.href = this.getAttribute('data-href');
            }
        });
    });
});
