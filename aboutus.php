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
            margin-top: 10px;
            
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
            margin: 0;
        }

    .table-container{
            display:grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px; 
            padding: 20px;
            margin-top: 1px;
    }    
    table{
            border-collapse: collapse;
            border: 2px solid black;
            background-color:white;
            width: 100%;
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
        function logout(){
            const confirmation = confirm("Are you sure you want to log out?");
            if (confirmation) {
                window.location.href = 'index.php'; 
            }
        }
    </script>
            <div class="main"><br>
            <h1>About Us</h1>
            <div class="table-container">
            <div>
            <table>
                <tr>
                    <td><img src="wong.jpg" alt="Pic" width="300" height="300"></td>
                    <td style="text-align: left;">
                        <h4>Micah Jillian Roque Wong</h4><br>
                        <p>Bachelor of Science in Information Technology</p>
                        <p>0915 940 8260</p>
                        <p>wongmicah021@gmail.com</p>
                    </td>
                </tr>
            </table>    
            </div>

        <div>    
        <table>    
            <tr>
                <td><img src="ID Pic 2024.jpg" alt="Pic" width="300" height="300"></td>
                    <td style="text-align: left;">
                        <h4>Jessa Denise Bautista Dangca</h4><br>
                        <p>Bachelor of Science in Information Technology</p>
                        <p>0908 941 4283</p>
                        <p>dangcajessadenise@gmail.com</p>
                    </td>
            </tr>
        </table>    
        </div>

        <div>    
        <table>    
            <tr>
                <td><img src="Marquez_IDPicture.jpg" alt="Pic" width="300" height="300"></td>
                <td style="text-align: left;">
                        <h4>Nicole Almaraz Marquez</h4><br>
                        <p>Bachelor of Science in Information Technology</p>
                        <p>0915 940 8260</p>
                        <p>nicolmarquez041@gmail.com</p>
                </td>
            </tr>
        </table>    
        </div>
    
        <div>    
        <table>    
            <tr>
                <td><img src="clarence.jpeg" alt="Pic" width="300" height="300"></td>
                <td style="text-align: left;">
                        <h4>Clarence Lyle Reyes Tayson</h4><br>
                        <p>Bachelor of Science in Information Technology</p>
                        <p>0915 940 8260</p>
                        <p>taysonclarence@gmail.com</p>
                </td>
            </tr>
        </table>    
        </div>
    </div>
    </div>
</body>
</html>
