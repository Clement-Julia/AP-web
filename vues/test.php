<?php
require_once "header.php";
print_r($_POST)
?>
<form action="test.php" method = "post">
    <div class="rating">
        <input type="radio" name="rating" value="5" id="5">
        <label for="5">☆</label>

        <input type="radio" name="rating" value="4" id="4">
        <label for="4">☆</label>

        <input type="radio" name="rating" value="3" id="3">
        <label for="3">☆</label>

        <input type="radio" name="rating" value="2" id="2">
        <label for="2">☆</label>
        
        <input type="radio" name="rating" value="1" id="1" checked>
        <label for="1">☆</label>
    </div>
    <button type="submit" class="btn">Submit</button>
</form>
<?php
require_once "footer.php";
?>