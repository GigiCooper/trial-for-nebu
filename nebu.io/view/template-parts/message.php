<?php

if (isset($_SESSION['message'])) {
    ?>
    <div class="alert">
        <span
            class="closebtn"
            onclick="this.parentElement.style.display='none';"
        >&times;</span>
        <strong>Error!</strong> <?php echo $_SESSION['message']; ?>
    </div>
    <?php
}
