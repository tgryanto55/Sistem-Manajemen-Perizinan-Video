<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?><?= (isset($title) ? $title . ' - ' : '') ?>Video Permission App</title>
    
    <!-- Tailwind CSS v4 -->
    <link href="<?= base_url('assets/css/app.css?v=' . time()) ?>" rel="stylesheet">
    
    <!-- Alpine.js Bundle -->
    <script src="<?= base_url('assets/js/bundle.js?v=' . time()) ?>" defer></script>
    
    <!-- Alpine AJAX CSRF Token -->
    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>" id="csrf-token">
    <script>
        document.addEventListener('ajax:expected', (event) => {
            const tokenElement = document.getElementById('csrf-token');
            if (tokenElement) {
                event.detail.headers['<?= config('Security')->headerName ?>'] = tokenElement.content;
            }
        });
    </script>
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased">
    
    <div class="flex h-screen overflow-hidden" x-data="{ isSidebarOpen: false }">
        
        <!-- Sidebar Backdrop (Mobile Only) -->
        <div x-show="isSidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="isSidebarOpen = false"
             class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden" 
             x-cloak></div>

        <!-- Sidebar -->
        <?php if (session()->has('user_id')): ?>
        <aside :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               x-data="{ currentPath: window.location.pathname }"
               @popstate.window="currentPath = window.location.pathname"
               @ajax:success.window="setTimeout(() => currentPath = window.location.pathname, 50)"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white flex flex-col flex-shrink-0 transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0">
            <!-- Logo -->
            <div class="h-16 flex items-center justify-between px-6 bg-gray-800">
                <a href="<?= base_url('/') ?>" class="font-bold text-xl text-white tracking-wider flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    VideoApp
                </a>
                <button @click="isSidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto py-4">
                <nav class="space-y-1 px-3" @click="if (window.innerWidth < 1024) isSidebarOpen = false">
                    <?php if (session('user_role') === 'admin'): ?>
                        <div class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Administration
                        </div>
                        <a x-target.push="main-content" href="<?= base_url('admin/dashboard') ?>" :class="currentPath === '/admin/dashboard' ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                            <span class="truncate">Dashboard</span>
                        </a>
                        <a x-target.push="main-content" href="<?= base_url('admin/customers') ?>" :class="currentPath.startsWith('/admin/customers') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                            <span class="truncate">Customers</span>
                        </a>
                        <a x-target.push="main-content" href="<?= base_url('admin/videos') ?>" :class="currentPath.startsWith('/admin/videos') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                            <span class="truncate">Videos</span>
                        </a>
                         <a x-target.push="main-content" href="<?= base_url('admin/requests') ?>" :class="currentPath.startsWith('/admin/requests') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                            <span class="truncate">Access Requests</span>
                        </a>
                    <?php else: ?>
                        <div class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Menu
                        </div>
                        <a x-target.push="main-content" href="<?= base_url('customer/dashboard') ?>" :class="currentPath === '/customer/dashboard' ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                            <span class="truncate">Dashboard</span>
                        </a>
                        <a x-target.push="main-content" href="<?= base_url('customer/videos') ?>" :class="currentPath.startsWith('/customer/videos') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                            <span class="truncate">Browse Videos</span>
                        </a>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- User Profile & Logout -->
            <div class="bg-gray-800 p-4 border-t border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                         <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">
                             <?= strtoupper(substr(session('user_name'), 0, 1)) ?>
                         </div>
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <p class="text-sm font-medium text-white truncate"><?= esc(session('user_name')) ?></p>
                        <p class="text-xs text-gray-400 capitalize"><?= esc(session('user_role')) ?></p>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="<?= base_url('logout') ?>" class="block w-full px-4 py-2 text-sm text-center font-medium text-red-400 bg-gray-900 hover:bg-red-900/20 hover:text-red-300 rounded-md transition-colors">
                        Sign Out
                    </a>
                </div>
            </div>
        </aside>
        <?php endif; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white h-16 flex items-center justify-between px-4 lg:px-6 z-30 lg:hidden">
                 <div class="flex items-center">
                    <button @click="isSidebarOpen = true" class="p-2 -ml-2 text-gray-500 hover:text-gray-600 lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div class="font-bold text-xl text-gray-900 lg:hidden ml-2">VideoApp</div>
                    <div class="hidden lg:block text-sm font-medium text-gray-500">
                        <?= $this->renderSection('title') ?>
                    </div>
                 </div>
                 
                 <div class="flex items-center space-y-0 space-x-4">
                    <!-- Right side header content (notifications, etc) -->
                 </div>
            </header>
            
            <!-- Main Scrollable Area -->
            <main id="main-content" class="flex-1 overflow-y-auto bg-gray-100 p-4 lg:p-8 relative">
                <!-- CI4 Flashdata Toast Trigger -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div x-data x-init="$nextTick(() => { $dispatch('show-toast', {message: '<?= esc(session()->getFlashdata('success')) ?>', type: 'success'}) })"></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div x-data x-init="$nextTick(() => { $dispatch('show-toast', {message: '<?= esc(session()->getFlashdata('error')) ?>', type: 'error'}) })"></div>
                <?php endif; ?>
                <?= $this->renderSection('content') ?>
            </main>
        </div>

    </div>

    <!-- Global Toast Notifications -->
    <div x-data="{ 
            toasts: [],
            addToast(event) {
                const toast = {
                    id: Date.now(),
                    message: event.detail.message,
                    type: event.detail.type || 'info'
                };
                this.toasts.push(toast);
                setTimeout(() => {
                    this.removeToast(toast.id);
                }, 5000);
            },
            removeToast(id) {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }
         }"
         @show-toast.window="addToast($event)"
         class="fixed top-4 right-4 z-[9999] flex flex-col space-y-2 max-w-sm w-full">
        
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="true" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-8"
                 :class="{
                    'bg-green-600': toast.type === 'success',
                    'bg-red-600': toast.type === 'error',
                    'bg-blue-600': toast.type === 'info',
                    'bg-yellow-500': toast.type === 'warning'
                 }"
                 class="text-white px-4 py-3 rounded-xl shadow-lg flex items-center justify-between">
                <div class="flex items-center">
                    <template x-if="toast.type === 'success'">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </template>
                    <span x-text="toast.message" class="text-sm font-medium"></span>
                </div>
                <button @click="removeToast(toast.id)" class="ml-4 text-white/70 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </template>
    </div>

    </div>

</body>
</html>
