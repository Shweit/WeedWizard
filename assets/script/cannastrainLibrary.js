// ----------------- //
// --- VARIABLES --- //
// ----------------- //

// --- Pagination --- //
const itemsPerPage = 8;
let currentPage = 0;
let allItems = [];
let filteredItems = [];
let searchValue = '';
let paginationContainer;
let contentContainer;

// ----------------------- //
// --- EVENT LISTENERS --- //
// ----------------------- //

document.addEventListener('DOMContentLoaded', function () {

    // --- Pagination --- //
    // Select all items for current view and init pagination
    contentContainer = document.querySelector('#results');
    allItems = Array.from(contentContainer.getElementsByClassName('pagination-item'));
    initPagination(allItems);

    // --- Filter functionality --- //
    // Filter breeder cards on btn click
    const filterButton = document.getElementById('filter-button');
    if (filterButton) {
        filterButton.addEventListener('click', () => {
            searchValue = document.getElementById('breeder-name-search').value.toLowerCase();
            filterItems(searchValue);
        });
    }

    // Prevent filter form submission
    document.getElementById('filter-form').addEventListener('submit', (e) => {
        e.preventDefault();
    });

    // Clear search input on btn click
    const clearButton = document.getElementById('clear-button');
    if (clearButton) {
        clearButton.addEventListener('click', () => {
            document.getElementById('breeder-name-search').value = '';
        });
    }

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

    // Hide all items
    allItems.forEach(item => {
        item.style.display = 'none';
    });

    // Show items for current page
    items.forEach((item, index) => {
        if (index >= startIndex && index < endIndex) {
            item.style.display = 'block';
        }
    });
    updateActiveButtonStates();
}

function createPageButtons(relevantItems, contentContainer) {
    const totalPages = Math.ceil(relevantItems.length / itemsPerPage);

    // Create pagination container and add child elements
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
            showPage(relevantItems, currentPage);
            updateActiveButtonStates();
        });

        pageItem.appendChild(pageButton);
        paginationList.appendChild(pageItem);
    }

    const rowElement = contentContainer.querySelector('.row');
    rowElement.insertAdjacentElement('afterend', paginationContainer);
}

function initPagination(paginationItems) {
    createPageButtons(paginationItems, contentContainer);
    showPage(paginationItems, currentPage);
}

function removePagination() {
    paginationContainer = document.querySelector('.pagination-container');
    if (paginationContainer) {
        paginationContainer.remove();
    }
}

function filterItems(searchValue) {
    filteredItems = allItems.filter(item => item.getAttribute('data-name').toLowerCase().includes(searchValue));

    currentPage = 0;
    removePagination();

    if (filteredItems.length === 0) {
        document.getElementById('no-results').style.display = 'block';
    } else {
        document.getElementById('no-results').style.display = 'none';
        initPagination(filteredItems);
        showPage(filteredItems, currentPage);
    }
}