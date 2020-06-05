<?php
    $placeholder =  "[1, 0, 0, 1, 0],\n" .
                    "[1, 0, 1, 0, 0],\n" .
                    "[0, 0, 1, 0, 1],\n" .
                    "[1, 0, 1, 0, 1],\n" .
                    "[1, 0, 1, 1, 0]";
?>
<div id="input" class="w3-row-padding w3-padding-64 w3-container">
    <div class="w3-content">
        <div class="w3-twothird">
            <h1>Input</h1>
            <?php
                if(isset($_SESSION['message'])){
            ?>
            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <strong>Error!</strong> <?php echo $_SESSION['message']; ?>
            </div>
            <?php
                }
            ?>
            <h5 class="w3-padding-32">Give me a two-dimensional array of potentially unequal height and width containing only 0s and 1s than click "Submit" button!</h5>
            <p>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <label for="array">Your array:</label>
                    <br>
                    <textarea
                    id="array"
                    name="array"
                    cols="55"
                    rows="5"
                    placeholder="<?php echo $placeholder; ?>"
                    ><?php echo @$_SESSION['array']; ?></textarea>
                    <br>
                    <input type="submit" value="Submit" class="w3-button w3-black w3-padding-large w3-large w3-margin-top">
                </form>
            </p>
        </div>
        <div class="w3-third w3-text-grey w3-center">
            <i class="fa fa-question-circle w3-padding-64 w3-text-red"></i>
        </div>
    </div>
</div>
