<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>My Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <header class="mb-8">
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Welcome, <?= esc(session('user_name')) ?>!</h1>
        <p class="mt-1 text-xs sm:text-sm text-gray-500">Track your video access permissions and discover new content.</p>
    </header>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-8">
        <!-- Accessible Videos -->
        <div class="bg-white p-4 lg:p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col relative overflow-hidden">
             <div class="absolute top-0 right-0 -mt-2 -mr-2 w-20 h-20 bg-green-100 rounded-full opacity-50"></div>
             <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Accessible Now</h3>
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="text-2xl lg:text-3xl font-bold text-gray-900"><?= $accessibleVideos ?></div>
                <div class="mt-auto pt-1 lg:pt-2 text-[10px] lg:text-xs text-green-600 font-medium whitespace-nowrap">Ready to watch</div>
             </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white p-4 lg:p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col relative overflow-hidden">
             <div class="absolute top-0 right-0 -mt-2 -mr-2 w-20 h-20 bg-yellow-100 rounded-full opacity-50"></div>
             <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Pending Access</h3>
                    <div class="p-2 bg-yellow-50 rounded-lg text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="text-2xl lg:text-3xl font-bold text-gray-900"><?= $pendingRequests ?></div>
                <div class="mt-auto pt-1 lg:pt-2 text-[10px] lg:text-xs text-yellow-600 font-medium whitespace-nowrap">Awaiting approval</div>
             </div>
        </div>

        <!-- Total Library -->
        <div class="bg-white p-4 lg:p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col relative overflow-hidden">
             <div class="absolute top-0 right-0 -mt-2 -mr-2 w-20 h-20 bg-indigo-100 rounded-full opacity-50"></div>
             <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Library Size</h3>
                    <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                </div>
                <div class="text-2xl lg:text-3xl font-bold text-gray-900"><?= $totalVideos ?></div>
                <div class="mt-auto pt-1 lg:pt-2 text-[10px] lg:text-xs text-indigo-600 font-medium whitespace-nowrap">Videos available</div>
             </div>
        </div>
    </div>

    <!-- Featured Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- New Arrivals -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                New Arrivals
            </h3>
            <div class="space-y-4">
                <?php foreach($recentVideos as $video): ?>
                <div class="flex items-center gap-3 lg:gap-4 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="w-12 h-10 lg:w-16 lg:h-12 bg-gray-200 rounded-lg flex-shrink-0 flex items-center justify-center text-gray-400">
                         <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900 truncate"><?= esc($video['title']) ?></h4>
                        <p class="text-xs text-gray-500 truncate"><?= esc($video['description']) ?></p>
                    </div>
                    <div class="flex-shrink-0">
                         <a href="<?= base_url('customer/videos/watch/' . $video['id']) ?>" class="text-[10px] lg:text-xs font-medium text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2 lg:px-3 py-1 lg:py-1.5 rounded-lg border border-indigo-100">
                             Watch
                         </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-4 text-center">
                 <a href="<?= base_url('customer/videos') ?>" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Browse Full Library &rarr;</a>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="bg-gray-900 rounded-2xl shadow-sm p-6 text-white text-center flex flex-col justify-center relative overflow-hidden min-h-[200px]">
             <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-indigo-900/50 to-purple-900/50 z-0"></div>
             <div class="relative z-10">
                 <h3 class="text-xl font-bold mb-2">Want to handle more?</h3>
                 <p class="text-sm text-gray-300 mb-6">Explore our full catalog and request access to premium content.</p>
                 <a href="<?= base_url('customer/videos') ?>" class="inline-block w-full py-3 px-4 bg-white text-gray-900 font-bold rounded-xl hover:bg-gray-100 transition-colors">
                     Browse Videos
                 </a>
             </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
