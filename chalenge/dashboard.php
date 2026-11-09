<?php
include_once('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body{ font-family: Arial;
              margin: 0;
              background: #f5f5f5; 
            }
        .header{background: #30343a;
                color: white;
                padding: 20px;}
        .container{display: flex;
                  }
        .sidebar{width: 200px; 
                 min-height: 80px; 
                 background: #eee;
                 padding: 20px;
                }
        .sidebar a{display: block;
                   padding: 12px; 
                   text-decoration: none; 
                   color: #333; }
        .content{padding: 30px;
                }
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
        <h1>Dashboard</h1>
        <p>Welcome to your Dashboard!</p>
    </div>
</div>

</body>
</html>
