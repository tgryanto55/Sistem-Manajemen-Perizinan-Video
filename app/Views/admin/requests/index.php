<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Access Requests<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Access Requests</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-500">Approve or reject video access for customers.</p>
        </div>



        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" 
             x-data="{ 
                pausePolling: false,
                editingCount: 0,
                init() {
                    setInterval(() => {
                        if (!this.pausePolling && this.editingCount === 0) {
                            $ajax('<?= current_url() ?>', { target: 'requests-table-body' });
                        }
                    }, 5000);
                }
             }"
             @focusin="pausePolling = true"
             @focusout="pausePolling = false"
             @edit-started="editingCount++"
             @edit-stopped="editingCount--">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Request Details</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Requested At</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Status</th>
                            <th scope="col" class="px-6 py-4 text-right pr-14 lg:pr-24 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="requests-table-body" class="bg-white divide-y divide-gray-100">
                        <?= $this->include('admin/requests/_rows') ?>
                    </tbody>
                </table>
            </div>
            <?php if (empty($requests)): ?>
                <div class="text-center py-12">
                    <p class="text-gray-500 text-sm">No access requests found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
