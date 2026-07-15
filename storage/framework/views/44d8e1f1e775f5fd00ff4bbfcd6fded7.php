<div
    data-slot="accordion-content"
    role="region"
    :id="$id('blat-accordion-panel', _v)"
    :aria-labelledby="$id('blat-accordion-trigger', _v)"
    x-show="isOpen(_v)"
    x-collapse
    x-cloak
    :data-state="isOpen(_v) ? 'open' : 'closed'"
    class="overflow-hidden text-sm"
>
    <div <?php echo e($attributes->twMerge('pt-0 pb-4')); ?>>
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH C:\laragon\www\zendesk\resources\views/components/ui/accordion-content.blade.php ENDPATH**/ ?>