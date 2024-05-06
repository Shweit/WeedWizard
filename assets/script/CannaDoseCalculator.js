document.addEventListener('DOMContentLoaded', function() {
    const intensity = document.getElementById('canna_dose_calculator_intensity');
    intensity.addEventListener('input', function() {

        let value = intensity.value;

        switch (true) {
            case value >= 1 && value <= 3:
                value = 'Sehr Niedrig';
                break;
            case value >= 4 && value <= 8:
                value = 'Niedrig';
                break;
            case value >= 9 && value <= 12:
                value = 'Mittel';
                break;
            case value >= 13 && value <= 16:
                value = 'Hoch';
                break;
            case value >= 17 && value <= 20:
                value = 'Sehr hoch';
                break;
        }

        document.getElementById('intensity-value').textContent = value;
    });

    const dosage = document.querySelectorAll('.dosage-choice');
    dosage.forEach(function(element) {
        element.addEventListener('click', function() {
            document.getElementById('canna_dose_calculator_basis_dosage').value = element.dataset.basisDosage;

            dosage.forEach(function(element) {
                element.classList.remove('dosage-active');
            });

            element.classList.add('dosage-active');
        });
    });
});
