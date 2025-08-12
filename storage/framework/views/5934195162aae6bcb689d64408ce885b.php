<!-- How to Use Modal -->
<div class="modal fade" id="howToUseModal" tabindex="-1" aria-labelledby="howToUseLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="howToUseLabel">How to Use <?php echo config('app.name'); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        
        <?php echo $__env->make('partials.how_to_use_content', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
      </div>
    </div>
  </div>
</div>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\partials\how_to_use_modal.blade.php ENDPATH**/ ?>