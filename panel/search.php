<?php
session_start();
include_once('../includes/core.php');
if ($_SESSION['level'] > 0) {
    fetchUserFiles($_SESSION['email']);
} else {
    fetchUserFiles($_SESSION['email']);
}
