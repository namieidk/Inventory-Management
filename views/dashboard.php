<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="../statics/bootstrap css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/31e24a5c2a.js" crossorigin="anonymous"></script>
    <style>
        body {
            display: flex;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
        }

        .left-sidebar {
            width: 250px;
            background: #343F79;
            color: white;
            height: 100vh;
            padding: 20px;
            position: fixed;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-y: auto;
        }

        .right-sidebar {
            width: 250px;
            background: #343F79;
            color: white;
            height: 100vh;
            padding: 20px;
            position: fixed;
            right: 0;
            top: 0;
            display: none;
            flex-direction: column;
            align-items: center;
        }

        .logo {
            width: 138px;
            height: 138px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .menu {
            list-style: none;
            padding: 0;
            width: 100%;
        }

        .menu li {
            padding: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            position: relative;
            transition: background-color 0.3s ease;
        }

        .menu li i {
            margin-right: 8px;
        }

        .menu li:hover {
            background: #435299;
        }

        .dropdown {
            position: relative;
            width: 100%;
        }

        .toggle-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .submenu {
            display: none;
            list-style: none;
            padding-left: 0;
            margin-top: 5px;
        }

        .submenu li {
            padding: 10px 15px;
            background: #435299;
            border-left: 3px solid #1abc9c;
            margin-left: 10px; 
            transition: background-color 0.3s ease;
        }

        .submenu li:hover {
            background-color: #5264b3;
            color: white;
        }

        .dropdown.active .submenu {
            display: block;
        }

        .main-content {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
            transition: width 0.3s;
        }

        .main-content.right-sidebar-open {
            width: calc(100% - 520px);
        }

        .cards {
            display: flex;
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            flex: 1;
            text-align: center;
        }

        .right-card {
            width: 200px;
            height: 105px;
            background: white;
            padding: 20px;
            box-shadow: 0 2px 5px #FFFFFF;
            text-align: center;
            margin-bottom: 20px;
        }

        .graph-placeholder {
            background: white;
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }

        .inventory-summary {
            background: white;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            background: white;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #34495e;
            color: white;
        }
    </style>
</head>

<body>
    <div class="left-sidebar">
        <img src="../images/Logo.jpg" alt="Le Parisien" class="logo">
        <ul class="menu">
            <li><i class="fa fa-home"></i><span> Home</span></li>
            <li><i class="fa fa-box"></i><span><a href="Inventory.php" style="color: white; text-decoration: none;"> Inventory</a></span></li>

            <li class="dropdown">
                <i class="fa fa-store"></i><span> Retailer</span><i class="fa fa-chevron-down toggle-btn"></i>
                <ul class="submenu">
                    <li>Supplier</li>
                    <li>Supplier Order</li>
                    <li>Expenses</li>
                    <li>Bills</li>
                </ul>
            </li>

            <li class="dropdown">
                <i class="fa fa-chart-line"></i><span> Sales</span><i class="fa fa-chevron-down toggle-btn"></i>
                <ul class="submenu">
                    <li>Customers</li>
                    <li>Invoice</li>
                    <li>Sales Order</li>
                </ul>
            </li>

            <li><i class="fa fa-file-alt"></i><span> Reports</span></li>
            <li><i class="fa fa-users"></i><span> Employees</span></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1>Welcome, (Username)</h1>
                    <p>15 February 2025</p>
                </div>
                <div class="right-sidebar-toggle" style="display: flex; align-items: center;">
                    <i class="fa fa-bell" style="margin-right: 20px; font-size: 30px; position: relative; top: -20px;"></i>
                    <i class="fa fa-cog" style="margin-right: 20px; font-size: 30px; position: relative; top: -20px; cursor: pointer;"></i>
                    <div>
                        <i class="fa fa-user-circle" style="font-size: 40px; cursor: pointer; position: relative; top: -20px;"
                            onclick="toggleRightSidebar()"></i>
                    </div>
                </div>
            </div>
        </header>


        <div class="cards">
            <div class="card">Total Sales <br> <strong>$30,412</strong></div>
            <div class="card">Total Order <br> <strong>12,980</strong></div>
            <div class="card">Total Customer <br> <strong>2,753</strong></div>
        </div>

        <div class="graph-placeholder">Graph (No details inside)</div>

        <div class="inventory-summary">
            <h3>Inventory Summary</h3>
            <p>Quantity on hand: <strong>1,000,000</strong></p>
            <p>Quantity to be received: <strong>50,000</strong></p>
        </div>

        <div class="recent-orders" style="margin-top: 20px;">
            <h3>Recent Orders</h3>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Payment</th>
                </tr>
                <tr>
                    <td>12563987563</td>
                    <td>18/7/2023</td>
                    <td>CCTV</td>
                    <td>Allan Wood</td>
                    <td>$1,349</td>
                    <td>On Process</td>
                    <td>Cash</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="right-sidebar">
        <div style="display: flex; align-items: center; margin-bottom: 20px; margin-right: 60px;">
            <img src="../images/PFP.jpg" alt="User Image"
                style="width: 60px; height: 57px; border-radius: 50%; margin-right: 10px;">
            <div>
                <span style="font-size: 20px;">Username</span>
                <br>
                <span style="font-size: 10px;">Role</span>
            </div>
        </div>
        <div style="display: flex; align-items: center; margin-bottom: 20px; flex-direction: column;">
            <input type="text" placeholder="Search" 
                style="display: flex; align-items: center; padding: 10px 20px; background-color: white; color: black; border: none; border-radius: 5px; width: 230px; height: 35px;">
            <div style="margin-top: 20px;">
                <div class="right-card" style="color: black; font-size: 20px;">To be Shipped <br> <strong
                        style="font-size: 30px;">3</strong></div>
                <div class="right-card" style="color: black; font-size: 20px;">To be Delivered <br> <strong
                        style="font-size: 30px;">2</strong></div>
                <div class="right-card" style="color: black; font-size: 20px;">To be Invoice<br> <strong
                        style="font-size: 30px;">0</strong></div>
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
    </script>
</body>

</html>
