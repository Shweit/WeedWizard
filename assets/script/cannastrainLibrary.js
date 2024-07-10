document.getElementById('show-sidebar').addEventListener('click', function () {
    document.getElementById('filters-hidden-container').classList.toggle('d-none');
    document.getElementById('filters-container').classList.toggle('d-none');
});

document.getElementById('close-sidebar').addEventListener('click', function () {
    document.getElementById('filters-container').classList.toggle('d-none');
    document.getElementById('filters-hidden-container').classList.toggle('d-none');
});
