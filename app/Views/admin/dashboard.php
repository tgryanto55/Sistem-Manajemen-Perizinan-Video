<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Admin Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="py-4 lg:py-6">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-4 lg:mb-6">
            <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 tracking-tight">Admin Dashboard</h1>
            <p class="mt-0.5 text-[10px] sm:text-xs text-gray-500">Welcome back! Here's what's happening today.</p>
        </div>
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
            <!-- Total Customers -->
            <div class="bg-white p-3 lg:p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col">
                <div class="flex items-center justify-between mb-2 lg:mb-3">
                    <h3 class="text-[9px] lg:text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Customers</h3>
                    <div class="p-1 lg:p-1.5 bg-indigo-50 rounded-lg text-indigo-600">
                        <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <div class="text-xl lg:text-2xl font-bold text-gray-900"><?= $totalCustomers ?></div>
                <div class="mt-auto pt-2 lg:pt-3 text-[9px] lg:text-[10px] text-green-600 font-medium flex items-center">
                    <svg class="w-2.5 h-2.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    <span>Active Users</span>
                </div>
            </div>

            <!-- Total Videos -->
            <div class="bg-white p-3 lg:p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col">
                 <div class="flex items-center justify-between mb-2 lg:mb-3">
                    <h3 class="text-[9px] lg:text-xs font-semibold text-gray-400 uppercase tracking-wider">Video Assets</h3>
                    <div class="p-1 lg:p-1.5 bg-pink-50 rounded-lg text-pink-600">
                        <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.818v6.364a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="text-xl lg:text-2xl font-bold text-gray-900"><?= $totalVideos ?></div>
                <div class="mt-auto pt-2 lg:pt-3 text-[9px] lg:text-[10px] text-gray-400">Total content</div>
            </div>

            <!-- Pending Requests -->
            <div class="bg-white p-3 lg:p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col">
                 <div class="flex items-center justify-between mb-2 lg:mb-3">
                    <h3 class="text-[9px] lg:text-xs font-semibold text-gray-400 uppercase tracking-wider">Pending Access</h3>
                    <div class="p-1 lg:p-1.5 bg-yellow-50 rounded-lg text-yellow-600">
                        <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="text-xl lg:text-2xl font-bold text-gray-900"><?= $pendingRequests ?></div>
                 <div class="mt-auto pt-2 lg:pt-3 text-[9px] lg:text-[10px] text-yellow-600 font-medium truncate">Requires attention</div>
            </div>

            <!-- Total Requests -->
            <div class="bg-white p-3 lg:p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col">
                 <div class="flex items-center justify-between mb-2 lg:mb-3">
                    <h3 class="text-[9px] lg:text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Requests</h3>
                    <div class="p-1 lg:p-1.5 bg-blue-50 rounded-lg text-blue-600">
                        <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                </div>
                <div class="text-xl lg:text-2xl font-bold text-gray-900"><?= $totalRequests ?></div>
                <div class="mt-auto pt-2 lg:pt-3 text-[9px] lg:text-[10px] text-gray-400">All time</div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Quick Actions
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Add Video -->
                    <a href="<?= base_url('admin/videos?create=1') ?>" class="flex items-start p-5 bg-white hover:bg-indigo-50/50 rounded-2xl border border-gray-100 hover:border-indigo-100 transition-all group shadow-sm hover:shadow-md">
                        <div class="p-4 bg-indigo-50 rounded-xl text-indigo-600 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.818v6.364a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="ml-5">
                            <h4 class="text-base font-bold text-gray-900 group-hover:text-indigo-900 transition-colors">Add New Video</h4>
                            <p class="mt-1 text-sm text-gray-500 leading-relaxed">Upload a direct file or embed from external URLs like YouTube.</p>
                        </div>
                    </a>

                    <!-- Add Customer -->
                    <a href="<?= base_url('admin/customers?create=1') ?>" class="flex items-start p-5 bg-white hover:bg-green-50/50 rounded-2xl border border-gray-100 hover:border-green-100 transition-all group shadow-sm hover:shadow-md">
                        <div class="p-4 bg-green-50 rounded-xl text-green-600 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </div>
                        <div class="ml-5">
                            <h4 class="text-base font-bold text-gray-900 group-hover:text-green-900 transition-colors">Add New Customer</h4>
                            <p class="mt-1 text-sm text-gray-500 leading-relaxed">Register a new user to the platform and grant initial access.</p>
                        </div>
                    </a>
                </div>
            </div>
            

        </div>
    </div>
</div>
<?= $this->endSection() ?>
