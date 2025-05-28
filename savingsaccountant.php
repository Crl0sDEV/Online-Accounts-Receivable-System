<?php
include 'connection.php';
$conn = openCon();
$sql = "SELECT sa.*, m.fullname, m.email 
        FROM savings_accounts sa
        JOIN member_profiles m ON sa.memberid = m.memberid";

$Result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Registered</title>
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
            transition: all 0.3s ease-in-out;
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
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
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
            transition: background-color 0.3s ease;
            line-height: 30px;
            margin-top: -40px;
        }

        .header .user-info:hover {
            background-color: #1e4e76;
        }

        button {
            padding: 8px 16px;
            font-size: 14px;
            background-color: #336699;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1e4e76;
        }

        .dropdown-container a {
            display: block;
            padding: 12px 16px;
            font-size: 14px;
            color: white;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
            transition: background-color 0.3s ease, padding-left 0.3s ease;
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

        button.approve-btn,
        button.disapprove-btn,
        button.delete-btn {
            padding: 6px 12px;
            background-color: #336699;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.approve-btn:hover,
        button.disapprove-btn:hover,
        button.delete-btn:hover {
            background-color: #1e4e76;
        }

        .add-user-btn {
            padding: 8px 16px;
            font-size: 14px;
            background-color: #336699;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-user-btn:hover {
            background-color: #1e4e76;
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
        <button class="dropdown-btn">Loan</button>
        <div class="dropdown-container">
            <a href="loanapplication.php">Loan Application</a>
            <a href="loant.php">Loan Type</a>
            <a href="loana.php">Loan Amortization</a>
            <a href="loani.php">Loan Interest</a>
        </div>
        <button class="dropdown-btn">Fixed</button>
        <div class="dropdown-container">
            <a href="fixedaccsaccountant.php">Accounts</a>
            <a href="fixedtransaccountant.php">Transactions</a>
        </div>
        <button class="dropdown-btn">Savings</button>
        <div class="dropdown-container">
            <a href="savingsaccountant.php">Accounts</a>
            <a href="savingstransaccountant.php">Transactions</a>
        </div>
        <button class="dropdown-btn">Time Deposit</button>
        <div class="dropdown-container">
            <a href="timedepoaccountant.php">Accounts</a>
            <a href="timedepotransaccountant.php">Transactions</a>
        </div>
        <a href="memprofaccountant.php">Member Profile</a>
        <a href="useraccsaccountant.php">User Accounts</a>
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
                    <h1>List of Savings Accounts</h1>
                </div>

                <table>
                    <tr>
                        <th>Member ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Account Number</th>
                        <th>Account Type</th>
                        <th>Created At</th>
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
                                echo "<td>{$row['account_type']}</td>";
                                echo "<td>{$row['created_at']}</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10'>No savings accounts found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>


                <?php mysqli_close($conn); ?>
            </div>
        </div>
    </div>
</body>

</html>