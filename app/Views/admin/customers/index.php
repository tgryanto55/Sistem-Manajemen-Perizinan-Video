<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Customers<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto" x-data="{ 
        showCreateModal: <?= session()->has('show_create_modal') ? 'true' : 'false' ?>,
        showEditModal: <?= session()->has('show_edit_modal') ? 'true' : 'false' ?>,
        editingCustomer: {
            id: '<?= session('edit_customer_id') ?>',
            name: '<?= old('name') ?>',
            email: '<?= old('email') ?>'
        },
        openEditModal(customer) {
            this.editingCustomer = { ...customer };
            this.showEditModal = true;
        }
    }" x-init="const urlParams = new URLSearchParams(window.location.search); if (urlParams.get('create')) showCreateModal = true"
       @customer-saved.window="showCreateModal = false; showEditModal = false">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Customer Management</h1>
                <p class="mt-1 text-xs sm:text-sm text-gray-500">Manage your customers and their video access.</p>
            </div>
            <button @click="showCreateModal = true" class="hidden sm:inline-flex items-center px-4 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add Customer
            </button>
        </div>

        <!-- Create Customer Modal -->
        <div x-show="showCreateModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showCreateModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                     class="fixed inset-0 bg-gray-500/75 transition-opacity" @click="showCreateModal = false" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div x-show="showCreateModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom sm:align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg w-full relative z-10">
                    
                    <form hx-post="<?= base_url('admin/customers') ?>" hx-target="#customer-table-body">
                        <?= csrf_field() ?>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Add New Customer</h3>
                                    <div class="mt-4 space-y-4 text-left">
                                        <div>
                                            <label for="name" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Full Name</label>
                                            <input type="text" name="name" id="name" value="<?= old('name') ?>" class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="John Doe">
                                        </div>
                                        <div>
                                            <label for="email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Email Address</label>
                                            <input type="email" name="email" id="email" value="<?= old('email') ?>" class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="john@example.com">
                                        </div>
                                        <div>
                                            <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Password</label>
                                            <input type="password" name="password" id="password" class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="••••••••">
                                        </div>
                                        
                                        <?php if (session()->has('errors') && !session()->has('show_edit_modal')): ?>
                                            <div class="rounded-xl bg-red-50 p-4 border border-red-100 mt-2">
                                                <div class="flex">
                                                    <div class="flex-shrink-0">
                                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-xs font-bold text-red-800 uppercase tracking-wider">Error Details</div>
                                                        <div class="mt-1 text-sm text-red-700">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-col sm:flex-row-reverse gap-3 sm:gap-0">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2.5 bg-indigo-600 text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto transition-all active:scale-95">
                                Create Customer
                            </button>
                            <button type="button" @click="showCreateModal = false" class="w-full inline-flex justify-center rounded-xl border border-gray-200 shadow-sm px-4 py-2.5 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto transition-all active:scale-95">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Customer Modal -->
        <div x-show="showEditModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                 <div x-show="showEditModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                     class="fixed inset-0 bg-gray-500/75 transition-opacity" @click="showEditModal = false" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div x-show="showEditModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom sm:align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg w-full relative z-10">
                    
                    <form hx-post="<?= base_url('admin/customers') ?>" hx-target="#customer-table-body">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" :value="editingCustomer.id">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Customer</h3>
                                    <div class="mt-4 space-y-4 text-left">
                                        <div>
                                            <label for="edit_name" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Full Name</label>
                                            <input type="text" name="name" id="edit_name" x-model="editingCustomer.name" class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                        </div>
                                        <div>
                                            <label for="edit_email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Email Address</label>
                                            <input type="email" name="email" id="edit_email" x-model="editingCustomer.email" class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                        </div>
                                        <div>
                                            <label for="edit_password" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">New Password <span class="text-gray-400 font-normal normal-case">(Optional)</span></label>
                                            <input type="password" name="password" id="edit_password" class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="••••••••">
                                        </div>
                                        
                                        <?php if (session()->has('errors') && session()->has('show_edit_modal')): ?>
                                            <div class="rounded-xl bg-red-50 p-4 border border-red-100 mt-2">
                                                <div class="flex">
                                                    <div class="flex-shrink-0">
                                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-xs font-bold text-red-800 uppercase tracking-wider">Error Details</div>
                                                        <div class="mt-1 text-sm text-red-700">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-col sm:flex-row-reverse gap-3 sm:gap-0">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2.5 bg-indigo-600 text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto transition-all active:scale-95">
                                Update Customer
                            </button>
                            <button type="button" @click="showEditModal = false" class="w-full inline-flex justify-center rounded-xl border border-gray-200 shadow-sm px-4 py-2.5 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto transition-all active:scale-95">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if (session()->has('success') && !request()->hasHeader('HX-Request')): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center" role="alert">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="block sm:inline"><?= session('success') ?></span>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 lg:px-6 py-3 lg:py-4 text-left text-[10px] lg:text-xs font-semibold text-gray-500 uppercase tracking-wider">User Info</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 lg:py-4 text-left text-[10px] lg:text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 lg:py-4 text-left text-[10px] lg:text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Status</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 lg:py-4 text-left text-[10px] lg:text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Joined</th>
                            <th scope="col" class="relative px-4 lg:px-6 py-3 lg:py-4"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody id="customer-table-body" class="bg-white divide-y divide-gray-100" hx-get="<?= base_url('admin/customers/rows') ?>" hx-trigger="every 5s">
                        <?= $this->include('admin/customers/_rows') ?>
                    </tbody>
                </table>
            </div>
            <?php if (empty($customers)): ?>
                <div class="text-center py-12">
                    <p class="text-gray-500 text-sm">No customers found.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Floating Action Button (FAB) - Mobile Only -->
        <button @click="showCreateModal = true" 
                :class="{ 'pointer-events-none blur-sm opacity-50': isSidebarOpen }"
                class="sm:hidden fixed bottom-8 right-8 w-14 h-14 bg-indigo-600 text-white rounded-full shadow-2xl flex items-center justify-center hover:bg-indigo-700 active:scale-95 transition-all z-40">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </button>
    </div>
</div>
<?= $this->endSection() ?>
