<?php
include '../database/database.php';

// Only process POST requests for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json'); // Set JSON header for AJAX response
    
    try {
        $stmt = $conn->prepare("INSERT INTO employees (firstname, lastname, email, job_title, phone, birth_date) 
                              VALUES (:firstname, :lastname, :email, :job_title, :phone, :birth_date)");

        // Bind parameters
        $stmt->bindParam(':firstname', $_POST['firstname']);
        $stmt->bindParam(':lastname', $_POST['lastname']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':job_title', $_POST['job_title']);
        $stmt->bindParam(':phone', $_POST['phone']);
        $stmt->bindParam(':birth_date', $_POST['birth_date']);

        $stmt->execute();

        echo json_encode(['success' => true]);
        exit;
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// Query to fetch all employees for display
try {
    $stmt = $conn->prepare("SELECT * FROM employees");
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $employees = []; // Empty array if query fails
    echo "<script>console.error('Error fetching employees: " . $e->getMessage() . "');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Dashboard</title>
    <link href="../statics/bootstrap css/bootstrap.min.css" rel="stylesheet">
    <link href="../statics/Employees.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/31e24a5c2a.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="left-sidebar">
    <img src="../images/Logo.jpg" alt="Le Parisien" class="logo">
    <ul class="menu">
        <li><i class="fa fa-home"></i><span><a href="dashboard.php" style="color: white; text-decoration: none;"> Home</a></span></li>
        <li><i class="fa fa-box"></i><span><a href="Inventory.php" style="color: white; text-decoration: none;"> Inventory</a></span></li>
        <li><i class="fa fa-credit-card"></i><span><a href="Payment.php" style="color: white; text-decoration: none;"> Payment</a></span></li>
        
        <li class="dropdown">
            <i class="fa fa-store"></i><span> Retailer</span><i class="fa fa-chevron-down toggle-btn"></i>
            <ul class="submenu">
                <li><a href="supplier.php" style="color: white; text-decoration: none;">Supplier</a></li>
                <li><a href="SupplierOrder.php" style="color: white; text-decoration: none;">Supplier Order</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <i class="fa fa-chart-line"></i><span> Sales</span><i class="fa fa-chevron-down toggle-btn"></i>
            <ul class="submenu">
                <li><a href="Customers.php" style="color: white; text-decoration: none;">Customers</a></li>
                <li><a href="Invoice.php" style="color: white; text-decoration: none;">Invoice</a></li>
                <li><a href="CustomerOrder.php" style="color: white; text-decoration: none;">Customer Order</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <i class="fa fa-store"></i><span> Admin</span><i class="fa fa-chevron-down toggle-btn"></i>
            <ul class="submenu">
                <li><a href="UserManagement.php" style="color: white; text-decoration: none;">User Management </a></li>
                <li><a href="Employees.php" style="color: white; text-decoration: none;">Employees</a></li>
                <li><a href="AuditLogs.php" style="color: white; text-decoration: none;">Audit Logs</a></li>
            </ul>
        </li>
        <li>
            <a href="Reports.php" style="text-decoration: none; color: inherit;">
                <i class="fas fa-file-invoice-dollar"></i><span> Reports</span>
            </a>
        </li>
    </ul>
</div>

<div class="main-content">
    <h1>Employees</h1>
    <div class="d-flex justify-content-between mb-3">
        <div class="search-container">
            <i class="fa fa-search"></i>
            <input type="text" class="form-control" placeholder="Search...">
        </div>
        <div>
            <button class="btn btn-dark" id="newProductBtn" data-bs-toggle="modal" data-bs-target="#employeeModal">New <i class="fa fa-plus"></i></button>
            <select class="btn btn-outline-secondary">
                <option>Order By</option>
                <option>Ascending (A → Z)</option>
                <option>Descending (Z → A)</option>
                <option>Low Price (Ascending)</option>
                <option>High Price (Descending)</option>
                <option>Newest</option>
                <option>Oldest</option>
                <option>Best Seller</option>
            </select>
            <select class="btn btn-outline-secondary">
                <option>Product Type</option>
                <option>Product Supplier</option>
                <option>Below ₱1,000</option>
                <option>₱1,000 - ₱5,000</option>
                <option>₱5,000 - ₱10,000</option>
                <option>Above ₱10,000</option>
                <option>In-Stock</option>
                <option>Out of Stock</option>
            </select>
        </div>
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Job Title</th>
                <th>Phone</th>
                <th>Birth Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($employees)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">No Employees available. Input new employee.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($employee['employee_id']); ?></td>
                        <td><?php echo htmlspecialchars($employee['firstname']); ?></td>
                        <td><?php echo htmlspecialchars($employee['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($employee['email']); ?></td>
                        <td><?php echo htmlspecialchars($employee['job_title']); ?></td>
                        <td><?php echo htmlspecialchars($employee['phone']); ?></td>
                        <td><?php echo htmlspecialchars($employee['birth_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeModalLabel">New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newEmployeeForm">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="firstName">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="firstname" required>
                            </div>
                            <div class="col">
                                <label for="lastName">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="lastname" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="jobTitle">Job Title</label>
                            <input type="text" class="form-control" id="jobTitle" name="job_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="birthDate">Birth Date</label>
                            <input type="date" class="form-control" id="birthDate" name="birth_date" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveEmployeeBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(function(dropdown) {
        var toggleBtn = dropdown.querySelector('.toggle-btn');
        toggleBtn.addEventListener('click', function() {
            dropdown.classList.toggle('active');
        });
    });

    document.getElementById('saveEmployeeBtn').addEventListener('click', function() {
        const form = document.getElementById('newEmployeeForm');
        
        if (form.checkValidity()) {
            const formData = new FormData(form);
            
            fetch('Employees.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Employee saved successfully!');
                    bootstrap.Modal.getInstance(document.getElementById('employeeModal')).hide();
                    location.reload();
                } else {
                    alert('Error saving employee: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the employee');
            });
        } else {
            form.reportValidity();
        }
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>