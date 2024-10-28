<?php
// Check for maintenance flag
if (file_exists('maintenance.flag1')) {
    header("Location: servermaintance.php");
    exit();
}
?>
