// ----------------- //
// --- VARIABLES --- //
// ----------------- //

// --- Pagination --- //
const itemsPerPage = 4;
let currentPage = 0;
let allItems = [];

// ----------------------- //
// --- EVENT LISTENERS --- //
// ----------------------- //

document.addEventListener('DOMContentLoaded', function () {

    // --- Pagination --- //
    const contentContainer = document.querySelector('#results');
    allItems = Array.from(contentContainer.getElementsByClassName('pagination-item'));
    initPagination(allItems);

    // --- Filter functionality --- //
    // Filter breeder cards on btn click

    document.getElementById('filter-button').addEventListener('click', () => {
        const searchValue = document.getElementById('breeder-name-search').value.toLowerCase();
        document.querySelectorAll('.breeder-card-container').forEach(cardContainer => {
            cardContainer.style.display = cardContainer.getAttribute('data-name').toLowerCase().includes(searchValue) ? 'block' : 'none';
        });
    });

    document.getElementById('filter-button').addEventListener('click', () => {
        const searchValue = document.getElementById('breeder-name-search').value.toLowerCase();
        filterItems(searchValue);
    });

    // Prevent filter form submission
    document.getElementById('filter-form').addEventListener('submit', (e) => {
        e.preventDefault();
    });

    // Clear search input on btn click
    document.getElementById('clear-button').addEventListener('click', () => {
        document.getElementById('breeder-name-search').value = '';
    });

    // --- Sidebar open & close --- //
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
});

// ----------------- //
// --- FUNCTIONS --- //
// ----------------- //

// --- Pagination --- //
function updateActiveButtonStates() {
    const pageButtons = document.querySelectorAll('.pagination button');
    pageButtons.forEach((button, index) => {
        if (index === currentPage) {
            button.classList.add('active');
        } else {
            button.classList.remove('active');
        }
    });
}

function showPage(items, page) {
    const startIndex = page * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    items.forEach((item, index) => {
        item.classList.toggle('hidden', index < startIndex || index >= endIndex);
    });
    updateActiveButtonStates();
}

function createPageButtons(items, content) {
    const totalPages = Math.ceil(items.length / itemsPerPage);
    const paginationContainer = document.createElement('nav');
    paginationContainer.classList.add('pagination-container', 'mb-4');
    const paginationList = document.createElement('ul');
    paginationList.classList.add('pagination', 'd-flex', 'justify-content-end', 'm-3');
    paginationContainer.appendChild(paginationList);

    for (let i = 0; i < totalPages; i++) {
        const pageItem = document.createElement('li');
        pageItem.classList.add('page-item');

        const pageButton = document.createElement('a');
        pageButton.classList.add('page-link');
        pageButton.textContent = (i + 1).toString();
        pageButton.href = "#";
        pageButton.addEventListener('click', (event) => {
            event.preventDefault();
            currentPage = i;
            showPage(items, currentPage);
            updateActiveButtonStates();
        });

        pageItem.appendChild(pageButton);
        paginationList.appendChild(pageItem);
    }

    const rowElement = content.querySelector('.row');
    rowElement.insertAdjacentElement('afterend', paginationContainer);
}

function initPagination(items) {
    const content = document.querySelector('#results');
    createPageButtons(items, content);
    showPage(items, currentPage);
}

function resetPagination() {
    const paginationContainer = document.querySelector('.pagination');
    if (paginationContainer) {
        paginationContainer.remove();
    }
}

function filterItems(searchValue) {
    const filteredItems = allItems.filter(item => item.getAttribute('data-name').toLowerCase().includes(searchValue));
    currentPage = 0;
    resetPagination();

    if (filteredItems.length === 0) {
        document.getElementById('no-results').style.display = 'block';
    } else {
        document.getElementById('no-results').style.display = 'none';
        initPagination(filteredItems);
        showPage(filteredItems, currentPage);
    }
}