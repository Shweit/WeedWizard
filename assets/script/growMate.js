import * as select2_test from 'select2';

$(document).ready(function() {
    $('#plant_breeder').select2({
        theme: "bootstrap-5",
        dropdownParent: $('#addPlantModal'),
        placeholder: "Wähle einen Breeder aus",
        allowClear: true,
    });

    let plantSelect = $('#plant_strain').select2({
        theme: "bootstrap-5",
        dropdownParent: $('#addPlantModal'),
        placeholder: "Wähle eine Strain aus",
        allowClear: true,
    });

    $('#plant_breeder').on('select2:select', function (e) {
        updatePlantStrainOptions(e.params.data.id);
    });

    function updatePlantStrainOptions(breederId) {
        $.ajax('/api/getStrains/' + breederId, {
            success: function (data) {
                plantSelect.empty();
                $.each(data, function (index, value) {
                    plantSelect.append(new Option(value.name, value.seedfinder_id, false, false));
                });
                plantSelect.trigger('change');
            }
        })
    }

    updatePlantStrainOptions($('#plant_breeder').val());

    const weeklyTasks = document.querySelectorAll('.weekly-task');

    [...weeklyTasks].forEach(task => {
        task.addEventListener('click', () => {
            task.disabled = true;
            let label = document.querySelector('#' + task.dataset.task + '-' + task.dataset.plant + '-label');

            let days = (() => {
                switch (task.dataset.task) {
                    case 'water':
                        return 5;
                    case 'fertilize':
                        return 14;
                    case 'temperature':
                        return 2;
                    case 'pesticide':
                        return 7;
                }
            })();

            label.innerText += ' - in ' + days + ' Tagen';

            fetch('/api/completeTask', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    plant_id: task.dataset.plant,
                    task: task.dataset.task,
                })
            })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        window.showToast(data.error, 'error');
                    } else {
                        window.showToast(data, 'success');
                    }
                });
        });
    });
});
