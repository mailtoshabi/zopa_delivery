<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Feedbacks'); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('admin.dir_components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> <?php echo app('translator')->get('translation.Customer_Manage'); ?> <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> <?php echo app('translator')->get('translation.Customer_Manage'); ?> <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> <?php echo app('translator')->get('translation.Feedbacks'); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php if(session()->has('success')): ?>
<div class="alert alert-success alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-success"></i><strong>Success</strong> - <?php echo e(session()->get('success')); ?>

</div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Feedback List <span class="text-muted fw-normal ms-2">(<?php echo e($feedbacks->total()); ?>)</span></h4>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Message</th>
                        <th>Reply</th>
                        <th>Public?</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $feedbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($index + $feedbacks->firstItem()); ?></td>
                        <td><?php echo e($feedback->customer->name); ?><br><small><?php echo e($feedback->customer->phone); ?></small></td>
                        <td><?php echo e($feedback->message); ?></td>
                        <td>
                            <?php if($feedback->reply): ?>
                                <strong class="text-success">Reply:</strong> <?php echo e($feedback->reply); ?><br>
                                <small class="text-muted">on <?php echo e($feedback->replied_at?->format('d M Y h:i A')); ?></small>
                            <?php endif; ?>

                            <form class="reply-form" data-id="<?php echo e($feedback->id); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="mb-2">
                                    <textarea name="reply" class="form-control" rows="2" placeholder="Type reply..."><?php echo e(old('reply', $feedback->reply)); ?></textarea>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" name="is_public" value="1" <?php echo e($feedback->is_public ? 'checked' : ''); ?>>
                                    <label class="form-check-label">Publicly visible?</label>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary">Save Reply</button>
                                <div class="reply-status text-success small mt-1" style="display: none;"></div>
                            </form>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo e($feedback->is_public ? 'success' : 'secondary'); ?>">
                                <?php echo e($feedback->is_public ? 'Yes' : 'No'); ?>

                            </span>
                        </td>
                        <td><?php echo e($feedback->created_at->format('d M Y')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <div class="pagination justify-content-center mt-3">
                <?php echo e($feedbacks->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.reply-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const feedbackId = this.dataset.id;
                const formData = new FormData(this);
                const csrfToken = formData.get('_token');

                fetch(`/admin/feedbacks/${feedbackId}/reply-ajax`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const statusDiv = this.querySelector('.reply-status');
                        statusDiv.textContent = 'Saved at ' + data.replied_at;
                        statusDiv.style.display = 'block';
                    }
                })
                .catch(err => {
                    alert('Error saving reply.');
                    console.error(err);
                });
            });
        });
    });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\feedbacks\index.blade.php ENDPATH**/ ?>