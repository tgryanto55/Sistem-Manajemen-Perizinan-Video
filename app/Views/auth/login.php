<?= $this->extend('layouts/guest') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="flex min-h-screen w-full">
    <!-- Left Side: Dark Login Form (Matches Dashboard Sidebar) -->
    <div class="w-full md:w-1/3 bg-gray-900 text-white flex flex-col justify-center px-12 relative">
        <!-- Logo Area -->
        <div class="absolute top-8 left-8 flex items-center">
            <svg class="w-8 h-8 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold text-xl tracking-wider">VideoApp</span>
        </div>

        <div class="max-w-md w-full mx-auto">
            <h2 class="text-3xl font-bold mb-2">Welcome Back</h2>
            <p class="text-gray-400 mb-8 text-sm">Sign in to access your dashboard.</p>

            <?php if (session()->has('error')): ?>
                <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 text-red-400 text-sm rounded-md">
                    <?= session('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->has('user_id')): ?>
                <div class="mb-6 p-4 bg-indigo-500/10 border border-indigo-500/20 rounded-xl flex items-start space-x-3">
                    <div class="flex-shrink-0 mt-0.5">
                        <svg class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-indigo-300">Logged in as <?= esc(session('user_name')) ?></p>
                        <p class="text-xs text-gray-500 mt-1">Role: <span class="capitalize"><?= esc(session('user_role')) ?></span>. Logging in below will switch your session.</p>
                    </div>
                </div>
            <?php endif; ?>

            <form class="space-y-5" action="<?= base_url('/login') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="space-y-1">
                    <label for="email" class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="appearance-none block w-full px-4 py-3 border border-gray-700 bg-gray-800 text-white placeholder-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200" 
                           placeholder="name@company.com" value="<?= old('email') ?>">
                </div>
                
                <div class="space-y-1">
                    <label for="password" class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                           class="appearance-none block w-full px-4 py-3 border border-gray-700 bg-gray-800 text-white placeholder-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200" 
                           placeholder="••••••••">
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-lg shadow-indigo-500/30">
                        Sign In
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-800">
                <div class="text-xs text-gray-500 flex justify-between">
                    <div>
                        <span class="block mb-1 text-gray-400 font-semibold">Admin Demo</span>
                        <span>admin@example.com</span>
                    </div>
                    <div>
                        <span class="block mb-1 text-gray-400 font-semibold">Customer Demo</span>
                        <span>customer@example.com</span>
                    </div>
                </div>
                 <div class="mt-2 text-xs text-gray-600 text-center">Password: password</div>
            </div>
        </div>
    </div>

    <!-- Right Side: Image Placeholder -->
    <div class="hidden md:block md:w-2/3 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>
        <div class="absolute bottom-12 left-12 text-white max-w-lg">
            <h1 class="text-4xl font-bold mb-4">Secure Video Management</h1>
            <p class="text-lg text-gray-200">Efficiently manage video permissions and access for your customers with our comprehensive platform.</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
