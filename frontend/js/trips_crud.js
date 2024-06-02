document.addEventListener("DOMContentLoaded", function() {
    fetchBusesData();
    fetchTripsData();

    const tripForm = document.getElementById('tripForm');
    let createTripBtn = document.getElementById('createTripBtn');
    let busNumInput = document.getElementById('busNumber');
    let departureLocationInput = document.getElementById('departureLocation');
    let arrivalLocationInput = document.getElementById('arrivalLocation');
    let departureTimeInput = document.getElementById('departureTime');
    let arrivalTimeInput = document.getElementById('arrivalTime');
    let priceInput = document.getElementById('price');

    const inputLabels = {
        busNumber: 'Bus Number',
        departureLocation: 'Departure Location',
        arrivalLocation: 'Arrival Location',
        departureTime: 'Departure Time',
        arrivalTime: 'Arrival Time',
        price: 'Price'
    };

    busNumInput.addEventListener('change', () => validateInput(busNumInput, "busNumber"));
    departureLocationInput.addEventListener('input' , () => validateInput(departureLocationInput, "departureLocation"));
    arrivalLocationInput.addEventListener('input' , () => validateInput(arrivalLocationInput, "arrivalLocation"));
    departureTimeInput.addEventListener('change', () => validateInput(departureTimeInput, "departureTime"));
    arrivalTimeInput.addEventListener('change', () => validateInput(arrivalTimeInput, "arrivalTime"));
    priceInput.addEventListener('input', () => validateInput(priceInput, "price"));

    function validateInput(inputElement, inputName) {
        let inputValue = inputElement.value.trim();
        let inputType = inputElement.getAttribute('type');
        let isValid = true;

        switch (inputType) {
            case 'text':
                if (inputValue === '') {
                    displayError(inputName, `${inputLabels[inputName]} is required.`);
                    isValid = false;
                } else {
                    clearError(inputName);
                }
                break;
            case 'datetime-local':
                if (inputValue === '') {
                    displayError(inputName, `${inputLabels[inputName]} is required.`);
                    isValid = false;
                } else {
                    let inputDate = new Date(inputValue);
                    let currentDate = new Date();
                    if (inputDate < currentDate) {
                        displayError(inputName, `${inputLabels[inputName]} cannot be in the past.`);
                        isValid = false;
                    } else {
                        clearError(inputName);
                    }
                }
                break;
            case 'number':
                if (inputValue === '') {
                    displayError(inputName, `${inputLabels[inputName]} is required.`);
                    isValid = false;
                } else {
                    let numericValue = parseFloat(inputValue);
                    if (isNaN(numericValue) || numericValue < 0) {
                        displayError(inputName, `${inputLabels[inputName]} cannot be negative.`);
                        isValid = false;
                    } else {
                        clearError(inputName);
                    }

                    //TODO: check why comparison is not working
                    // Additional check for arrival time and departure time
                    if (inputName === 'arrivalTime') {
                        let departureTime = document.getElementById('departureTime').value;
                        if (departureTime) {
                            let departureDate = new Date(departureTime);
                            if (inputDate < departureDate) {
                                displayError(inputName, `${inputLabels[inputName]} cannot be earlier than Departure Time.`);
                                isValid = false;
                            } else {
                                clearError(inputName);
                            }
                        }
                    }
                }
                break;
            default:
                // Specific validation for select elements
                if (inputElement.tagName.toLowerCase() === 'select') {
                    if (inputValue === '' || inputValue === 'Select a XYZ') {
                        displayError(inputName, `${inputLabels[inputName]} must be selected.`);
                        isValid = false;
                    } else {
                        clearError(inputName);
                    }
                } else {
                    clearError(inputName);
                }
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

    createTripBtn.addEventListener("click", function(event) {
        event.preventDefault();

        if (validateForm()) {
            // Serialize form data into JSON object
            let formData = {};
            $(tripForm).serializeArray().forEach(item => {
                formData[item.name] = item.value;
            });
            console.log(formData);

            // Submit the form using jQuery Ajax
            $.ajax({
                type: "POST",
                url: "../../../backend/functions/trips_crud.php",
                data: JSON.stringify(formData),
                dataType: "json",
                contentType: "application/json",
                success: function(response) {
                    console.log('Server Response: '+ response);
                    fetchTripsData();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Trip created',
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
        ['busNumber', 'departureLocation', 'arrivalLocation', 'departureTime', 'arrivalTime', 'price'].forEach(inputName => {
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
            displayBusSelection(data);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching people data: ' + error);
            console.error('Status: ' + status);
            console.error('Response: ' + xhr.responseText);
        }
    });
}

function displayBusSelection(data) {
    const busSelect = $('#busNumber');
    busSelect.empty(); // Clear previous options

    // Add default option
    busSelect.append($('<option>', {
        value: '',
        text: 'Select a Bus'
    }));

    // Add bus options
    $.each(data, function(index, bus) {
        busSelect.append($('<option>', {
            value: bus.Id,
            text: bus.BusNumber
        }));
    });
}

function fetchTripsData() {
    $.ajax({
        url: '../../../backend/functions/trips_crud.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            displayTrips(data);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching people data: ' + error);
            console.error('Status: ' + status);
            console.error('Response: ' + xhr.responseText);
        }
    });
}

function displayTrips(trips) {
    let tableIndexCounter = 0;
    // Clear the table body
    let tableBody = $('#tripsTable');
    tableBody.empty();

    // Define a number formatter for South African currency
    const currencyFormatter = new Intl.NumberFormat('en-ZA', {
        style: 'currency',
        currency: 'ZAR',
        minimumFractionDigits: 2
    });

    // Define a date formatter
    const dateTimeFormatter = new Intl.DateTimeFormat('en-GB', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    });

    // Loop through the people array and add each person to the table
    $.each(trips, function(index, trip) {
        tableIndexCounter++;
        let formattedPrice = currencyFormatter.format(trip.Price);
        let formattedDepartureTime = dateTimeFormatter.format(new Date(trip.DepartureTime));
        let formattedArrivalTime = dateTimeFormatter.format(new Date(trip.ArrivalTime));

        let newRow = '<tr>' +
            // '<td>' + person.Id + '</td>' +
            '<td>' + tableIndexCounter + '</td>' +
            '<td>' + trip.Bus + '</td>' +
            '<td>' + trip.DepartureLocation + '</td>' +
            '<td>' + trip.ArrivalLocation + '</td>' +
            '<td>' + formattedDepartureTime + '</td>' +
            '<td>' + formattedArrivalTime + '</td>' +
            '<td>' + formattedPrice  + '</td>' +
            '<td>' +
            '<button class="btn btn-secondary m-1" onclick="editRecord('+trip.Id+')">Edit</button>' +
            '<button class="btn btn-warning m-1" onclick="deleteTrip('+trip.Id+')">Delete</button>' +
            '</td>' +
            '</tr>';
        tableBody.append(newRow);
    });
}

function deleteTrip(recordId) {
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
                TripId: recordId
            };
            $.ajax({
                type: "POST",
                url: '../../../backend/functions/trips_crud.php',
                data: JSON.stringify(deleteData),
                dataType: 'json',
                contentType: "application/json",
                success: function(response) {
                    console.log('Server Response: '+ response + ' RecordId: ' + recordId);
                    fetchTripsData();
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