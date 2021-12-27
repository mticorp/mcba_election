<?php $__env->startSection('style'); ?>
    <style>
        @media  only screen and (min-width: 768px) {
            #app-body .card-tools {
                width: 75% !important;
            }
        }

    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container py-3 mb-5">
        <?php if(count($all_elections) > 0): ?>
            <div class="row justify-content-center mb-3">
                <?php $__currentLoopData = $all_elections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $election): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($election->status == 1): ?>
                        <div class="col-md-6 elections">
                            <div class="card mt-4" style="border: 3px solid #17a2b8;">
                                <div class="card-body" id="center-body">
                                    <div class="card-text py-3 ml-2 text-justify">
                                        <input type="hidden" name="election_id" value="<?php echo e($election->id); ?>">
                                        <div class="row">
                                            <div class="col-md-6 col-6">
                                                <p><i class="fas fa-list-alt"></i> Election Name</p>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <p>: <?php echo e($election->name); ?></p>
                                            </div>
                                        </div>
                                        <?php if($election->candidate_flag == 1): ?>
                                        <div class="row">
                                            <div class="col-md-6 col-6">
                                                <p><i class="fas fa-user-check"></i> Position</p>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <p>: <?php echo e($election->position); ?></p>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <div class="row text-justify">
                                            <div class="col-md-6 col-6">
                                                <p><i class="fas fa-info-circle"></i> Status</p>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <p>: <span class="label bg-info">Start</span></p>
                                            </div>
                                        </div>
                
                                        <div class="row text-justify">
                                            <div class="col-md-6 col-6">
                                                <p><i class="fas fa-calendar"></i> Start Time</p>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <p>: <?php echo e(str_replace(['after','before'], 'ago', Carbon\Carbon::parse($election->start_time)->diffForHumans(Carbon\Carbon::parse(Carbon\Carbon::now())->format('Y-m-d h:i:s')))); ?>

                                                </p>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                                <div class="card-footer text-center py-0 px-0">
                                    <div class="row justify-content-center">
                                        <div class="col-6 pr-0">
                                            <a href="<?php echo e(route('vote.voter.index', $election->id)); ?>"
                                                class="btn btn-info btn-flat btn-block py-2">Vote</a>
                                        </div>
                                        <div class="col-6 pl-0">
                                            <a href="<?php echo e(route('vote.result-page', $election->id)); ?>"
                                                class="btn btn-info btn-flat btn-block py-2">View Result</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-md-6 elections">                            
                            <?php if(Carbon\Carbon::parse($election->start_time) <= Carbon\Carbon::parse($election->end_time) && $election->end_time != "0000-00-00 00:00:00"): ?>
                            <div class="card mt-4">
                                <input type="hidden" name="duration_from" value="<?php echo e($election->duration_from); ?>">
                                <div class="card-body" id="center-body">
                                    <div class="card-text py-3 ml-2">
                                        <input type="hidden" name="election_id" value="<?php echo e($election->id); ?>">
                                        <div class="row text-justify">
                                            <div class="col-md-6 col-6">
                                                <p><i class="fas fa-list-alt"></i> Election Name</p>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <p>: <?php echo e($election->name); ?></p>
                                            </div>
                                        </div>

                                        <?php if($election->candidate_flag == 1): ?>
                                        <div class="row text-justify">
                                            <div class="col-md-6 col-6">
                                                <p><i class="fas fa-user-check"></i> Position</p>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <p>: <?php echo e($election->position); ?></p>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <div class="row">
                                            <div class="col-md-6 col-6 text-justify">
                                                <p><i class="fas fa-info-circle"></i> Status</p>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <p>: <span class="label bg-danger">Stop</span></p>
                                            </div>
                                        </div>

                                        <div class="row text-justify">                               
                                            <div class="col-md-6 col-6">
                                            <p> <i class="fas fa-calendar"></i> Stop at </p>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <p>: <?php echo e(str_replace(['after','before'], 'ago', Carbon\Carbon::parse($election->end_time)->diffForHumans(Carbon\Carbon::parse(Carbon\Carbon::now())->format('Y-m-d h:i:s')))); ?></p>
                                            </div>
                                        </div>                            
                                    </div>
                                </div>

                                <div class="card-footer text-center" style="padding: 0px 0px!important;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="<?php echo e(route('vote.result-page', $election->id)); ?>"
                                                style="text-decoration:none; color:white;" class="btn btn-info btn-block py-2">View
                                                Result</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php elseif($election->start_time == "0000-00-00 00:00:00" && $election->end_time == "0000-00-00 00:00:00"): ?>
                            <div class="card mt-4" style="background-color:rgb(220, 222, 222);">
                                <input type="hidden" name="duration_from" value="<?php echo e($election->duration_from); ?>">
                                <div class="card-body" id="center-body">
                                    <div class="card-text py-3 ml-2">
                                        <input type="hidden" name="election_id" value="<?php echo e($election->id); ?>">
                                        <div class="row text-justify">
                                            <div class="col-md-6 col-6">
                                                <p><i class="fas fa-list-alt"></i> Election Name</p>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <p>: <?php echo e($election->name); ?></p>
                                            </div>
                                        </div>

                                        <?php if($election->candidate_flag == 1): ?>
                                        <div class="row text-justify">
                                            <div class="col-md-6 col-6">
                                                <p><i class="fas fa-user-check"></i> Position</p>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <p>: <?php echo e($election->position); ?></p>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <div class="row text-justify">
                                            <div class="col-md-6 col-6">
                                                <p><i class="fas fa-info-circle"></i> Status</p>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <p>: Not Start</p>
                                            </div>
                                        </div>

                                        <div class="row text-justify">                               
                                            <div class="col-md-6 col-6">
                                                <p><i class="fas fa-hourglass"></i> Start Time </p>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <p>: <?php echo e(Carbon\Carbon::parse($election->duration_from)->format('d-m-Y g:i A')); ?></p>
                                            </div>
                                        </div>
                                        
                                        <div class="row text-justify">
                                            <div class="col-md-6 col-6">
                                                <p><i class="fas fa-hourglass"></i> End Time</p>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <p>: <?php echo e(Carbon\Carbon::parse($election->duration_to)->format('d-m-Y g:i A')); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-center" style="padding: 0px 0px!important;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-info btn-flat btn-block py-2" disabled>Comming Soon</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="row justify-content-center pb-5 my-3">
                <div class="col-sm-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-list-alt"></i></h3>
                            <div class="card-tools">

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-text text-center">
                                <h4 class="py-3">No Election Avaliable!</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/mcba_election/resources/views/voter/index.blade.php ENDPATH**/ ?>