document.addEventListener('DOMContentLoaded', function () {
    let passwordToggle = new Map;
    switch (window.location.pathname) {
        case '/login':
            let passwordToggleIcon = document.getElementById('togglePasswordIcon');
            let passwords = document.getElementById('password');

            passwordToggle.set(passwords, passwordToggleIcon);
            break;
        case '/register':
            let passwordToggleIconFirst = document.getElementById('togglePasswordIconFirst');
            let passwordToggleIconSecond = document.getElementById('togglePasswordIconSecond');
            let passwordsFirst = document.getElementById('registration_form_password_first');
            let passwordsSecond = document.getElementById('registration_form_password_second');

            passwordToggle.set(passwordsFirst, passwordToggleIconFirst);
            passwordToggle.set(passwordsSecond, passwordToggleIconSecond);
            break;
        default:
            break;
    }

    passwordToggle.forEach((icon, password) => {
        icon.addEventListener('click', function () {
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
});