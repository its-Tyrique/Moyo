document.addEventListener("DOMContentLoaded", function() {
    fetchBookingsData();

});

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

function deleteBooking(recordId) {
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
            $.ajax({
                url: '../../../backend/functions/bookings_crud.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    deletePerson: true,
                    PersonId: recordId
                },
                success: function(response) {
                    console.log('Server Response: '+ response + ' RecordId: ' + recordId);
                    fetchUsersData();
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting record: ' + error);
                }
            });
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
            '<button class="btn btn-secondary m-1" onclick="editRecord('+booking.Id+')">Edit</button>' +
            '<button class="btn btn-warning m-1" onclick="deleteBooking('+booking.Id+')">Delete</button>' +
            '</td>' +
            '</tr>';
        tableBody.append(newRow);
    });
}