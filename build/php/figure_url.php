<?php
session_start();

if (isset($_SESSION['figure_url'])) {
    return $_SESSION['figure_url'];
} else {
    return null;
}
