<?php

$newTry = isset($_POST['newTry']);

if ($newTry) {
    unset($_SESSION['result']);
}

if (isset($_SESSION['result'])) {
    require_once "result.php";
} else {
    require_once "input.php";
}

if ($newTry) {
    ?>
        <script>
            window.location.hash = '#input';
        </script>
    <?php
}
