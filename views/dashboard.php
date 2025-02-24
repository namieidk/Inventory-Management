<?php
include '../database/database.php';

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: Login.php');
    date_default_timezone_set('Asia/Manila'); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="../statics/bootstrap css/bootstrap.min.css" rel="stylesheet">
    <link href="../statics/dashboard.css" rel="stylesheet">
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
        <header>
            <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                 <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
                    <p><?php echo date('d F Y'); ?></p>
            </div>

                <div class="right-sidebar-toggle" style="display: flex; align-items: center;">
                    <i class="fa fa-bell" style="margin-right: 20px; font-size: 30px; position: relative; top: -20px;"></i>
                    <div style="position: relative; display: inline-block;">
                        <i class="fa fa-cog" id="settingsIcon" style="margin-right: 20px; font-size: 30px; position: relative; top: -20px; cursor: pointer;"></i>
                     <div id="logoutMenu" style="display: none; position: absolute; top: 20px; right: 0; background: white; border: 1px solid #ccc; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);">
                         <a href="logout.php" style="display: block; padding: 8px 20px; text-decoration: none; color: black; white-space: nowrap; position: relative; top: -3px; ">Log out</a>
                     </div>
                </div>
                    <div>
                        <i class="fa fa-user-circle" style="font-size: 40px; cursor: pointer; position: relative; top: -20px;"
                            onclick="toggleRightSidebar()"></i>
                    </div>
                </div>
            </div>
        </header>


        <div class="cards">
            <div class="card">Total Sales <br> <strong></strong></div>
            <div class="card">Total Order <br> <strong></strong></div>
            <div class="card">Total Customer <br> <strong></strong></div>
        </div>

        <div class="graph-placeholder">Graph (No details inside)</div>

        <div class="inventory-summary">
            <h3>Inventory Summary</h3>
            <p>Quantity on hand: <strong></strong></p>
            <p>Quantity to be received: <strong></strong></p>
        </div>

        <div class="recent-orders" style="margin-top: 20px;">
            <h3>Recent Orders</h3>
            <table>
                <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Payment</th>
             </tr>
              </thead>
              <tbody>
                    <tr>
                        <td colspan="7" class="text-center text-muted">No order yet. Add new order.</td>
                     </tr>
              </tbody>
                
            </table>
        </div>
    </div>

    <div class="right-sidebar">
        <div style="display: flex; align-items: center; margin-bottom: 20px; margin-right: 60px;">
            <img src="../images/PFP.jpg" alt="User Image"
                style="width: 60px; height: 57px; border-radius: 50%; margin-right: 10px;">
                <div>
                     <span style="font-size: 20px;">
                          <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?>
                     </span>
                     <br>
                 <span style="font-size: 10px;">
                           <?php echo isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'Admin'; ?>
                 </span>
                </div>

        </div>
        <div style="display: flex; align-items: center; margin-bottom: 20px; flex-direction: column;">
            <input type="text" placeholder="Search" 
                style="display: flex; align-items: center; padding: 10px 20px; background-color: white; color: black; border: none; border-radius: 5px; width: 230px; height: 35px;">
            <div style="margin-top: 20px;">
                <div class="right-card" style="color: black; font-size: 20px;">To be Shipped <br> <strong
                        style="font-size: 30px;"></strong></div>
                <div class="right-card" style="color: black; font-size: 20px;">To be Delivered <br> <strong
                        style="font-size: 30px;"></strong></div>
                <div class="right-card" style="color: black; font-size: 20px;">To be Invoice<br> <strong
                        style="font-size: 30px;"></strong></div>
            </div>
        </div>
    </div>

    <script>
        function toggleRightSidebar() {
            const rightSidebar = document.querySelector('.right-sidebar');
            const mainContent = document.querySelector('.main-content');
            const isOpen = rightSidebar.style.display === 'flex';
            rightSidebar.style.display = isOpen ? 'none' : 'flex';
            mainContent.classList.toggle('right-sidebar-open', !isOpen);
            rightSidebar.style.overflowY = 'auto';
        }

        document.querySelectorAll('.dropdown').forEach(item => {
            item.addEventListener('click', function (e) {
                this.classList.toggle('active');
            });
        });

        const settingsIcon = document.getElementById('settingsIcon');
    const logoutMenu = document.getElementById('logoutMenu');

    settingsIcon.addEventListener('click', () => {
        logoutMenu.style.display = logoutMenu.style.display === 'none' ? 'block' : 'none';
    });

    document.addEventListener('click', (event) => {
        if (!settingsIcon.contains(event.target) && !logoutMenu.contains(event.target)) {
            logoutMenu.style.display = 'none';
        }
    });
    </script>
</body>

</html>
