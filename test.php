
<?php
include_once 'login/db_connect.php';
include_once 'login/functions.php';
sec_session_start();
?>


<html>
<head>
  <title>Test Page</title>

   <script src="jquery.js"></script>
</head>
 <?php if (login_check($mysqli) == true) : ?>
            <p>Welcome <?php echo htmlentities($_SESSION['username']); ?>!</p>
<body onload="getUsers(); view();">

<p><a href="login/logout.php">Log out</a>.</p>


<h1>Test Page</h1>




<?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="login.php">login</a>.
            </p>
        <?php endif; ?>
</body>
</html>
