<!DOCTYPE html>
<html lang="en">
<head>
    <title>Multipurpose Cooperative</title>
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
            font-size: 45px;
        }
    </style>
</head>
<body>

    <div class="sidebar"><br><br><br><br>
        <a href="memhomepage.php">Home</a>
        <button class="dropdown-btn">Loan</button>
        <div class="dropdown-container">
            <a href="loanapplication.php">Loan Application</a>
            <a href="loantmem.php">Loan Type</a>
            <a href="loanamem.php">Loan Amortization</a>
            <a href="loanimem.php">Loan Interest</a>
        </div>
        <button class="dropdown-btn">Fixed</button>
        <div class="dropdown-container">
            <a href="fixedaccmem.php">Accounts</a>
            <a href="fixedtransmem.php">Transactions</a>
            <a href="fixedhistorymem.php">History</a>
        </div>
        <button class="dropdown-btn">Savings</button>
        <div class="dropdown-container">
            <a href="savingsaccmem.php">Accounts</a>
            <a href="savingstransmem.php">Transactions</a>
            <a href="savingshistorymem.php">History</a>
        </div>
        <button class="dropdown-btn">Time Deposit</button>
        <div class="dropdown-container">
            <a href="timedepoaccmem.php">Accounts</a>
            <a href="timedepotransmem.php">Transactions</a>
            <a href="timedepohistorymem.php">History</a>
        </div>
        <a href="memberprofile.php">Member Profile</a>
        <a href="aboutus.php">About Us</a>
        <a href="contactus.php">Contact Us</a>
        <a href="useraccounts.php">User Accounts</a>
    </div>

    <div class="header">
        <div class="logo">Multipurpose Cooperative</div>
        <div class="logoutbtn"><button onclick="logout()">Log Out</button></div>
    </div>

    <div class="main">
        <h1>Welcome to the Member Portal</h1>
        <p>Explore our services, manage your accounts, and apply for loans effortlessly.</p>
    </div>

    <script>
        document.querySelectorAll('.dropdown-btn').forEach(button => {
            button.addEventListener('click', () => {
                const container = button.nextElementSibling;
                container.style.display = container.style.display === 'block' ? 'none' : 'block';
                button.classList.toggle('active');
            });
        });
        function logout(){
            const confirmation = confirm("Are you sure you want to log out?");
            if (confirmation) {
                window.location.href = 'index.php'; 
            }
        }
    </script>

</body>
</html>
