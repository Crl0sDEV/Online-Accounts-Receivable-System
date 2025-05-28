<?php
include 'connection.php';
$conn = openCon();

$id = isset($_GET['memberid']) ? intval($_GET['memberid']) : 0;

$sql = "SELECT * FROM member_profiles WHERE memberid = $id"; 
$Result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Multipurpose Cooperative</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            background-color: #f4f7fa;
            height: 100vh;
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

        .sidebar a, .dropdown-btn {
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

        .sidebar a:hover, .dropdown-btn:hover {
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

        .header {
            background-color: #004080;
            color: white;
            padding: 8px 20px;
            font-size: 16px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form-button {
            padding: 10px;
            font-size: 14px;
            background-color: rgb(81, 160, 209);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 48%;
            margin-top: 20px;
        }

        .header .logo {
            font-size: 25px; 
            font-weight: bold;
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
            margin-top: 5px;
        }
        .header .logoutbtn:hover {
            background-color: #1e4e76;
        }

        .main {
            margin-left: 220px; 
            padding: 30px;
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 60px; 
            background-color: #f4f7fa;
        }

        .formcontainer {
            background: rgba(255, 255, 255, 0.97);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 600px; 
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #004080;
            text-align: center;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-right: 20px;
            border: 2px solid #004080;
        }

        .cancel-button {
            background-color: #ccc;
        }

        .cancel-button:hover {
            background-color: #999;
        }
        .profile-name {
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        td strong {
            color: #004080;
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
        <a href="useraccounts.php">User Accounts</a>
    </div>
    
    <div class="header">
        <div class="logo">Multipurpose Cooperative</div>
        <div class="logoutbtn"><button onclick="logout()">Log Out</button></div>
    </div>

<script>
        document.querySelectorAll('.dropdown-btn').forEach(button => {
            button.addEventListener('click', () => {
                const container = button.nextElementSibling;
                container.style.display = container.style.display === 'block' ? 'none' : 'block';
                button.classList.toggle('active');
            });
        });
    function logout() {
        const confirmation = confirm("Are you sure you want to log out?");
        if (confirmation) {
            window.location.href = 'index.php';
        }
    }
</script>

<?php
if (mysqli_num_rows($Result) > 0) {
    $rows = mysqli_fetch_array($Result);
?>

<div class="main">
    <div class="formcontainer">
        <h1>Member Profile</h1>
        <div class="profile-header">
            <img src="anony.png" alt="Profile Picture">
            <div class="profile-name"><?php echo $rows["fullname"] ?></div>
        </div>
        <table>
            <tr>
                <td><strong>Address:</strong></td>
                <td><?php echo $rows["address"] ?></td>
            </tr>
            <tr>
                <td><strong>Birthdate:</strong></td>
                <td><?php echo $rows["birthdate"] ?></td>
            </tr>
            <tr>
                <td><strong>Sex:</strong></td>
                <td><?php echo $rows["sex"] ?></td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td><?php echo $rows["email"] ?></td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td><?php echo $rows["status"] ?></td>
            </tr>
            <tr>
                <td><strong>Application Type:</strong></td>
                <td><?php echo $rows["application_type"] ?></td>
            </tr>
            <tr>
                <td><strong>Date Applied:</strong></td>
                <td><?php echo $rows["date_applied"] ?></td>
            </tr>
        </table>
        <div style="display: flex; justify-content: space-between;">
                <button class="form-button cancel-button" type="button" onclick="window.location.href='memberprofile.php'">Back</button>
            </div>
    </div>
</div>

<?php
} else {
    echo '<p style="text-align: center;">No member found.</p>'; 
}
mysqli_close($conn);
?>
</body>
</html>