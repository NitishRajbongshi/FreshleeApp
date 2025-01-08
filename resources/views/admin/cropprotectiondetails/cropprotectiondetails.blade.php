@extends('admin.common.layout')

@section('title', 'All Crop Variety Management')

@section('main')
    <div id="successAlert" class="alert alert-success alert-dismissible fade d-none" role="alert">
        <span class="alert-message"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">All Crop Control Measure Management</h5>
        </div>

        <div class="px-4 py-2">
            <div class="mb-3">
                <label for="crop_type_cd" class="form-label">Select Crop Type</label>
                <select class="form-select" id="crop_type_cd">
                    <option value="">Select Crop Type</option>
                    @foreach ($cropTypes as $code => $description)
                        <option value="{{ $code }}">{{ $description }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="crop_name_cd" class="form-label">Select Crop Name</label>
                <select class="form-select" id="crop_name_cd">
                    <option value="">Select Crop Name</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="disease_cd" class="form-label">Select Disease</label>
                <select class="form-select" id="disease_cd">
                    <option value="">Select Disease</option>
                </select>
            </div>

            <div class="container">
                <form id="combinedForm" action="{{ route('admin.cropprotectiondetails.cropprotectiondetails.saveCropProtectionDetails') }}"
                 method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="crop_type_cd" id="crop_type_cd_input" value="">
                    <input type="hidden" name="crop_name_cd" id="crop_name_cd_input" value="">


                    <div class="row px-4 py-2" id="cardContainer" style="display:none;">
                        <div class="col-md-12">
                            <div class="card text-center" id="addDetailsCard"
                                style="background-color: #ffe5b4; cursor: pointer;">
                                <div class="card-body">
                                    <i class="tf-icons bx bx-plus-medical"></i>
                                    <h6 class="card-title mt-2">Add Details</h6>
                                </div>
                            </div>
                            <div class="form-container mt-3" id="detailsForm" style="display:none;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="control_measure" class="form-label">Control Measure</label>
                                            <textarea class="form-control" id="control_measure" name="control_measure" rows="4"></textarea>
                                            <div class="invalid-feedback control_measure-feedback" style="display: none;">
                                                Please provide Control Measure</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="control_measure_as" class="form-label">Control Measure
                                                (Assamese)</label>
                                            <textarea class="form-control" rows="4" id="control_measure_as" name="control_measure_as"></textarea>
                                        </div>
                                    </div>

                                    <input type="hidden" name="tech_key_words_and_codes" id="tech_key_words_and_codes" >

                                    <div class="col-md-4">
                                        <div class="mb-3" id="keywordeng">
                                            <label for="keyword" class="form-label">Keywords</label>
                                            <input type="text" class="form-control" id="keyword" name="keyword"
                                                rows="4">
                                        </div>
                                        <button type="button" class="btn btn-link" id="addKeywordBtn">Add Keyword</button>

                                        <div class="mb-3" id="keywordas">
                                            <label for="keyword_as" class="form-label">Keywords
                                                (Assamese)</label>
                                            <input type="text" class="form-control" rows="4" id="keyword_as"
                                                name="keyword_as">
                                        </div>
                                        <button type="button" class="btn btn-link" id="addKeywordAsBtn">Add Assamese Keyword</button>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="imagepath1" class="form-label">Imagepath 1</label>
                                            <img id="image1" class="img-thumbnail mb-2" alt="Image 1 preview"
                                                style="width: 70px; height: 70px;">
                                            <input type="file"
                                                class="form-control @error('imagepath1') is-invalid @enderror"
                                                id="imagepath1" name="imagepath1"
                                                accept="image/png, image/jpeg, image/jpg" onchange="previewImage(1)">
                                                <div class="invalid-feedback imagepath1-feedback" style="display: none;">
                                                    Please provide image 1</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="imagepath2" class="form-label">Imagepath 2</label>
                                            <img id="image2" class="img-thumbnail mb-2" alt="Image 2 preview"
                                                style="width: 70px; height: 70px;">
                                            <input type="file"
                                                class="form-control @error('imagepath2') is-invalid @enderror"
                                                id="imagepath2" name="imagepath2"
                                                accept="image/png, image/jpeg, image/jpg" onchange="previewImage(2)">
                                                <div class="invalid-feedback imagepath2-feedback" style="display: none;">
                                                    Please provide image 2</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="imagepath3" class="form-label">Imagepath 3</label>
                                            <img id="image3" class="img-thumbnail mb-2" alt="Image 3 preview"
                                                style="width: 70px; height: 70px;">
                                            <input type="file"
                                                class="form-control @error('imagepath3') is-invalid @enderror"
                                                id="imagepath3" name="imagepath3"
                                                accept="image/png, image/jpeg, image/jpg" onchange="previewImage(3)">
                                                <div class="invalid-feedback imagepath3-feedback" style="display: none;">
                                                    Please provide image 3</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row px-4 py-2" id="buttonContainer" style="display:none;">
                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" id="cancelButton" class="btn btn-warning">Cancel</button>
                                    <button type="submit" id="submitButton" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="modal fade" id="keywordModal" tabindex="-1" aria-labelledby="keywordModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="keywordModalLabel">Add Keyword with Product Type and Technical
                                    Name</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="modalKeyword" class="form-label">Keyword</label>
                                    <input type="text" class="form-control" id="modalKeyword" name="modalKeyword">
                                    <div class="invalid-feedback modalKeyword-feedback" style="display: none;">
                                        The keyword is not available in the control measure
                                    </div>
                                    <div class="invalid-feedback modalKeywordblank-feedback" style="display: none;">
                                        Please add keyword
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="productType" class="form-label">Product Type</label>
                                    <select class="form-select" id="productType" name="productType">
                                        <option value="">Select Product Type</option>
                                        @foreach ($productTypes as $code => $description)
                                            <option value="{{ $code }}">{{ $description }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback productType-feedback" style="display: none;">
                                        Please select Product Type
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="technicalName" class="form-label">Technical Name</label>
                                    <select class="form-select" id="technicalName" name="technicalName">
                                        <option value="">Select Technical Name</option>
                                    </select>
                                    <div class="invalid-feedback technicalName-feedback" style="display: none;">
                                        Please select Technical Name
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="tradeNames" class="form-label">Trade Names</label>
                                    <div id="tradeNames" class="form-check"></div>
                                    <div class="invalid-feedback tradeNames-feedback" style="display: none;">
                                        Please select Trade Name
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" id="modalCloseBtn"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="saveKeywordBtn">Save Keyword</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('custom_js')
        <script>
            function previewImage(imageNumber) {
                const fileInput = $(`#imagepath${imageNumber}`)[0];
                const imageContainer = $(`#image${imageNumber}`);

                if (fileInput.files && fileInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imageContainer.attr('src', e.target.result);
                    };
                    reader.readAsDataURL(fileInput.files[0]);
                }
            }
            document.addEventListener('DOMContentLoaded', function() {
                const allElements = document.querySelectorAll('*');
                  allElements.forEach(el => {
                      el.style.fontSize = '14px';
                  });
                const cropTypeSelect = document.getElementById('crop_type_cd');
                const cropNameSelect = document.getElementById('crop_name_cd');
                const diseaseSelect = document.getElementById('disease_cd');
                const cardContainer = document.getElementById('cardContainer');
                const addDetailsCard = document.getElementById('addDetailsCard');
                const detailsForm = document.getElementById('detailsForm');
                const buttonContainer = document.getElementById('buttonContainer');
                var modal = new bootstrap.Modal(document.getElementById('keywordModal'));
                const productTypeSelect = document.getElementById('productType');
                const technicalNameSelect = document.getElementById('technicalName');
                const tradeNamesContainer = document.getElementById('tradeNames');
                const labelContainer = document.querySelector('label[for="tradeNames"]');
                const saveKeywordBtn = document.getElementById("saveKeywordBtn");
                const keyword = document.getElementById("modalKeyword").value.trim();
                const modalCloseBtn = document.getElementById('modalCloseBtn');
                let controlMeasureValue = "";
                labelContainer.style.visibility = 'hidden';
                const techData = document.getElementById('tech_key_words_and_codes');
                const cancelButton = document.getElementById('cancelButton');

                let cropTypeCd = '';
                let cropNameCd = '';
                let diseaseCd = '';

                if (cancelButton) {
                    cancelButton.addEventListener('click', function() {
                        location.reload();
                    });
                }

                const hideElements = () => {
                    cardContainer.style.display = 'none';
                    detailsForm.style.display = 'none';
                    buttonContainer.style.display = 'none';
                    addDetailsCard.style.display = 'none';
                };

                const updateVisibility = () => {
                    if (cropTypeCd && cropNameCd && diseaseCd) {
                        cardContainer.style.display = 'block';
                        addDetailsCard.style.display = 'block';
                    } else {
                        hideElements();
                    }
                };

                document.getElementById('addKeywordBtn').addEventListener('click', function() {
                    controlMeasureValue = document.getElementById("control_measure").value.trim();
                    modal.show();
                });

                document.getElementById('addKeywordAsBtn').addEventListener('click', function() {
                    controlMeasureValue = document.getElementById("control_measure_as").value.trim();
                    modal.show();
                });

                saveKeywordBtn.addEventListener("click", function() {
                    const modalKeyword = document.getElementById("modalKeyword").value.trim();
                    const productTypeSelected = document.getElementById("productType").value;
                    const technicalNameSelected = document.getElementById("technicalName").value;
                    const tradeNamesSelected = document.querySelectorAll(
                        "#tradeNames input[type='checkbox']:checked");
                    const feedbackElement = document.querySelector(".modalKeyword-feedback");
                    const feedbackElementBlank = document.querySelector(".modalKeywordblank-feedback");
                    const feedbackElementProductType = document.querySelector(".productType-feedback");
                    const feedbackElementTechnicalName = document.querySelector(".technicalName-feedback");
                    const feedbackElementTradeName = document.querySelector(".tradeNames-feedback");

                    if (!modalKeyword) {
                        feedbackElementBlank.style.display = "block";
                        return;
                    } else {
                        feedbackElementBlank.style.display = "none";
                    }

                    if (controlMeasureValue === document.getElementById("control_measure").value.trim()) {
                        if (controlMeasureValue.includes(modalKeyword)) {

                            if (!productTypeSelected) {
                                feedbackElementProductType.style.display = "block";
                                return;
                            } else {
                                feedbackElementProductType.style.display = "none";
                            }

                            if (!technicalNameSelected) {
                                feedbackElementTechnicalName.style.display = "block";
                                return;
                            } else {
                                feedbackElementTechnicalName.style.display = "none";
                                if (tradeNamesContainer.children.length === 0) {
                                    feedbackElementTradeName.style.display = "block";
                                    feedbackElementTradeName.textContent =
                                        "You cannot save the keyword because there are no trade names available.";
                                    return;
                                }
                                if (tradeNamesSelected.length === 0) {
                                    feedbackElementTradeName.style.display = "block";
                                    feedbackElementTradeName.textContent = "Please select Trade Name";
                                    return;
                                } else {
                                    feedbackElementTradeName.style.display = "none";
                                }
                            }

                            feedbackElement.style.display = "none";

                            let techDataObj = {};
                            try {
                                if (techData.value) {
                                    techDataObj = JSON.parse(techData.value);
                                }
                            } catch (error) {
                                console.error("Error parsing techData.value:", error);
                            }

                            if (!techDataObj.key_word_details) {
                                techDataObj.key_word_details = [];
                            }
                            if (!techDataObj.key_word_details_as) {
                                techDataObj.key_word_details_as = [];
                            }

                            const techCode = technicalNameSelected;
                            const techName = modalKeyword;
                            const selectedTradeNames = Array.from(tradeNamesSelected).map(input => input.value);
                            const selectedTradeDetails = Array.from(tradeNamesSelected).map(input => input
                                .getAttribute('data-trade-code'));
                            const selectedTechnicalNames = Array.from(document.getElementById('technicalName')
                                .options).find(option => option.value === techCode);
                            const selectedTechnicalCode = selectedTechnicalNames.getAttribute(
                                'data-technical-code');


                            const keyWordDetailsObj = {
                                key_word: techName,
                                tech_code: selectedTechnicalCode,
                                tech_name: techCode,
                                trade_codes: selectedTradeDetails,
                                trade_names: selectedTradeNames
                            };

                            const existingDetail = techDataObj.key_word_details.find(detail => detail
                                .tech_code === techCode);
                            if (!existingDetail) {
                                techDataObj.key_word_details.push(keyWordDetailsObj);
                            }


                            techData.value = JSON.stringify(techDataObj);

                            console.log("techData", techData.value);

                            const keywordInput = document.getElementById('keyword');
                            let existingValue = keywordInput.value.trim();

                            if (existingValue) {
                                keywordInput.value = existingValue + ", " + modalKeyword;
                            } else {
                                keywordInput.value = modalKeyword;
                            }

                            document.getElementById('keywordeng').style.visibility = 'visible';
                            modal.hide();
                            clearModal();
                        } else {
                            feedbackElement.style.display = "block";
                        }

                    } else if (controlMeasureValue === document.getElementById("control_measure_as").value
                        .trim()) {
                        if (controlMeasureValue.includes(modalKeyword)) {

                            if (!productTypeSelected) {
                                feedbackElementProductType.style.display = "block";
                                return;
                            } else {
                                feedbackElementProductType.style.display = "none";
                            }

                            if (!technicalNameSelected) {
                                feedbackElementTechnicalName.style.display = "block";
                                return;
                            } else {
                                feedbackElementTechnicalName.style.display = "none";
                                if (tradeNamesContainer.children.length === 0) {
                                    feedbackElementTradeName.style.display = "block";
                                    feedbackElementTradeName.textContent =
                                        "You cannot save the keyword because there are no trade names available.";
                                    return;
                                }
                                if (tradeNamesSelected.length === 0) {
                                    feedbackElementTradeName.style.display = "block";
                                    feedbackElementTradeName.textContent = "Please select Trade Name";
                                    return;
                                } else {
                                    feedbackElementTradeName.style.display = "none";
                                }
                            }
                            feedbackElement.style.display = "none";

                            let techDataObj = {};
                            try {
                                if (techData.value) {
                                    techDataObj = JSON.parse(techData.value);
                                }
                            } catch (error) {
                                console.error("Error parsing techData.value:", error);
                            }

                            if (!techDataObj.key_word_details) {
                                techDataObj.key_word_details = [];
                            }
                            if (!techDataObj.key_word_details_as) {
                                techDataObj.key_word_details_as = [];
                            }

                            const techCode = technicalNameSelected;
                            const techName = modalKeyword;
                            const selectedTradeNames = Array.from(tradeNamesSelected).map(input => input.value);
                            const selectedTradeDetails = Array.from(tradeNamesSelected).map(input => input
                                .getAttribute('data-trade-code'));
                            const selectedTechnicalNames = Array.from(document.getElementById('technicalName')
                                .options).find(option => option.value === techCode);
                            const selectedTechnicalCode = selectedTechnicalNames.getAttribute(
                                'data-technical-code');


                            const keyWordDetailsObj = {
                                key_word: techName,
                                tech_code: selectedTechnicalCode,
                                tech_name: techCode,
                                trade_codes: selectedTradeDetails,
                                trade_names: selectedTradeNames
                            };

                            const existingDetailAs = techDataObj.key_word_details_as.find(detail => detail
                                .tech_code === techCode);
                            if (!existingDetailAs) {
                                techDataObj.key_word_details_as.push(keyWordDetailsObj);
                            }

                            techData.value = JSON.stringify(techDataObj);

                            const keywordAsInput = document.getElementById('keyword_as');
                            let existingValue = keywordAsInput.value.trim();

                            if (existingValue) {
                                keywordAsInput.value = existingValue + ", " + modalKeyword;
                            } else {
                                keywordAsInput.value = modalKeyword;
                            }

                            document.getElementById('keywordas').style.visibility = 'visible';
                            modal.hide();
                            clearModal();

                        } else {
                            feedbackElement.style.display = "block";
                        }
                    } else {
                        feedbackElement.style.display = "block";
                    }
                });

                modalCloseBtn.addEventListener('click', function() {
                    clearModal();
                });

                technicalNameSelect.addEventListener("change", function() {
                    const feedbackElementTradeName = document.querySelector(".tradeNames-feedback");
                    feedbackElementTradeName.style.display = "none";
                });

                function clearModal() {
                    document.getElementById("modalKeyword").value = "";
                    const feedbackElement = document.querySelector(".modalKeyword-feedback");
                    const feedbackElementProductType = document.querySelector(".productType-feedback");
                    const feedbackElementTechnicalName = document.querySelector(".technicalName-feedback");
                    const feedbackElementTradeName = document.querySelector(".tradeNames-feedback");
                    const feedbackElementBlank = document.querySelector(".modalKeywordblank-feedback");

                    feedbackElement.style.display = "none";
                    feedbackElementBlank.style.display = "none";
                    feedbackElementProductType.style.display = "none";
                    feedbackElementTechnicalName.style.display = "none";
                    feedbackElementTradeName.style.display = "none";
                    controlMeasureValue = "";
                    document.getElementById("productType").value = "";
                    document.getElementById("technicalName").value = "";
                    tradeNamesContainer.innerHTML = "";
                    labelContainer.style.visibility = 'hidden';
                }

                document.getElementById('keyword').addEventListener('input', function(event) {
                    handleKeywordChange(event, false);
                });

                document.getElementById('keyword_as').addEventListener('input', function(event) {
                    handleKeywordChange(event, true);
                });

                function removeKeywordFromTechData(keyword, isAsamese = false) {
                    let techDataObj = {};
                    try {
                        if (document.getElementById('tech_key_words_and_codes').value) {
                            techDataObj = JSON.parse(document.getElementById('tech_key_words_and_codes').value);
                        }
                    } catch (error) {
                        console.error("Error parsing techData.value:", error);
                        return;
                    }

                    const keywordArrayKey = isAsamese ? 'key_word_details_as' : 'key_word_details';

                    const updatedKeywordDetails = techDataObj[keywordArrayKey].filter(detail => detail.key_word !==
                        keyword);
                    techDataObj[keywordArrayKey] = updatedKeywordDetails;

                    document.getElementById('tech_key_words_and_codes').value = JSON.stringify(techDataObj);
                    console.log("Updated techData:", document.getElementById('tech_key_words_and_codes').value);
                }


                function handleKeywordChange(event, isAsamese = false) {
                    const keywordInput = event.target;
                    const currentKeywords = keywordInput.value.split(',').map(keyword => keyword.trim());

                    const techDataObj = JSON.parse(techData.value || '{}');
                    const keywordArrayKey = isAsamese ? 'key_word_details_as' : 'key_word_details';


                    techDataObj[keywordArrayKey].forEach(detail => {
                        if (!currentKeywords.includes(detail.key_word)) {
                            removeKeywordFromTechData(detail.key_word, isAsamese);
                        }
                    });
                }


                cropTypeSelect.addEventListener('change', function() {
                    cropTypeCd = this.value;
                    cropNameSelect.innerHTML = '<option value="">Select Crop Name</option>';
                    diseaseSelect.innerHTML = '<option value="">Select Disease</option>';
                    cropNameCd = '';
                    diseaseCd = '';
                    updateVisibility();

                    if (cropTypeCd) {
                        fetch(`/admin/crop-names?crop_type_cd=${cropTypeCd}`)
                            .then(response => response.json())
                            .then(data => {
                                const sortedData = Object.entries(data).sort((a, b) => a[1].localeCompare(b[
                                    1]));
                                sortedData.forEach(([key, value]) => {
                                    const option = document.createElement('option');
                                    option.value = key;
                                    option.textContent = value.toUpperCase();
                                    cropNameSelect.appendChild(option);
                                });
                            })
                            .catch(error => console.error('Error fetching crop names:', error));
                    }
                });

                cropNameSelect.addEventListener('change', function() {
                    cropNameCd = this.value;
                    diseaseSelect.innerHTML = '<option value="">Select Disease</option>';
                    diseaseCd = '';
                    updateVisibility();

                    if (cropNameCd) {
                        fetch(`/admin/diseases?crop_name_cd=${cropNameCd}`)
                            .then(response => response.json())
                            .then(data => {
                                const sortedData = Object.entries(data).sort((a, b) => a[1].localeCompare(b[
                                    1]));
                                sortedData.forEach(([key, value]) => {
                                    const option = document.createElement('option');
                                    option.value = key;
                                    option.textContent = value.toUpperCase();
                                    diseaseSelect.appendChild(option);
                                });
                            })
                            .catch(error => console.error('Error fetching diseases:', error));
                    }
                });

                diseaseSelect.addEventListener('change', function() {
                    diseaseCd = this.value;
                    detailsForm.style.display = 'none';
                    buttonContainer.style.display = 'none';
                    updateVisibility();

                    if (diseaseCd) {
                        fetch(`/admin/crop-disease?disease_cd=${diseaseCd}&crop_name_cd=${cropNameCd}`)
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById('control_measure').value = data[0]
                                    ?.control_measure || '';
                                document.getElementById('control_measure_as').value = data[0]
                                    ?.control_measure_as || '';
                                document.getElementById('image1').src = data[0]?.imagepath1 || '';
                                document.getElementById('image2').src = data[0]?.imagepath2 || '';
                                document.getElementById('image3').src = data[0]?.imagepath3 || '';

                                techData.value = data[0]?.tech_key_words_and_codes || '';

                                if (data[0]?.tech_key_words_and_codes) {
                                    let techKeywords;

                                    try {
                                        techKeywords = typeof data[0].tech_key_words_and_codes ===
                                            'string' ?
                                            JSON.parse(data[0].tech_key_words_and_codes) :
                                            data[0].tech_key_words_and_codes;
                                    } catch (error) {
                                        techKeywords = null;
                                    }

                                    if (techKeywords?.key_word_details?.length > 0) {
                                        const techNames = techKeywords.key_word_details.map(detail => detail.key_word);
                                        document.getElementById('keyword').value = techNames.join(', ');
                                        document.getElementById('keywordeng').style.visibility = 'visible';
                                    } else {
                                        document.getElementById('keywordeng').style.visibility = 'hidden';
                                    }

                                    if (techKeywords?.key_word_details_as?.length > 0) {
                                        const techNamesAs = techKeywords.key_word_details_as.map(detail => detail.key_word);
                                        document.getElementById('keyword_as').value = techNamesAs.join(', ');
                                        document.getElementById('keywordas').style.visibility = 'visible';
                                    } else {
                                        document.getElementById('keywordas').style.visibility = 'hidden';
                                    }

                                } else {
                                    document.getElementById('keywordeng').style.visibility = 'hidden';
                                    document.getElementById('keywordas').style.visibility = 'hidden';
                                }
                            })
                            .catch(error => console.error('Error fetching variety details:', error));
                    }
                });

                addDetailsCard.addEventListener('click', function() {
                    if (detailsForm.style.display === 'none') {
                        detailsForm.style.display = 'block';
                        buttonContainer.style.display = 'block';
                    } else {
                        detailsForm.style.display = 'none';
                        buttonContainer.style.display = 'none';
                    }
                });

                hideElements();

                productTypeSelect.addEventListener('change', function() {
                    const productTypeCd = this.value;
                    if (productTypeCd) {
                        $.ajax({
                            url: '{{ route('admin.cropprotectiondetails.getTechnicalNameByProductType') }}',
                            type: 'GET',
                            data: {
                                product_type_cd: productTypeCd
                            },
                            success: function(data) {
                                populateTechnicalNames(data);
                            }
                        });
                    } else {
                        technicalNameSelect.innerHTML = '<option value="">Select Technical Name</option>';
                    }
                });

                function populateTechnicalNames(technicalCodes) {
                    technicalNameSelect.innerHTML = '<option value="">Select Technical Name</option>';

                    const technicalCodesArray = Object.values(technicalCodes);

                    technicalCodesArray.forEach(function(technicalCode) {
                        const option = document.createElement('option');
                        option.value = technicalCode.technical_name;
                        option.textContent = technicalCode.technical_name;
                        option.setAttribute('data-technical-code', technicalCode.technical_code);
                        technicalNameSelect.appendChild(option);
                    });
                }


                technicalNameSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const technicalNameCd = selectedOption.getAttribute('data-technical-code');

                    if (technicalNameCd) {
                        $.ajax({
                            url: '{{ route('admin.cropprotectiondetails.getTradeNamesByTechnicalCodes') }}',
                            type: 'GET',
                            data: {
                                technical_code: technicalNameCd
                            },
                            success: function(data) {
                                populateTradeNamesCheckboxes(data);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error fetching trade names:', error);
                                console.log('Response:', xhr.responseText);
                            }
                        });
                    }
                });


                function populateTradeNamesCheckboxes(tradeNames) {
                    tradeNamesContainer.innerHTML = '';

                    if (tradeNames.length > 0) {
                        labelContainer.style.visibility = 'visible';
                        tradeNames.forEach(trade => {
                            const checkbox = document.createElement('div');
                            checkbox.classList.add('form-check');
                            checkbox.innerHTML = `
                                <input class="form-check-input" type="checkbox" value="${trade.trade_name}" data-trade-code="${trade.trade_code}" data-technical-name="${trade.technical_name}" id="tradeName_${trade.trade_name}">
                                <label class="form-check-label" for="tradeName_${trade.trade_name}">
                                    ${trade.trade_name}
                                </label>
                            `;
                            tradeNamesContainer.appendChild(checkbox);
                        });
                    } else {
                        labelContainer.style.visibility = 'hidden';
                    }
                }


                $('#combinedForm').on('submit', function(e) {
                    e.preventDefault();

                    var isValid = true;
                    var cropTypeCd = $('#crop_type_cd').val().trim();
                    var cropNameCd = $('#crop_name_cd').val().trim();
                    var diseaseCd = $('#disease_cd').val().trim();
                    var controlMeasure = $('#control_measure').val().trim();
                    var controlMeasureAs = $('#control_measure_as').val().trim();
                    var image1File = $('#imagepath1')[0].files[0];
                    var image2File = $('#imagepath2')[0].files[0];
                    var image3File = $('#imagepath3')[0].files[0];
                    var techDataJson = $('#tech_key_words_and_codes').val().trim();


                    console.log("Initial techDataJson:", techDataJson);

                    $('#control_measure').removeClass('is-invalid');
                    $('.invalid-feedback').hide();


                    if (controlMeasure === '') {
                        $('#control_measure').addClass('is-invalid');
                        $('.invalid-feedback.control_measure-feedback').show();
                        isValid = false;
                    }

                    if (isValid) {
                        var formData = new FormData(this);
                        var actionUrl = $(this).attr('action');
                        var dt = $(this).serialize();

                        if (image1File) {
                            formData.append('image1', image1File);
                        }
                        if (image2File) {
                            formData.append('image2', image2File);
                        }
                        if (image3File) {
                            formData.append('image3', image3File);
                        }

                        formData.append('crop_type_cd', cropTypeCd);
                        formData.append('crop_name_cd', cropNameCd);
                        formData.append('disease_cd', diseaseCd);
                        formData.append('control_measure', controlMeasure);
                        formData.append('control_measure_as', controlMeasureAs);
                        formData.append('tech_key_words_and_codes', techDataJson);

                        $.ajax({
                            url: $('#combinedForm').attr('action'),
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                if (response.success) {
                                    var popupOverlay = document.createElement('div');
                                    popupOverlay.id = 'popupOverlay';
                                    popupOverlay.style.position = 'fixed';
                                    popupOverlay.style.top = '0';
                                    popupOverlay.style.left = '0';
                                    popupOverlay.style.width = '100%';
                                    popupOverlay.style.height = '100%';
                                    popupOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                                    popupOverlay.style.display = 'flex';
                                    popupOverlay.style.justifyContent = 'center';
                                    popupOverlay.style.alignItems = 'center';


                                    var popupBox = document.createElement('div');
                                    popupBox.id = 'popupBox';
                                    popupBox.style.backgroundColor = 'white';
                                    popupBox.style.padding = '20px';
                                    popupBox.style.borderRadius = '10px';
                                    popupBox.style.textAlign = 'center';
                                    popupBox.style.maxWidth = '400px';
                                    popupBox.style.width = '80%';
                                    popupBox.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';


                                    var message = document.createElement('p');
                                    message.innerText = response.success;
                                    message.style.fontSize = '16px';
                                    message.style.fontWeight = 'bold';
                                    popupBox.appendChild(message);


                                    var closeButton = document.createElement('button');
                                    closeButton.innerText = 'Close';
                                    closeButton.style.marginTop = '15px';
                                    closeButton.style.padding = '10px 20px';
                                    closeButton.style.backgroundColor = 'green';
                                    closeButton.style.color = 'white';
                                    closeButton.style.border = 'none';
                                    closeButton.style.borderRadius = '5px';
                                    closeButton.style.cursor = 'pointer';
                                    closeButton.onclick = function() {
                                        document.body.removeChild(popupOverlay);
                                    };

                                    popupBox.appendChild(closeButton);
                                    popupOverlay.appendChild(popupBox);
                                    document.body.appendChild(popupOverlay);

                                    setTimeout(function() {
                                        document.body.removeChild(popupOverlay);
                                    }, 3000);
                                }
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    }
                });
            });
        </script>
    @endsection
