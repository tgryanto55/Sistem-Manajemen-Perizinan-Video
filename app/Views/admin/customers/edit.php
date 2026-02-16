<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Edit Customer<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Customer</h1>
            <p class="mt-1 text-sm text-gray-500">Update customer details.</p>
        </div>
        <a href="<?= base_url('admin/customers') ?>" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">
            &larr; Back to Customers
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= base_url('admin/customers/update/' . $customer['id']) ?>" method="POST" class="divide-y divide-gray-100">
            <?= csrf_field() ?>
            
            <div class="p-6 space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" id="name" value="<?= old('name', $customer['name']) ?>" 
                           class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" id="email" value="<?= old('email', $customer['email']) ?>" 
                           class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Password (Optional) -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        New Password <span class="text-gray-400 font-normal">(Leave blank to keep current)</span>
                    </label>
                    <input type="password" name="password" id="password" 
                           class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>

            <!-- Errors -->
            <?php if (session()->has('errors')): ?>
                <div class="px-6 py-4 bg-red-50">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Validation Error</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <?php foreach (session('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="px-6 py-4 bg-gray-50 flex items-center justify-end">
                <a href="<?= base_url('admin/customers') ?>" class="mr-4 text-sm font-medium text-gray-700 hover:text-gray-900">Cancel</a>
                <button type="submit" class="inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                    Update Customer
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
