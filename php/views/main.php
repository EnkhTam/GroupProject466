<!--Couldn't figure out what would be cool to go here, so just redirects to user's
    info page-->
<!DOCTYPE html>
<html>
<head>
<title>A useless page</title>
<link rel= "stylesheet" type = "text/css" href = "../../css/base/custom.css">
</head>
<body>
<?php include('../includes/header.php') ?>
<script>
window.onload = function() {
    // similar behavior as clicking on a link, redirect to userinfo.php
    window.location.href = "userinfo.php";
}
</script>
</body>
</html>
