document.addEventListener('DOMContentLoaded', function() {
    const employeeForm = document.getElementById('employeeForm');
    const employeeTable = document.getElementById('employeeTable').getElementsByTagName('tbody')[0];

    employeeForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(employeeForm);
        const employeeId = formData.get('employeeId');

        fetch('../PHP/employee_details.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadEmployees();
                employeeForm.reset();
            } else {
                alert('Error: ' + data.message);
            }
        });
    });

    function loadEmployees() {
        fetch('../PHP/employee_details.php')
        .then(response => response.json())
        .then(data => {
            employeeTable.innerHTML = '';
            data.forEach(employee => {
                const row = employeeTable.insertRow();
                row.innerHTML = `
                    <td>${employee.id}</td>
                    <td>${employee.name}</td>
                    <td>${employee.position}</td>
                    <td>${employee.contact}</td>
                    <td class="actions">
                        <button onclick="editEmployee(${employee.id})">Edit</button>
                    </td>
                `;
            });
        });
    }

    window.editEmployee = function(id) {
        fetch(`../PHP/employee_details.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('employeeId').value = data.id;
            document.getElementById('employeeName').value = data.name;
            document.getElementById('employeePosition').value = data.position;
            document.getElementById('employeeContact').value = data.contact;
        });
    }

    loadEmployees();
});
