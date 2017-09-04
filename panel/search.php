<?php
include_once('../includes/core.php');
if ($_SESSION['level'] > 0) {
    fetchFiles(null, null, null);
} else {
    fetchFiles(null, null, null);
}
