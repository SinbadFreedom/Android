<?php
session_start();

if (isset($_SESSION['figure_url'])) {
    echo $_SESSION['figure_url'];
    return;
} else {
    return;
}
