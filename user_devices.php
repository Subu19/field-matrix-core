<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect unauthorized users to the login page
    header("location: /login.php");
    exit();
} else {
    require_once("./backend/database/connect.php");
    $sql = "SELECT * from device";
    $result = $mysql->query($sql);
    $userid = $_SESSION['user_id'];
    $sql2 = "SELECT * from userdevice inner join device on device.did = userdevice.did  where userid = '{$userid}';";
    $mydevices = $mysql->query($sql2);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="user_devices_styles.css">
</head>

<body>
    <div class="container">
        <!-- side bar -->
        <aside>
            <div class="top">
                <div class="logo">
                    <img src="images/logo.png">
                    <h2>FMC</h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">close</span>
                </div>
            </div>
            <div class="sidebar">
                <a href="user_panel.php">
                    <span class="material-icons-sharp">grid_view</span>
                    <h3 onclick="toggleDashboard()">Dashboard</h3>
                </a>
                <a href="user_messages.php">
                    <span class="material-icons-sharp">mail_outline</span>
                    <h3>Messages</h3>
                    <span class="message-count">26</span>
                </a>
                <a href="user_devices.php" class="active">
                    <span class="material-icons-sharp">inventory</span>
                    <h3 onclick="toggleDevice()">Devices</h3>
                </a>
                <a href="login.php">
                    <span class="material-icons-sharp">logout</span>
                    <h3>Log Out</h3>
                </a>
            </div>
        </aside>
        <!-- end of side bar -->
        <main>
            <h1>User Dashboard</h1>
            <button class="btn" onclick="togglePopup()"><span class="material-icons-sharp">add</span>Add Device</button>
            <div class="recent-devices">
                <h2>Recent Devices</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Device Name</th>
                            <th>MAC Address</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($mydevice = $mydevices->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $mydevice["name"] ?></td>
                                <td class="primary"><?php echo $mydevice["mac"] ?></td>
                                <td class="warning">Off</td>
                                <td class="actions">
                                    <form method="post" action="/backend/user/deletedevice.php">
                                        <input type="text" name="udid" value="<?php echo $mydevice["udid"] ?>" hidden />
                                        <button class="btn del">
                                            <span class="material-icons-sharp">
                                                close
                                            </span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="#">Show All</a>
            </div>
            <!--End of Analytics-->

        </main>
        <!--End of Main-->

        <div class="right">
            <div class="top">
                <button id="menu-btn">
                    <span class="material-icons-sharp">menu</span>
                </button>
                <div class="theme-toggler">
                    <span class="material-icons-sharp active">light_mode</span>
                    <span class="material-icons-sharp">dark_mode</span>
                </div>
                <div class="profile">
                    <div class="info">
                        <p>Hey, <b>User123</b></p>
                        <small class="text-muted">User</small>
                    </div>
                    <div class="profile-photo">
                        <img src="./images/profile-user.jpg">
                    </div>
                </div>
            </div>
            <!--End of Top-->
        </div>
    </div>

    <div class="popup" id="popup">
        <form id="popupForm" action="/backend/adddevice.php" method="post">
            <div class="select">
                <select class="input" name="did">
                    <?php while ($device = $result->fetch_assoc()) : ?>
                        <option class="option" value="<?php echo $device['did'] ?>"><?php echo $device['name'] ?></option>
                    <?php endwhile; ?>
                </select>
                <span class="material-icons-sharp icon">expand_more</span>
            </div>
            <input name="mac" class="input" type="text" placeholder="MAC Address">
            <button class="btn">Submit</button>


        </form>
    </div>

    <script src="./user_devices_scripts.js"></script>
</body>

</html>