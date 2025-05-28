<!DOCTYPE html>
<html>
<head>
    <title>Contact Us</title>
    <style>
       
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            background-color: #f4f7fa;
            padding: 0;
            font-size: 17px;
        }

        input[type="text"]{
            width: 160%; 
            padding: 7px;
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
            height: 50vh;
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
            padding: 3px 19px; 
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
        input{
                border-radius: 6px;
                padding: 7px; 
            }
            table{
            border-collapse: collapse;
            width: 85%;
            margin: auto;
            background-color: white;
            border: 10px;
            border: 2px solid black;
        }
        td{
            padding:12px;
        }
        h4{
            font-family:Poppins;
            font-size: 16px;
            text-align: center;
            margin: 0;
        }
        h2{
            font-family:Poppins;
            font-size: 33px;
            margin:0;
            text-align: left;
        }
        textarea {
            width: 100%;
            max-width: 500px;
            min-width: 300px;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 8px;
            resize: none;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        textarea:focus {
            border-color: #336699;
            box-shadow: 0 0 5px rgba(51, 102, 153, 0.5);
            outline: none;
        }
    </style>
</head>
<body>

    <div class="sidebar"><br><br><br><br>
        <a href="adminhomepage.php">Home</a>
        <button class="dropdown-btn">Members</button>
        <div class="dropdown-container">
            <a href="registered.php">Registered</a>
            <a href="loanapplication.php">Application/Pending</a>
        </div>
        <button class="dropdown-btn">Loan</button>
        <div class="dropdown-container">
            <a href="loanapplication.php">Loan Application</a>
            <a href="#">Loan Type</a>
            <a href="#">Loan Amortization</a>
            <a href="#">Loan Interest</a>
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
        <button class="logoutbtn" onclick="logout()">Log Out</button>
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

<div class="main">
        <h1>Contact Us</h1>
        <table>
            <tr>
                <td colspan="3"> 
                    <h2>Bulacan State University</h2>
                </td>
            </tr>
            <tr>
                <td>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3856.407774864281!2d120.81214497546308!3d14.858459685659009!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397af7324f0f857%3A0xc21f39ed5b256ece!2sBulacan%20State%20University!5e0!3m2!1sen!2sph!4v1740489816217!5m2!1sen!2sph" 
                    width="400" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </td>
                <td>
                    <h4>Address:</h4><br>
                    <h4>Contact No.:</h4><br>
                    <h4>Email:</h4>
                </td>
                <td>
                    <p>Manila North Road Barangay Guinhawa 3000 Bulacan</p>
                    <p>(044) 919 7800</p>
                    <p>officeofthepresident@bulsu.edu.ph</p>
                </td>
            </tr>
            <tr>
                <td colspan="1"><h2>Feedback</h2></td>
            </tr>
            <tr>
                <td><label for="message"><b>Message:</b></label></td>
                <td colspan="2">
                    <textarea id="message" class="no-resize" name="message" placeholder="Enter your message" rows="5"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;">
                    <button id="btnsubmit" name="btnsubmit">Submit</button>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
