


<form method="POST" action="<?php echo e(route('sms.send')); ?>" >
    <?php echo csrf_field(); ?><br><br>
    <label for="mobile">Mobile Number without Country code</label>
    <br>
    <input id="mobile" name="mobile" placeholder="Mobile Number" value="">
<br><br>
    <label for="description">Content</label>
    <br>
    <textarea id="description" name="description" placeholder="Message"></textarea>
    <br><br>
    <button type="submit" >Send</button>
</form>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\sms.blade.php ENDPATH**/ ?>