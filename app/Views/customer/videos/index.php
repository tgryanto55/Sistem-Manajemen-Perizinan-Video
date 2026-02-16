<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Browse Videos<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <header class="mb-8">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Browse Videos</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-500">Explore our library and request access to premium content.</p>
        </div>
    </header>
        
    <?php if (empty($videos)): ?>
        <div class="text-center py-16 bg-white rounded-3xl border border-dashed border-gray-100">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.818v6.364a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No videos available</h3>
            <p class="mt-1 text-sm text-gray-500">Video library is currently empty.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-8" hx-get="<?= base_url('customer/videos/rows') ?>" hx-trigger="every 5s">
            <?= $this->include('customer/videos/_rows') ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
