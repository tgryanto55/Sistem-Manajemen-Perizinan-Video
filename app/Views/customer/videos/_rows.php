<?php foreach ($videos as $video): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden hover:shadow-md transition-shadow duration-300">
        <!-- Video Thumbnail / Interaction Area -->
        <div class="aspect-video bg-gray-900 relative group overflow-hidden">
            <!-- Thumbnail Image -->
            <?php if (!empty($video['thumbnail_url'])): ?>
                <img src="<?= esc($video['thumbnail_url']) ?>" 
                     alt="<?= esc($video['title']) ?>" 
                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-80 group-hover:opacity-100">
            <?php endif; ?>

            <!-- Interactivity Layer -->
            <?php if ($video['has_active_access']): ?>
                <a href="<?= base_url('customer/videos/watch/' . $video['id']) ?>" class="absolute inset-0 z-10 flex items-center justify-center">
                    <div class="w-12 h-12 lg:w-16 lg:h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white group-hover:bg-indigo-600/90 group-hover:scale-110 transition-all duration-300 shadow-xl">
                        <svg class="w-6 h-6 lg:w-8 lg:h-8 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                </a>
            <?php elseif ($video['access_status'] === 'pending'): ?>
                <div class="absolute inset-0 z-10 flex items-center justify-center cursor-not-allowed">
                    <div class="w-12 h-12 lg:w-16 lg:h-16 bg-gray-800/50 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-300 transition-all duration-300">
                        <svg class="w-6 h-6 lg:w-8 lg:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            <?php else: ?>
                <form action="<?= base_url('customer/videos/request/' . $video['id']) ?>" method="POST" x-target="main-content" class="absolute inset-0 z-10">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full h-full flex items-center justify-center group/btn">
                        <div class="w-12 h-12 lg:w-16 lg:h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white group-hover/btn:bg-white group-hover/btn:text-indigo-600 group-hover:scale-110 transition-all duration-300 shadow-xl">
                            <svg class="w-6 h-6 lg:w-8 lg:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                    </button>
                </form>
            <?php endif; ?>
            
            <!-- Status Badge -->
            <div class="absolute top-2 right-2 lg:top-4 lg:right-4 z-20">
                <?php if ($video['has_active_access']): ?>
                    <span class="px-2 lg:px-3 py-1 rounded-full text-[8px] lg:text-xs font-bold bg-green-500 text-white shadow-lg border border-white/20">
                        Accessible
                    </span>
                <?php elseif ($video['access_status'] === 'pending'): ?>
                    <span class="px-2 lg:px-3 py-1 rounded-full text-[8px] lg:text-xs font-bold bg-yellow-500 text-white shadow-lg border border-white/20">
                        Pending Approval
                    </span>
                <?php else: ?>
                    <span class="px-2 lg:px-3 py-1 rounded-full text-[8px] lg:text-xs font-bold bg-gray-900/80 text-white backdrop-blur-sm shadow-lg border border-white/10">
                        Locked
                    </span>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="p-3 lg:p-6 flex-1 flex flex-col">
            <h3 class="text-xs lg:text-xl font-bold text-gray-900 mb-1 lg:mb-2 line-clamp-1" title="<?= esc($video['title']) ?>">
                <?= esc($video['title']) ?>
            </h3>
            <p class="text-[10px] lg:text-sm text-gray-500 line-clamp-2 mb-3 lg:mb-6 flex-1">
                <?= esc($video['description']) ?>
            </p>
            
            <div class="mt-auto">
                <?php if ($video['has_active_access']): ?>
                    <a href="<?= base_url('customer/videos/watch/' . $video['id']) ?>" 
                       class="block w-full py-1.5 lg:py-2.5 px-3 lg:px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-center text-[10px] lg:text-sm font-semibold rounded-lg lg:rounded-xl shadow-lg shadow-indigo-500/30 transition-all transform active:scale-95">
                        Watch Now
                    </a>
                <?php elseif ($video['access_status'] === 'pending'): ?>
                    <button disabled class="block w-full py-1.5 lg:py-2.5 px-3 lg:px-4 bg-yellow-100 text-yellow-700 text-center text-[10px] lg:text-sm font-semibold rounded-lg lg:rounded-xl cursor-not-allowed opacity-75">
                        Pending
                    </button>
                <?php else: ?>
                    <form action="<?= base_url('customer/videos/request/' . $video['id']) ?>" method="POST" x-target="main-content">
                        <?= csrf_field() ?>
                        <button type="submit" class="block w-full py-1.5 lg:py-2.5 px-3 lg:px-4 bg-white border border-gray-900 hover:bg-gray-900 hover:text-white text-gray-900 text-center text-[10px] lg:text-sm font-bold rounded-lg lg:rounded-xl transition-colors">
                            Request
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
