<?php
include "includes/header.php";
?>

<h2>Checkout</h2>

<p style="margin-top: 15px;">
    Review your order information before completing your purchase.
</p>

<form style="margin-top: 20px; max-width: 500px;">

    <label for="name"><strong>Full Name:</strong></label><br>
    <input type="text" id="name" name="name"
        style="width:100%; padding:10px; margin:8px 0 15px 0;"><br>

    <label for="email"><strong>Email Address:</strong></label><br>
    <input type="email" id="email" name="email"
        style="width:100%; padding:10px; margin:8px 0 15px 0;"><br>

    <label for="address"><strong>Shipping Address:</strong></label><br>
    <textarea id="address" name="address"
        style="width:100%; padding:10px; margin:8px 0 15px 0;"></textarea><br>

    <button type="submit"
        style="padding:10px 20px; background-color:#f4dce5; border:none; cursor:pointer;">
        Place Order
    </button>

</form>

<?php
include "includes/footer.php";
?>
