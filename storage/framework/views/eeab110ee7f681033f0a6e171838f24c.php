<?php $__env->startSection('title', 'സൈറ്റ് മാപ്പ് - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-4">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            സൈറ്റ് മാപ്പ്
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>
    <div class="row">
        <div class="col-md-12 p-4 lh-lg">

            <ul class="list-group list-group-flush">

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-house"></i> ഡാഷ്‌ബോർഡ്</h5>
                    <small>നിങ്ങളുടെ മീൽ വാലറ്റ്, ഓർഡറുകൾ, ഷോർട്ട്കട്ടുകളുടെ അവലോകനം.</small>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-bowl-food"></i> Zopa Meals</h5>
                    <ul>
                        <li><strong>പ്ലാൻ വാങ്ങുക:</strong> മുൻകൂർ പ്ലാനുകൾ കാണുക, വാലറ്റ് അല്ലെങ്കിൽ COD വഴി പേയ്‌മെന്റ് ചെയ്യുക.</li>
                        <li><strong>ഒറ്റ ഓർഡർ:</strong> ഒറ്റദിവസം ഭക്ഷണം തിരഞ്ഞെടുക്കുക, നേരിട്ട് പേയ്‌മെന്റ് ചെയ്യുക.</li>
                    </ul>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-calendar-day"></i> ഡെയ്‌ലി ഓർഡറുകൾ</h5>
                    <ul>
                        <li><strong>ഇന്നത്തെ ഓർഡർ:</strong> വാലറ്റിൽ നിന്നോ അധികമായി അഭ്യർത്ഥിച്ചോ വിഭജിക്കപ്പെട്ട ഭക്ഷണം.</li>
                        <li><strong>വരാനിരിക്കുന്ന ഓർഡറുകൾ:</strong> ഭാവിയിലെ മീൽ നീക്കം.</li>
                        <li><strong>ഓർഡർ ഹിസ്റ്ററി:</strong> മുമ്പത്തെ ഭക്ഷണ രേഖകളും അളവുകളും.</li>
                    </ul>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-utensils"></i> എന്റെ മീൽസ്</h5>
                    <ul>
                        <li><strong>അധിക ഭക്ഷണം അഭ്യർത്ഥിക്കുക:</strong> വാലറ്റിൽ നിന്ന് കുറച്ച് ഇന്ന് ഭക്ഷണം ചേർക്കുക.</li>
                    </ul>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-calendar-xmark"></i> എന്റെ ലീവ്‌സ്</h5>
                    <ul>
                        <li><strong>ലീവ് അടയാളപ്പെടുത്തുക:</strong> നിങ്ങൾക്ക് ആ ദിവസം ഭക്ഷണം വേണ്ടെന്ന് സൂചിപ്പിക്കുക.</li>
                        <li><strong>പരമാവധി ദിവസം:</strong> <?php echo e(Utility::MAX_LEAVE_DAYS_AHEAD); ?> ദിവസം മുന്നേ വരെ.</li>
                        <li><strong>കട്ട്ഓഫ് സമയം:</strong> മാറ്റങ്ങൾ അനുവദിക്കപ്പെടുന്നത് <?php echo e($lastOrderTime); ?> വരെ മാത്രം.</li>
                    </ul>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-wallet"></i> എന്റെ വാങ്ങലുകൾ</h5>
                    <ul>
                        <li><strong>മീൽ പ്ലാനുകളും വാലറ്റും:</strong> റീചാർജ്, പേയ്‌മെന്റ്, ഉപയോഗം കാണുക.</li>
                    </ul>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-user"></i> എന്റെ പ്രൊഫൈൽ</h5>
                    <ul>
                        <li><strong>പേഴ്സണൽ ഇൻഫോ:</strong> പേര്, ഫോൺ, പദവി, ഓഫീസ് വിവരങ്ങൾ എഡിറ്റ് ചെയ്യുക.</li>
                        <li><strong>ലൊക്കേഷൻ:</strong> സ്റ്റേറ്റ്, ജില്ല, പിൻകോഡ് അപ്ഡേറ്റ് ചെയ്യുക.</li>
                    </ul>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-gears"></i> സെറ്റിംഗ്സ്</h5>
                    <ul>
                        <li><strong>പാസ്‌വേഡ്:</strong> ലോഗിൻ പാസ്‌വേഡ് മാറ്റുക (എനേബിള്‍ ചെയ്തിട്ടുണ്ടെങ്കില്‍ മാത്രം).</li>
                        <li><strong>ലോഗ്‌ഔട്ട്:</strong> സുരക്ഷിതമായി സെഷൻ അവസാനിപ്പിക്കുക.</li>
                    </ul>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-comment-dots"></i> ഫീഡ്ബാക്ക്</h5>
                    <small>നിങ്ങളുടെ അഭിപ്രായങ്ങൾ, നിർദേശങ്ങൾ, സംശയങ്ങൾ അയക്കുക.</small>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-book-open"></i> സഹായം</h5>
                    <ul>
                        <li><a href="<?php echo e(route('how_to_use')); ?>">ഉപയോഗിക്കുന്ന വിധം</a></li>
                        <li><a href="<?php echo e(route('site_map')); ?>">സപ്പോർട്ട്</a></li>
                        <li><a href="<?php echo e(route('support')); ?>">സൈറ്റ് മാപ്പ്</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\site_map_ml.blade.php ENDPATH**/ ?>