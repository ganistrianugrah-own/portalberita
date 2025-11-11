<?php
session_start();
session_destroy();
header("Location: login.php?msg=success&text=Berhasil logout!");
exit;
