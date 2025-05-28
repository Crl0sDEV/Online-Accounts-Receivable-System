<?php
include 'connection.php';
$conn = openCon();
$sql = "SELECT tda.*, m.fullname, m.email 
        FROM time_deposit_accounts tda
        JOIN member_profiles m ON tda.memberid = m.memberid";

$Result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Time Deposit Accounts</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            background-color: #f4f7fa;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #004080;
            color: white;
            padding-top: 15px;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar .name {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a,
        .dropdown-btn {
            display: block;
            color: white;
            padding: 12px 16px;
            font-size: 16px;
            text-decoration: none;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border-radius: 5px;
            margin: 4px 0;
            width: 100%;
            box-sizing: border-box;
        }

        .sidebar a:hover,
        .dropdown-btn:hover {
            background-color: #336699;
        }

        .dropdown-container {
            display: none;
            background-color: #003366;
            padding-left: 0;
            margin-left: 10px;
            margin-right: 10px;
            border-radius: 5px;
            overflow: hidden;
        }

        .main {
            margin-left: 220px;
            padding: 30px;
            flex: 1;
            background-color: #f4f7fa;
            text-align: center;
            margin-top: 60px;
            height: 85vh;
        }

        .header {
            background-color: #004080;
            color: white;
            padding: 8px 20px;
            font-size: 16px;
            text-align: left;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            height: 50px;
        }

        .header .logo {
            font-size: 25px;
            font-weight: bold;
            line-height: 50px;
        }

        .header .logoutbtn {
            float: right;
            font-size: 14px;
            padding: 1px 4px;
            background-color: #336699;
            border-radius: 5px;
            cursor: pointer;
            line-height: 30px;
            margin-top: -40px;
        }

        button {
            padding: 8px 16px;
            font-size: 14px;
            background-color: #336699;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1e4e76;
        }

        .dropdown-container a {
            padding: 12px 16px;
            font-size: 14px;
            color: white;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
            border-radius: 4px;
            margin: 2px 0;
        }

        .dropdown-container a:hover {
            background-color: #336699;
            padding-left: 22px;
        }

        h1 {
            font-size: 32px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #0059b3;
            color: white;
            font-weight: bold;
        }

        .content-box {
            background: rgba(255, 255, 255, 0.97);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 1200px;
        }

        .container {
            flex: 1;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<body>

    <div class="sidebar"><br><br><br><br>
        <a href="adminhomepage.php">Home</a>
        <button class="dropdown-btn">Members</button>
        <div class="dropdown-container">
            <a href="registered.php">Registered</a>
            <a href="memberapplication.php">Application/Pending</a>
        </div>
        <button class="dropdown-btn">Loan</button>
        <div class="dropdown-container">
            <a href="loanapplication.php">Loan Application</a>
            <a href="loant.php">Loan Type</a>
            <a href="loana.php">Loan Amortization</a>
            <a href="loani.php">Loan Interest</a>
        </div>
        <button class="dropdown-btn">Fixed</button>
        <div class="dropdown-container">
            <a href="fixedaccount.php">Accounts</a>
            <a href="fixedtransaction.php">Transactions</a>
        </div>
        <button class="dropdown-btn">Savings</button>
        <div class="dropdown-container">
            <a href="savingsaccount.php">Accounts</a>
            <a href="savingstransaction.php">Transactions</a>
        </div>
        <button class="dropdown-btn">Time Deposit</button>
        <div class="dropdown-container">
            <a href="timedepositsaccount.php">Accounts</a>
            <a href="timedepositstransaction.php">Transactions</a>
        </div>
        <a href="memberprofile.php">Member Profile</a>
        <a href="aboutus.php">About Us</a>
        <a href="contactus.php">Contact Us</a>
        <a href="useraccounts.php">User Accounts</a>
    </div>

    <div class="header">
        <div class="logo">Multipurpose Cooperative</div>
        <div class="logoutbtn"><button>Log Out</button></div>
    </div>

    <script>
        document.querySelectorAll('.dropdown-btn').forEach(button => {
            button.addEventListener('click', () => {
                const container = button.nextElementSibling;
                container.style.display = container.style.display === 'block' ? 'none' : 'block';
                button.classList.toggle('active');
            });
        });
    </script>

    <div class="main">
        <div class="container">
            <div class="content-box">
                <div class="title">
                    <h1>List of Time Deposit Accounts</h1>
                </div>
                <table>
                    <tr>
                        <th>Member ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Account Number</th>
                        <th>Interest Rate</th>
                        <th>Principal Amount</th>
                        <th>Start Date</th>
                        <th>Maturity Date</th>
                    </tr>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($Result) > 0) {
                            while ($row = mysqli_fetch_assoc($Result)) {
                                echo "<tr>";
                                echo "<td>{$row['memberid']}</td>";
                                echo "<td>{$row['fullname']}</td>";
                                echo "<td>{$row['email']}</td>";
                                echo "<td>{$row['account_number']}</td>";
                                echo "<td>{$row['interest_rate']}%</td>";
                                echo "<td>₱" . number_format($row['balance'], 2) . "</td>";
                                echo "<td>" . date('Y-m-d', strtotime($row['created_at'])) . "</td>";
                                echo "<td>" . date('Y-m-d', strtotime($row['maturity_date'])) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10'>No time deposit accounts found.</td></tr>";
                        }
                        mysqli_close($conn);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>