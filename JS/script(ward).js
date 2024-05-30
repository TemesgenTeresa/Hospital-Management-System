document.addEventListener('DOMContentLoaded', function() {
    const wardForm = document.getElementById('wardForm');
    const wardTable = document.getElementById('wardTable').getElementsByTagName('tbody')[0];

    wardForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(wardForm);
        const wardId = formData.get('wardId');

        fetch('../PHP/ward_details.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadWards();
                wardForm.reset();
            } else {
                alert('Error: ' + data.message);
            }
        });
    });

    function loadWards() {
        fetch('../PHP/ward_details.php')
        .then(response => response.json())
        .then(data => {
            wardTable.innerHTML = '';
            data.forEach(ward => {
                const row = wardTable.insertRow();
                row.innerHTML = `
                    <td>${ward.id}</td>
                    <td>${ward.ward_name}</td>
                    <td>${ward.ward_type}</td>
                    <td>${ward.capacity}</td>
                    <td class="actions">
                        <button onclick="editWard(${ward.id})">Edit</button>
                    </td>
                `;
            });
        });
    }

    window.editWard = function(id) {
        fetch(`../PHP/ward_details.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('wardId').value = data.id;
            document.getElementById('wardName').value = data.ward_name;
            document.getElementById('wardType').value = data.ward_type;
            document.getElementById('capacity').value = data.capacity;
        });
    }

    loadWards();
});
