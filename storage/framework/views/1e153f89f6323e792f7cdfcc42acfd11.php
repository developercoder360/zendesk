<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="antialiased" data-base="zinc" data-theme="default" data-radius="0.5">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e($title ?? 'Zendesk Marketing'); ?></title>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($meta_description)): ?>
        <meta name="description" content="<?php echo e($meta_description); ?>">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <!-- Dark Mode Script -->
    <script>
        (function () {
            const get = (k, d) => localStorage.getItem('theme:' + k) || d;
            const mode = get('mode', 'system');
            const dark = mode === 'dark' || (mode === 'system' && matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
        })();
    </script>
</head>
<body x-data class="min-h-screen bg-background text-foreground font-sans selection:bg-primary selection:text-primary-foreground">
    <!-- Navbar -->
    <?php if (isset($component)) { $__componentOriginalfaf0d0b2c30d36449abfd55298640522 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfaf0d0b2c30d36449abfd55298640522 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.marketing.navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('marketing.navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfaf0d0b2c30d36449abfd55298640522)): ?>
<?php $attributes = $__attributesOriginalfaf0d0b2c30d36449abfd55298640522; ?>
<?php unset($__attributesOriginalfaf0d0b2c30d36449abfd55298640522); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfaf0d0b2c30d36449abfd55298640522)): ?>
<?php $component = $__componentOriginalfaf0d0b2c30d36449abfd55298640522; ?>
<?php unset($__componentOriginalfaf0d0b2c30d36449abfd55298640522); ?>
<?php endif; ?>

    <!-- Main Content -->
    <main class="flex-1">
        <?php echo e($slot); ?>

    </main>

    <!-- Footer -->
    <?php if (isset($component)) { $__componentOriginal211506c1e29c7ecbbfb81861f611e452 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal211506c1e29c7ecbbfb81861f611e452 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.marketing.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('marketing.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal211506c1e29c7ecbbfb81861f611e452)): ?>
<?php $attributes = $__attributesOriginal211506c1e29c7ecbbfb81861f611e452; ?>
<?php unset($__attributesOriginal211506c1e29c7ecbbfb81861f611e452); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal211506c1e29c7ecbbfb81861f611e452)): ?>
<?php $component = $__componentOriginal211506c1e29c7ecbbfb81861f611e452; ?>
<?php unset($__componentOriginal211506c1e29c7ecbbfb81861f611e452); ?>
<?php endif; ?>
</body>
</html>
<?php /**PATH C:\laragon\www\zendesk\resources\views/layouts/marketing.blade.php ENDPATH**/ ?>