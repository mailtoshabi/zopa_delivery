<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

    <head>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title> <?php echo $__env->yieldContent('title'); ?> | ZOPA - Food Drop</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">





        <!-- Bootstrap Css -->
        <link href="<?php echo e(URL::asset('assets/css/bootstrap.min.css')); ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="<?php echo e(URL::asset('assets/css/app.min.css')); ?>" id="app-style" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(URL::asset('assets/css/global.css')); ?>" id="app-style" rel="stylesheet" type="text/css" />
  </head>

    <?php echo $__env->yieldContent('content'); ?>
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\kitchen\layouts\invoice.blade.php ENDPATH**/ ?>