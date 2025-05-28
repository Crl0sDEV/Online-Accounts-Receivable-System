<?php
include 'connection.php';
$conn = openCon();
$sql = "SELECT * FROM ownerusers";
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

        .sidebar a {
            box-sizing: border-box; 
            display: block;
            color: white;
            padding: 12px 16px;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            border-radius: 5px;
            margin: 4px 0;
            width: 100%;
        }

        .sidebar a:hover {
            background-color: #336699;
        }

        .main {
            margin-left: 220px;
            padding: 30px;
            flex: 1;
            background-color: #f4f7fa;
            margin-top: 60px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
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
            display: flex;
            justify-content: space-between;
            align-items: center;

        }

        .header .logo {
            font-size: 25px;
            font-weight: bold;
        }

        .header button.logoutbtn {
            font-size: 14px;
            padding: 9px 19px;
            background-color: #336699;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .header button.logoutbtn:hover {
            background-color: #1e4e76;
        }

        .container {
            flex: 1;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .content-box {
            background: rgba(255, 255, 255, 0.97);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            width: 90%;
            max-width: 1200px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #0059b3;
            color: white;
            font-weight: bold;
        }

        button.view-btn,
        button.edit-btn,
        button.delete-btn {
            padding: 6px 12px;
            background-color: #336699;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.view-btn:hover,
        button.edit-btn:hover,
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

        .title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <br><br><br><br>
        <a href="ownerhomepage.php">Home</a>
        <a href="usermanagement.php">User Management</a>
    </div>

    <div class="header">
        <div class="logo">Multipurpose Cooperative</div>
        <button class="logoutbtn" onclick="logout()">Log Out</button>
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
        <div class="container">
            <div class="content-box">
                <div class="title">
                    <h1>User Management</h1>
                    <a href="adduser.php"><button class="add-user-btn">+ Add New User Account</button></a>
                </div>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                    <tbody>
                    <?php
                        if (mysqli_num_rows($Result) > 0) {
                            $id = 1;
                            while ($rows = mysqli_fetch_array($Result)) {
                                echo "<tr>";
                                echo "<td>{$id}</td>";
                                echo "<td>{$rows['fullname']}</td>";
                                echo "<td>{$rows['username']}</td>";
                                echo "<td>" . str_repeat('*', strlen($rows['password'])) . "</td>";
                                echo "<td>{$rows['email']}</td>";
                                echo "<td>{$rows['role']}</td>";
                                echo "<td>
                                        <a href='viewusers.php?id={$rows['id']}'><button class='view-btn'>View</button></a>
                                        <a href='edituser.php?id={$rows['id']}'><button class='edit-btn'>Edit</button></a>
                                        <a href='deleteuser.php?id={$rows['id']}' onclick=\"return confirm('Are you sure?')\">
                                            <button class='delete-btn'>Delete</button>
                                        </a>
                                      </td>";
                                echo "</tr>";
                                $id++;
                            }
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
