// Simple test script to verify Chart.js is working
document.addEventListener('DOMContentLoaded', function () {
    console.log('Chart test script loaded');

    // Check if Chart.js is available
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded!');
        document.body.innerHTML += '<div style="background-color: red; color: white; padding: 20px; position: fixed; top: 0; left: 0; right: 0; z-index: 9999;">Chart.js is not loaded!</div>';
        return;
    }

    console.log('Chart.js is loaded successfully');

    // Create a test chart
    try {
        const testCanvas = document.createElement('canvas');
        testCanvas.id = 'testChart';
        testCanvas.width = 400;
        testCanvas.height = 200;
        testCanvas.style.position = 'fixed';
        testCanvas.style.top = '10px';
        testCanvas.style.right = '10px';
        testCanvas.style.zIndex = '9999';
        testCanvas.style.backgroundColor = 'white';
        testCanvas.style.border = '1px solid black';
        document.body.appendChild(testCanvas);

        new Chart(testCanvas, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: 'Test Chart',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        console.log('Test chart created successfully');
    } catch (e) {
        console.error('Error creating test chart:', e);
        document.body.innerHTML += '<div style="background-color: red; color: white; padding: 20px; position: fixed; top: 0; left: 0; right: 0; z-index: 9999;">Error creating chart: ' + e.message + '</div>';
    }
});