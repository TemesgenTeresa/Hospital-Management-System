document.addEventListener('DOMContentLoaded', function() {
    const labForm = document.getElementById('labForm');
    const labTable = document.getElementById('labTable').getElementsByTagName('tbody')[0];

    labForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(labForm);
        const labId = formData.get('labId');

        fetch('../PHP/lab_details.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadLabs();
                labForm.reset();
            } else {
                alert('Error: ' + data.message);
            }
        });
    });

    function loadLabs() {
        fetch('../PHP/lab_details.php')
        .then(response => response.json())
        .then(data => {
            labTable.innerHTML = '';
            data.forEach(lab => {
                const row = labTable.insertRow();
                row.innerHTML = `
                    <td>${lab.id}</td>
                    <td>${lab.lab_test_name}</td>
                    <td>${lab.date}</td>
                    <td>${lab.duration}</td>
                    <td>${lab.cost}</td>
                    <td>${lab.type}</td>
                    <td>${lab.result}</td>
                    <td class="actions">
                        <button onclick="editLab(${lab.id})">Edit</button>
                    </td>
                `;
            });
        });
    }

    window.editLab = function(id) {
        fetch(`../PHP/lab_details.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('labId').value = data.id;
            document.getElementById('labTestName').value = data.lab_test_name;
            document.getElementById('date').value = data.date;
            document.getElementById('duration').value = data.duration;
            document.getElementById('cost').value = data.cost;
            document.getElementById('type').value = data.type;
            document.getElementById('result').value = data.result;
        });
    }

    loadLabs();
});
