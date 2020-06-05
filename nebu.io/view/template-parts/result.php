<div id="result" class="w3-row-padding w3-light-grey w3-padding-64 w3-container">
    <div class="w3-content">
        <div class="w3-third w3-center">
            <i class="fa fa-exclamation-circle w3-padding-64 w3-text-red w3-margin-right"></i>
        </div>
        <div class="w3-twothird">
            <h1>Result</h1>
            <?php
                require_once 'message.php';
            ?>
            <h5 class="w3-padding-32"><?php echo $_SESSION['result']; ?></h5>
            <p class="w3-text-grey">This is the calculated size any number of 1s that are either horizontally or vertically adjacent (but not diagonally adjacent) in your array.
                The number of adjacent 1s forming a group determine its size.
                I wrote an algorithm that returns an array of the sizes of all groups (including groups of one) represented in the input matrix.
                Please note that sizes don't need to be in any particular order.</p>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input
                    type="submit"
                    name="newTry"
                    value="Try again!"
                    class="w3-button w3-black w3-padding-large w3-large w3-margin-top"
                >
            </form>
        </div>
    </div>
</div>
