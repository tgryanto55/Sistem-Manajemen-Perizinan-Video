<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Access Requests<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Access Requests</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-500">Approve or reject video access for customers.</p>
        </div>

        <?php if (session()->has('success')): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center" role="alert">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="block sm:inline"><?= session('success') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative flex items-center" role="alert">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <span class="block sm:inline"><?= session('error') ?></span>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" 
             x-data="{ 
                pausePolling: false,
                editingCount: 0,
                init() {
                    setInterval(() => {
                        if (!this.pausePolling && this.editingCount === 0) {
                            htmx.trigger('#requests-table-body', 'refresh-rows');
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
                    <tbody id="requests-table-body" class="bg-white divide-y divide-gray-100" 
                           hx-get="<?= base_url('admin/requests/rows') ?>" 
                           hx-trigger="refresh-rows">
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
