<?php
    session_start();
    session_destroy();
    header("Hx-redirect: ../Login");
?>