<?php $request = app('Illuminate\Http\Request'); ?>
<nav class="navbar">
    <div class="text-white text-center w-100">
        <?php if((request()->is('/') ? 'active' : '') || ($request->segment(2) == 'register' ? 'active' : '') || ($request->segment(1) == 'select-Election' ? 'active' : '') || ($request->segment(1) == 'app' ? 'active' : '') || ($request->segment(1) == 'verify' ? 'active' : '')): ?>
            <img src="<?php echo e(url('images/election.logo.jpg')); ?>" alt="" class="header-img">
        <?php else: ?>
            <?php if($election->company_logo): ?>
                <img src="<?php echo e(url($election->company_logo)); ?>" alt="" class="header-img">
            <?php else: ?>
                <img src="<?php echo e(url('images/election.logo.jpg')); ?>" alt="" class="header-img">
            <?php endif; ?>
        <?php endif; ?>
    </div>
</nav>
<?php /**PATH /var/www/mcba_election/resources/views/partials/header.blade.php ENDPATH**/ ?>