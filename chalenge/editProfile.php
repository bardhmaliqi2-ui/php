<?php
include_once('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <style>
        body{font-family: Arial;
             margin: 0; 
             background: #f5f5f5; }
        .header{background: #30343a;
                color: white; 
                padding: 20px; }
        .container{display: flex; 
                                }
        .sidebar{width: 200px;
                 min-height: 80px;
                 background: #eee; 
                 padding: 20px; }
        .sidebar a{display: block; 
                   padding: 12px;
                   text-decoration: none;
                   color: #333; }
        .content{padding: 30px; 
                                }
        input{display: block; 
              margin: 10px 0; 
              padding: 10px; 
              width: 250px; }
        button{padding: 10px 20px;
               background: #087df0;
               color: white;
               border: none;
               cursor: pointer; }
    </style>
</head>
<body>

<div class="header"><h3>Hello,</h3></div>

<div class="container">
    <div class="sidebar">
        <a href="dashboard.php">Dashboard</a>
        <a href="productDashboard.php">Product Dashboard</a>
        <a href="editProfile.php">Edit Profile</a>
    </div>

    <div class="content">
        <h1>Edit Profile</h1>

        <form method="POST" action="">
            <label>Name:</label>
            <input type="text" name="name" placeholder="Enter your name">

            <label>Email:</label>
            <input type="email" name="email" placeholder="Enter your email">

            <label>Password:</label>
            <input type="password" name="password" placeholder="Enter new password">

            <button type="submit">Save Changes</button>
        </form>
    </div>
</div>

</body>
</html>
