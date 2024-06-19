window.addEventListener('DOMContentLoaded', function () {
    const bannerFileInput = document.getElementById('user_profile_form_banner');

    bannerFileInput.addEventListener('change', function() {
        const file = bannerFileInput.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const bannerPreview = document.getElementById('bannerPreview');
            bannerPreview.style.backgroundImage = `url(${e.target.result})`;
        }

        reader.readAsDataURL(file);
    });

    const profileFileInput = document.getElementById('user_profile_form_profilePicture');

    profileFileInput.addEventListener('change', function() {
        const file = profileFileInput.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const profilePreview = document.getElementById('profilePicturePreview');
            profilePreview.style.backgroundImage = `url(${e.target.result})`;
        }

        reader.readAsDataURL(file);
    });
});