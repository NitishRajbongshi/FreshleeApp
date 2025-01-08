$(function () {
  $('#tblCropSymptoms').DataTable({
    paging: true,
    ordering: true,
    info: true,
  })
    .buttons()
    .container()
    .appendTo(".spn_crop_symptom");
});

// Diseases data from PHP to JavaScript
// Populate disease dropdown based on selected crop type
$('#crop_type_cd').on('change', function () {
  var cropTypeCd = $(this).val();
  var diseaseDropdown = $('#disease_cd');
  diseaseDropdown.empty();
  diseaseDropdown.append('<option value="">Select Disease</option>');

  if (cropTypeCd) {
    var filteredDiseases = diseases[cropTypeCd] || [];

    // Sort the diseases alphabetically by disease_name
    filteredDiseases.sort(function (a, b) {
      return a.disease_name.localeCompare(b
        .disease_name); // Sorting in alphabetical order
    });

    // Append each sorted disease to the dropdown
    $.each(filteredDiseases, function (index, disease) {
      diseaseDropdown.append('<option value="' + disease.disease_cd + '">' +
        disease.disease_name + '</option>');
    });
  }
});


$('#disease_cd').on('change', function () {
  var selectedDiseaseCd = $(this).val();

  if (selectedDiseaseCd) {
    var filteredSymptoms = symptoms.filter(function (item) {
      return item.disease_cd == selectedDiseaseCd;
    });

    var crop_symptom_data = new DataTable('#tblCropSymptoms');
    crop_symptom_data.clear().draw();


    $.each(filteredSymptoms, function (index, item) {
      var arr_symp_dtls = [++index, item.symptom,
      item.disease_name, '<a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' +
      item.id + '" data-disease-cd="' + item.disease_cd +
      '" data-symptom="' + item.symptom + '" data-language-cd="' + item
        .language_cd + '"><i class="tf-icons bx bx-edit"></i> Edit</a>' +
      '<a href="#" class="btn btn-sm btn-outline-danger delete-btn" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="' +
      item.id + '"><i class="tf-icons bx bx-trash"></i></a>'];
      crop_symptom_data.row.add(arr_symp_dtls);
    });
    crop_symptom_data.draw();
    $('#tableContainer').show();

  }
  else
    $('#tableContainer').hide();
});

$('#editModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var id = button.data('id');
  var symptom = button.data('symptom');
  var diseaseCd = button.data('disease-cd');
  var languageCd = button.data('language-cd');

  var modal = $(this);
  modal.find('#id').val(id);
  modal.find('#symptom').val(symptom);
  modal.find('#language_cd').val(languageCd).trigger('change');

  // Populate disease dropdown based on the current crop type
  var cropTypeCd = $('#crop_type_cd').val(); // Assuming you have a way to get this
  var diseaseDropdown = modal.find('#disease_cd_edit');
  diseaseDropdown.empty();
  diseaseDropdown.append('<option value="">Select Disease</option>');

  if (cropTypeCd) {
    var filteredDiseases = diseases[cropTypeCd] || [];

    $.each(filteredDiseases, function (index, disease) {
      var selected = disease.disease_cd == diseaseCd ? 'selected' : '';
      diseaseDropdown.append('<option value="' + disease.disease_cd + '" ' +
        selected + '>' + disease.disease_name + '</option>');
    });
  }
});



$('#editForm').on('submit', function (e) {
  e.preventDefault();

  var isValid = true;
  var symptom = $('#symptom').val().trim();
  var diseaseCd = $('#disease_cd_edit').val().trim();
  var languageCd = $('#language_cd').val().trim();
  var form = $(this);

  $('#symptom').removeClass('is-invalid');
  $('#disease_cd_edit').removeClass('is-invalid');
  $('#language_cd').removeClass('is-invalid');
  $('.invalid-feedback').hide();

  if (symptom === '') {
    $('#symptom').addClass('is-invalid');
    $('.invalid-feedback').filter('.symptom-feedback').show();
    isValid = false;
  }

  if (diseaseCd === '') {
    $('#disease_cd_edit').addClass('is-invalid');
    $('.invalid-feedback').filter('.disease-feedback').show();
    isValid = false;
  }

  if (languageCd === '') {
    $('#language_cd').addClass('is-invalid');
    $('.invalid-feedback').filter('.language-feedback').show();
    isValid = false;
  }

  if (isValid) {
    form.off('submit').submit();
  }
});

$('#deleteModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var id = button.data('id');

  var modal = $(this);
  modal.find('#deleteId').val(id);
});

$('#deleteForm').on('submit', function (e) {
  e.preventDefault();

  var form = $(this);
  form.off('submit').submit();
});
