$(document).ready(function() {
    fetchTripsData();

    // Check user login status
    $.ajax({
        url: '../../backend/includes/checkAuthentication.php',
        method: 'GET',
        success: function(response) {
            if (response.loggedIn === true) {
                $('#navbar-content').html('Welcome, ' + response.FullName + ' | <a href="../../backend/functions/logout.php">Logout</a>');

                // Enable checkbox selection
                $('input[name="tripSelect"]').prop('disabled', false);
            } else {
                $('#navbar-content').html('<a href="login.html">Login</a> | <a href="register.html">Register</a>');

                // Disable checkbox selection
                // $('input[name="tripSelect"]').prop('disabled', true);
                // Add event listener to show toast
                $('input[name="tripSelect"]').on('click', function() {
                    $('#loginToast').toast('show');
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error: ', status, error);
        }
    });

    $('.toast').toast();

    const bookingForm = $('#bookingForm');

    // Handle form submission
    bookingForm.on('submit', function(event) {
        event.preventDefault();

        const selectedTrips = [];
        $('input[name="tripSelect"]:checked').each(function() {
            let tripId = $(this).val();
            console.log('tripId: ',tripId);
            const ticketCount = $(`input[name="ticketCount-${tripId}"]`).val();
            selectedTrips.push({ tripId, ticketCount });
        });

        console.log(selectedTrips);
        $.ajax({
            url: '../../backend/functions/bookTrip.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ trips: selectedTrips }),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Booking successful!',
                        showConfirmButton: true,
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Booking failed: ' + response.message,
                        showConfirmButton: true
                    });
                }
            },
            error: function(error) {
                console.error('Error booking trips:', error);
                alert('An error occurred while booking the trips.');
            }
        });
    });
});

function fetchTripsData() {
    $.ajax({
        url: '../../backend/functions/trips_crud.php',
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

function displayTrips(trips) {
    // Clear the table body
    let tableBody = $('#tripsTable');
    tableBody.empty();

    // Loop through the people array and add each person to the table
    $.each(trips, function(index, trip) {
        let formattedPrice = currencyFormatter.format(trip.Price);
        let formattedDepartureTime = dateTimeFormatter.format(new Date(trip.DepartureTime));
        let formattedArrivalTime = dateTimeFormatter.format(new Date(trip.ArrivalTime));

        let newRow = '<tr>' +
            '<td>' + '<input type="checkbox" name="tripSelect" value="' + trip.Id + '">' + '</td>' +
            '<td>' + trip.DepartureLocation + '</td>' +
            '<td>' + trip.ArrivalLocation + '</td>' +
            '<td>' + formattedDepartureTime + '</td>' +
            '<td>' + formattedArrivalTime + '</td>' +
            '<td>' + formattedPrice  + '</td>' +
            '<td>' + trip.Bus + '</td>' +
            '<td>' + '<input type="number" name="ticketCount-' + trip.Id + '" min="1" value="1">' + '</td>' +
            '</tr>';
        tableBody.append(newRow);
    });
}

// Function to calculate and display total price
function updateTotalPrice() {
    let totalPrice = 0;

    // Iterate over selected trips and calculate total price
    $('input[name="tripSelect"]:checked').each(function() {
        let tripId = $(this).val();
        let ticketCount = parseInt($('input[name="ticketCount-' + tripId + '"]').val());
        let tripPriceText = $('#tripsTable tr').eq($(this).closest('tr').index()).find('td:eq(5)').text();
        // Extract the numerical value of the price
        let tripPrice = parseFloat(tripPriceText.replace(/[^0-9.-]+/g,""));

        totalPrice += tripPrice * ticketCount;
    });

    // Display total price
    $('#totalPrice').text(currencyFormatter.format(totalPrice));
}

// Event listener for trip selection change and ticket count change
$(document).on('change', 'input[name="tripSelect"], input[name^="ticketCount-"]', function() {
    updateTotalPrice();
});

// Initial call to update total price when the page loads
updateTotalPrice();

