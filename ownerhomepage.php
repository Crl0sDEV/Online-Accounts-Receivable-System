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

        .sidebar a {
            box-sizing: border-box; 
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
        }

        .sidebar a:hover, .dropdown-btn:hover {
            background-color: #336699;
        }

      
        .main {
            background-color: #f4f7fa;
            margin-left: 220px; 
            padding: 30px;
            flex: 1;
            background-color: #fff;
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

        h1 {
            font-size: 45px;
        }
    </style>
</head>
<body>

    <div class="sidebar"><br><br><br><br>
        <a href="ownerhomepage.php">Home</a>
        <a href="usermanagement.php">User Management</a>
    </div>

    <div class="header">
        <div class="logo">Multipurpose Cooperative</div>
        <div class="logoutbtn">
            <button onclick="logout()">Log Out</button> 
        </div>
    </div>
    <script>
        function logout(){
            const confirmation = confirm("Are you sure you want to log out?");
            if (confirmation) {
                window.location.href = 'index.php'; 
            }
        }
    </script>
    <div class="main">
        <h1>Welcome Owner!</h1>
        <p>Explore your services, and manage accounts.</p>
    </div>

</body>
</html>
