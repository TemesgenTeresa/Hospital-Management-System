document.addEventListener('DOMContentLoaded', function() {
    const patientForm = document.getElementById('patientForm');
    const patientTable = document.getElementById('patientTable').getElementsByTagName('tbody')[0];

    patientForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(patientForm);
        const patientId = formData.get('patientId');

        fetch('../PHP/patient_details.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadPatients();
                patientForm.reset();
            } else {
                alert('Error: ' + data.message);
            }
        });
    });

    function loadPatients() {
        fetch('../PHP/patient_details.php')
        .then(response => response.json())
        .then(data => {
            patientTable.innerHTML = '';
            data.forEach(patient => {
                const row = patientTable.insertRow();
                row.innerHTML = `
                    <td>${patient.id}</td>
                    <td>${patient.name}</td>
                    <td>${patient.age}</td>
                    <td>${patient.gender}</td>
                    <td>${patient.address}</td>
                    <td>${patient.phone}</td>
                    <td>${patient.email}</td>
                    <td class="actions">
                        <button onclick="editPatient(${patient.id})">Edit</button>
                    </td>
                `;
            });
        });
    }

    window.editPatient = function(id) {
        fetch(`../PHP/patient_details.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('patientId').value = data.id;
            document.getElementById('patientName').value = data.name;
            document.getElementById('patientAge').value = data.age;
            document.getElementById('patientGender').value = data.gender;
            document.getElementById('patientAddress').value = data.address;
            document.getElementById('patientPhone').value = data.phone;
            document.getElementById('patientEmail').value = data.email;
        });
    }

    loadPatients();
});
