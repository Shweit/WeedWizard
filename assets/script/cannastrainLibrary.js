document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('show-sidebar').addEventListener('click', function () {
        $('#filters-hidden-container').hide();
        $('#filters-container').show();
    });

    document.getElementById('close-sidebar').addEventListener('click', function () {
        $('#filters-hidden-container').show();
        $('#filters-container').hide();
    });
});