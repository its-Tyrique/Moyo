document.addEventListener('DOMContentLoaded', function() {
    fetchBusesData();

    const busForm = document.getElementById('busForm');
    let createBusBtn = document.getElementById('createBusBtn');
    let busNumInput = document.getElementById('busNumber');
    let busCapacityInput = document.getElementById('busCapacity');

    const inputLabels = {
        busNumber: 'Bus Number',
        busCapacity: 'Bus Capacity'
    };

    busNumInput.addEventListener('input', ()=>validateInput(busNumInput, 'busNumber'));
    busCapacityInput.addEventListener('input', ()=>validateInput(busCapacityInput, 'busCapacity'));

    function validateInput(inputElement, inputName) {
        let inputValue = inputElement.value.trim();
        let inputType = inputElement.getAttribute('type');
        let isValid = true;

        // TODO: Add title validation

        switch (inputType) {
            case 'text':
                if (inputValue === '') {
                    displayError(inputName, `${inputLabels[inputName]} is required.`);
                    isValid = false;
                } else {
                    clearError(inputName);
                }
                break;
            case 'number':
                if (inputValue === '') {
                    displayError(inputName, `${inputLabels[inputName]} is required.`);
                    isValid = false;
                    // TODO: check why this is not working
                } else {
                    let numericValue = parseFloat(inputValue);
                    if (isNaN(numericValue) || numericValue < 0) {
                        displayError(inputName, `${inputLabels[inputName]} cannot be negative.`);
                        isValid = false;
                    } else {
                        clearError(inputName);
                    }
                }
            default:
                clearError(inputName);
        }
        return isValid;
    }

    function displayError(inputName, errorMessage) {
        let inputElement = document.getElementById(inputName);
        let errorElement = document.getElementById(`${inputName}-error`);
        errorElement.textContent = errorMessage;
        errorElement.style.display = 'block';
        inputElement.classList.add('is-invalid');
    }

    function clearError(inputName) {
        let inputElement = document.getElementById(inputName);
        let errorElement = document.getElementById(`${inputName}-error`);
        errorElement.textContent = '';
        errorElement.style.display = 'none';
        inputElement.classList.remove('is-invalid');
        inputElement.classList.add('is-valid');
    }

    createBusBtn.addEventListener("click", function(event) {
       event.preventDefault();

       if (validateForm()){
           // Serialize form data into JSON object
           let formData = {};
           $(busForm).serializeArray().forEach(item => {
               formData[item.name] = item.value;
           });
           console.log(formData);

           // Submit the form using jQuery Ajax
           $.ajax({
               type: "POST",
               url: "../../../backend/functions/buses_crud.php",
               data: JSON.stringify(formData),
               dataType: "json",
               contentType: "application/json",
               success: function(response) {
                   console.log('Server Response: '+ response);
                   fetchBusesData();
                   Swal.fire({
                       icon: 'success',
                       title: 'Success!',
                       text: 'Register successful!',
                       showConfirmButton: true,
                   });
               },
               error: function(xhr, status, error) {
                   console.error('Error fetching people data: ' + error);
                   console.error('Status: ' + status);
                   console.error('Response: ' + xhr.responseText);
                   // Display SweetAlert to show error message
                   Swal.fire({
                       icon: 'error',
                       title: 'Error!',
                       text: 'Failed to sign up due to system error.',
                       showConfirmButton: true
                   });
               }
           });
       }
    });

    function validateForm() {
        // Validate all the form fields
        let isValid = true;
        ['busNumber', 'busCapacity'].forEach(inputName => {
            let inputElement = document.getElementById(inputName);
            if (!validateInput(inputElement, inputName)) {
                isValid = false;
                console.log('Invalid input: ', inputName);
            }
        });
        return isValid;
    }
});


function fetchBusesData() {
    $.ajax({
        url: '../../../backend/functions/buses_crud.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            displayBuses(data);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching people data: ' + error);
            console.error('Status: ' + status);
            console.error('Response: ' + xhr.responseText);
        }
    });
}

function displayBuses(buses) {
    let tableIndexCounter = 0;
    // Clear the table body
    let tableBody = $('#busesTable');
    tableBody.empty();

    // Loop through the people array and add each person to the table
    $.each(buses, function(index, bus) {
        tableIndexCounter++;
        let newRow = '<tr>' +
            // '<td>' + person.Id + '</td>' +
            '<td>' + tableIndexCounter + '</td>' +
            '<td>' + bus.BusNumber + '</td>' +
            '<td>' + bus.Capacity + '</td>' +
            '<td>' +
            '<button class="btn btn-secondary m-1" onclick="editBus('+bus.Id+')">Edit</button>' +
            '<button class="btn btn-warning m-1" onclick="deleteBus('+bus.Id+')">Delete</button>' +
            '</td>' +
            '</tr>';
        tableBody.append(newRow);
    });
}

function deleteBus(recordId) {
    console.log('RecordId: ' + recordId);
    // Prompt the user to confirm the deletion
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#007bff',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            let deleteData = {
                deletePerson: true,
                BusId: recordId
            };
            $.ajax({
                type: "POST",
                url: '../../../backend/functions/buses_crud.php',
                data: JSON.stringify(deleteData),
                dataType: 'json',
                contentType: "application/json",
                success: function(response) {
                    console.log('Server Response: '+ response + ' RecordId: ' + recordId);
                    fetchBusesData();
                },
                error: function(xhr, status, error) {
                    console.error('Status: ' + status);
                    console.error('Response: ' + xhr.responseText);
                    console.error('Error deleting record: ' + error);
                }
            });
        }
    });
}