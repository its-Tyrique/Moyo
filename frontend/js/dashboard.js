// chart.js

document.addEventListener('DOMContentLoaded', (event) => {
    // Data for the number of trips chart
    const tripData = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
            label: 'Number of Trips',
            data: [65, 59, 80, 81, 56, 55, 40],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };

    // Options for the number of trips chart
    const tripOptions = {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    // Get the context of the canvas element for number of trips chart
    const tripCtx = document.getElementById('myChart').getContext('2d');

    // Create and render the number of trips chart
    new Chart(tripCtx, {
        type: 'bar',
        data: tripData,
        options: tripOptions
    });

    // Data for the user trend chart
    const userTrendData = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
            label: 'Number of Users',
            data: [200, 300, 400, 500, 600, 700, 800],
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1,
            fill: false
        }]
    };

    // Options for the user trend chart
    const userTrendOptions = {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    // Get the context of the canvas element for user trend chart
    const userTrendCtx = document.getElementById('userTrendChart').getContext('2d');

    // Create and render the user trend chart
    new Chart(userTrendCtx, {
        type: 'line',
        data: userTrendData,
        options: userTrendOptions
    });

    // Data for the bus capacity chart
    const busCapacityData = {
        labels: ['Bus 1', 'Bus 2', 'Bus 3', 'Bus 4', 'Bus 5'],
        datasets: [{
            label: 'Bus Capacity',
            data: [50, 60, 70, 80, 90],
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            borderColor: 'rgba(255, 159, 64, 1)',
            borderWidth: 1
        }]
    };

    // Options for the bus capacity chart
    const busCapacityOptions = {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    // Get the context of the canvas element for bus capacity chart
    const busCapacityCtx = document.getElementById('busCapacityChart').getContext('2d');

    // Create and render the bus capacity chart
    new Chart(busCapacityCtx, {
        type: 'bar',
        data: busCapacityData,
        options: busCapacityOptions
    });

    // Data for the revenue chart
    const revenueData = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
            label: 'Monthly Revenue',
            data: [1000, 2000, 3000, 4000, 5000, 6000, 7000],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            fill: false
        }]
    };

    // Options for the revenue chart
    const revenueOptions = {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    // Get the context of the canvas element for revenue chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');

    // Create and render the revenue chart
    new Chart(revenueCtx, {
        type: 'line',
        data: revenueData,
        options: revenueOptions
    });
});
