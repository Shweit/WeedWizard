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
});
