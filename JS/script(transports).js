document.addEventListener('DOMContentLoaded', function() {
    const transportForm = document.getElementById('transportForm');
    const transportTable = document.getElementById('transportTable').getElementsByTagName('tbody')[0];

    transportForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(transportForm);
        const transportId = formData.get('transportId');

        fetch('../PHP/transport_details.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTransports();
                transportForm.reset();
            } else {
                alert('Error: ' + data.message);
            }
        });
    });

    function loadTransports() {
        fetch('../PHP/transport_details.php')
        .then(response => response.json())
        .then(data => {
            transportTable.innerHTML = '';
            data.forEach(transport => {
                const row = transportTable.insertRow();
                row.innerHTML = `
                    <td>${transport.id}</td>
                    <td>${transport.vehicle_number}</td>
                    <td>${transport.driver_name}</td>
                    <td>${transport.driver_contact}</td>
                    <td>${transport.transport_type}</td>
                    <td class="actions">
                        <button onclick="editTransport(${transport.id})">Edit</button>
                    </td>
                `;
            });
        });
    }

    window.editTransport = function(id) {
        fetch(`../PHP/transport_details.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('transportId').value = data.id;
            document.getElementById('vehicleNumber').value = data.vehicle_number;
            document.getElementById('driverName').value = data.driver_name;
            document.getElementById('driverContact').value = data.driver_contact;
            document.getElementById('transportType').value = data.transport_type;
        });
    }

    loadTransports();
});
