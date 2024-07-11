document.addEventListener('DOMContentLoaded', function () {

    // Show sidebar on icon click
    document.getElementById('show-sidebar').addEventListener('click', function () {
        $('#filters-hidden-container').hide();
        $('#filters-container').show();
    });

    // Close sidebar on icon click
    document.getElementById('close-sidebar').addEventListener('click', function () {
        $('#filters-hidden-container').show();
        $('#filters-container').hide();
    });

    // Filter breeder cards on btn click
    document.getElementById('filter-button').addEventListener('click', () => {
        const searchValue = document.getElementById('breeder-name-search').value.toLowerCase();
        document.querySelectorAll('.breeder-card-container').forEach(cardContainer => {
            cardContainer.style.display = cardContainer.getAttribute('data-name').toLowerCase().includes(searchValue) ? 'block' : 'none';
        });
    });

    // Prevent form submission
    document.getElementById('filter-form').addEventListener('submit', (e) => {
        e.preventDefault();
    });

    // Clear search input on btn click
    document.getElementById('clear-button').addEventListener('click', () => {
        document.getElementById('breeder-name-search').value = '';
    });

});