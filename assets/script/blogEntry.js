document.addEventListener('DOMContentLoaded', function() {
    const addBlogCommentForm = document.getElementById('addBlogCommentForm');

    addBlogCommentForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const content = document.getElementById('addBlogComment_content').value;
        const parent = document.getElementById('addBlogComment_parent').value;

        fetch('/blog/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                content: content,
                parent: parent
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

    const addBlogComment_content = document.getElementById('addBlogComment_content');
    addBlogComment_content.addEventListener('keyup', function() {
        const content = addBlogComment_content.value;

        const character_limit = document.getElementById('character_limit_comment');
        character_limit.innerText = content.length + '/1000';

        let submit = document.getElementById('addBlogComment_submit')

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
    sortByOptions.addEventListener('change', function() {
        const sortValue = sortByOptions.value;
        const blogEntries = document.getElementsByClassName('commentEntry');

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
});
