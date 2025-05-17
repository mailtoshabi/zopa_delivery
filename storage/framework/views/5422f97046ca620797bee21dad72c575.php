<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Branch_List'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css')); ?>" rel="stylesheet" type="text/css" />

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('admin.dir_components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> <?php echo app('translator')->get('translation.Account_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('li_2'); ?> <?php echo app('translator')->get('translation.Branch_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> <?php echo app('translator')->get('translation.Branch_List'); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
<?php if(session()->has('success')): ?>
<div class="alert alert-success alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-success"></i><strong>Success</strong> - <?php echo e(session()->get('success')); ?>

</div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card mb-0">
            <div class="card-body">
                <!-- Nav tabs -->
                
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane branchdetailsTab active" role="tabpanel">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="mb-3">
                                <h5 class="card-title"><?php echo app('translator')->get('translation.Branch_List'); ?> <span class="text-muted fw-normal ms-2">(<?php echo e($branches->total()); ?>)</span></h5>
                                </div>
                            </div>

                            
                        </div>
                         <!-- end row -->

                        <div class="table-responsive mb-4">
                            <table class="table align-middle dt-responsive table-check nowrap" style="border-collapse: collapse; border-spacing: 0 8px; width: 100%;">
                                <thead>
                                <tr>
                                    
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Mobile</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">City</th>
                                    <th scope="col">Created</th>
                                    <th style="width: 80px; min-width: 80px;">View</th>
                                </tr>
                                </thead>
                                <tbody>
                                   <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                       <tr>
                                           
                                           <td><?php echo e($branch->id); ?></td>
                                           <td>
                                               <?php if(!empty($branch->image)): ?>
                                                   <img src="<?php echo e(URL::asset('storage/branches/' . $branch->image)); ?>" alt="" class="branch_logo_list">
                                               <?php else: ?>
                                               <div class="avatar-sm d-inline-block align-middle me-2">
                                                   <div class="avatar-title bg-soft-primary text-primary font-size-20 m-0 rounded-circle">
                                                       <i class="bx bxs-user-circle"></i>
                                                   </div>
                                               </div>
                                               <?php endif; ?>
                                               <a href="<?php echo e(route('admin.branches.edit',encrypt($branch->id))); ?>" class=""><?php echo e($branch->name); ?> <?php if($branch->id==default_branch()->id): ?><span class="badge badge-pill badge-soft-success font-size-12">Default</span><?php endif; ?></a>
                                            </td>

                                           <td><?php echo e($branch->phone); ?></td>
                                           <td><?php echo e($branch->email); ?></td>
                                           <td>
                                            <?php echo e($branch->city); ?>

                                            <br> <small><?php echo e($branch->district->name); ?> District</small>
                                           </td>
                                           <td>
                                            <a href="#" class="text-body">
                                                On <?php echo e($branch->created_at->format('d M Y')); ?>

                                            </a>
                                        </td>
                                           
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bx bx-dots-horizontal-rounded"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="<?php echo e(route('admin.branches.edit',encrypt($branch->id))); ?>"><i class="mdi mdi-pencil font-size-16 text-success me-1"></i> Edit</a></li>
                                                        
                                                        <?php if(!$branch->is_approved): ?>
                                                        <li><a href="#" class="dropdown-item" data-plugin="delete-data" data-target-form="#form_delete_<?php echo e($loop->iteration); ?>"><i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i> Delete</a></li>
                                                        <form id="form_delete_<?php echo e($loop->iteration); ?>" method="POST" action="<?php echo e(route('admin.branches.destroy',encrypt($branch->id))); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="_method" value="DELETE" />
                                                        </form>
                                                        <?php endif; ?>
                                                        <li><a class="dropdown-item" href="<?php echo e(route('admin.branches.changeStatus',encrypt($branch->id))); ?>"><?php echo $branch->status?'<i class="fas fa-power-off font-size-16 text-danger me-1"></i> Unpublish':'<i class="fas fa-circle-notch font-size-16 text-primary me-1"></i> Publish'; ?></a></li>
                                                        <li><a class="dropdown-item" href="<?php echo e(route('admin.branches.makeDefault',encrypt($branch->id))); ?>"><i class="mdi mdi-cursor-pointer font-size-16 text-success me-1"></i> Make Default</a></li>
                                                        <li><a class="dropdown-item" href="<?php echo e(route('admin.branches.view',encrypt($branch->id))); ?>"><i class="fa fa-eye font-size-16 text-success me-1"></i> Details</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                       </tr>
                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <!-- end table -->
                            <div class="pagination justify-content-center"><?php echo e($branches->links()); ?></div>
                        </div>
                         <!-- end table responsive -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('assets/libs/datatables.net/datatables.net.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/libs/datatables.net-responsive/datatables.net-responsive.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/js/pages/datatable-pages.init.js')); ?>"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\branches\index.blade.php ENDPATH**/ ?>