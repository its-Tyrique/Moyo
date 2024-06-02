document.addEventListener("DOMContentLoaded", function() {
    fetchBookingsData();

    function fetchBookingsData() {
        $.ajax({
            url: '../../../backend/functions/bookings_crud.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                displayBookings(data);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching people data: ' + error);
                console.error('Status: ' + status);
                console.error('Response: ' + xhr.responseText);
            }
        });
    }

    function displayBookings(bookings) {
        let tableIndexCounter = 0;
        // Clear the table body
        let tableBody = $('#bookingsTable');
        tableBody.empty();

        // Loop through the people array and add each person to the table
        $.each(bookings, function(index, booking) {
            tableIndexCounter++;
            let newRow = '<tr>' +
                // '<td>' + person.Id + '</td>' +
                '<td>' + tableIndexCounter + '</td>' +
                '<td>' + booking.Account + '</td>' +
                '<td>' + booking.Trip + '</td>' +
                '<td>' + booking.BookingDate + '</td>' +
                '<td>' +
                '<button class="actions-btn" onclick="editRecord('+booking.Id+')">Edit</button>' +
                '<button class="delete-actions-btn" onclick="deleteRecord('+booking.Id+')">Delete</button>' +
                '</td>' +
                '</tr>';
            tableBody.append(newRow);
        });
    }
});