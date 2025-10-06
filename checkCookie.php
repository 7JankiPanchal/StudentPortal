<?php
if (isset($_COOKIE["username"])) {
    echo "Cookie is set! Value: " . $_COOKIE["username"];
    header("Location: index.html");
    exit();
} else {
    echo "Cookie is NOT set.";
}
?>
