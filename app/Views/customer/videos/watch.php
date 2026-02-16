<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Watch <?= esc($video['title']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="py-1">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Simple Back Link -->
        <div class="mb-1">
            <a href="<?= base_url('customer/videos') ?>" class="group inline-flex items-center text-[10px] font-semibold text-gray-400 hover:text-indigo-600 transition-colors">
                <div class="mr-2 p-1 rounded-lg bg-gray-100 group-hover:bg-indigo-50 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                Back to Library
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden" 
             x-data="{ 
                isExpired: false,
                expiry: new Date('<?= $expired_at ?>').getTime(),
                now: new Date().getTime(),
                h: '00', m: '00', s: '00',
                init() {
                    this.update();
                    setInterval(() => {
                        this.now = new Date().getTime();
                        this.update();
                    }, 1000);
                },
                update() {
                    let diff = Math.max(0, this.expiry - this.now);
                    if (diff <= 0) {
                        this.isExpired = true;
                        return;
                    }
                    
                    let hours = Math.floor(diff / (1000 * 60 * 60));
                    let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((diff % (1000 * 60)) / 1000);
                    
                    this.h = String(hours).padStart(2, '0');
                    this.m = String(minutes).padStart(2, '0');
                    this.s = String(seconds).padStart(2, '0');
                }
             }">
             
            <!-- Expired State View -->
            <div x-show="isExpired" x-cloak class="p-12 text-center">
                <div class="mx-auto w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Access Expired</h2>
                <p class="text-gray-500 mb-8 max-w-sm mx-auto">Your access duration for this video has ended. Please request a new access if you wish to continue watching.</p>
                <a href="<?= base_url('customer/videos') ?>" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                    Return to Library
                </a>
            </div>

            <div x-show="!isExpired">
                <!-- Integrated Premium Header -->
                <div class="bg-gray-800 px-6 py-2 flex flex-col md:flex-row justify-between items-center gap-2">
                    <div class="flex-1 min-w-0 text-center md:text-left">
                        <h1 class="text-sm lg:text-lg font-bold text-white tracking-tight truncate">
                            <?= esc($video['title']) ?>
                        </h1>
                    </div>

                    <!-- Sleek & Small Integrated Countdown -->
                    <?php if (isset($expired_at)): ?>
                    <div class="shrink-0 flex items-center bg-gray-900/80 backdrop-blur-md px-2 py-1 rounded-lg border border-white/10 shadow-lg">
                        <div class="flex items-center font-mono font-bold text-xs lg:text-sm text-sky-400 tabular-nums leading-none">
                            <span x-text="h">00</span>
                            <span class="mx-0.5 text-gray-500 font-sans text-[10px]">:</span>
                            <span x-text="m">00</span>
                            <span class="mx-0.5 text-gray-500 font-sans text-[10px]">:</span>
                            <span x-text="s">00</span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Clean Description Area -->
                <?php if (!empty($video['description'])): ?>
                <div class="px-6 py-2.5 lg:py-3 bg-gray-50/50 border-b border-gray-100">
                    <p class="text-gray-500 text-xs lg:text-sm leading-relaxed max-w-4xl font-medium">
                        <?= esc($video['description']) ?>
                    </p>
                </div>
                <?php endif; ?>

                <!-- Focused Video Area -->
                <div class="bg-gray-900 aspect-video flex items-center justify-center relative shadow-inner">
                    <?php 
                        $videoPath = $video['video_path'];
                        $isUrl = filter_var($videoPath, FILTER_VALIDATE_URL);
                        $isYoutube = $isUrl && (strpos($videoPath, 'youtube.com') !== false || strpos($videoPath, 'youtu.be') !== false);
                    ?>

                    <?php if ($isYoutube): ?>
                        <?php
                            $videoId = '';
                            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videoPath, $match)) {
                                $videoId = $match[1];
                            }
                        ?>
                        <?php if ($videoId): ?>
                            <iframe class="absolute inset-0 w-full h-full" src="https://www.youtube.com/embed/<?= $videoId ?>?rel=0&modestbranding=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <?php else: ?>
                            <div class="text-white text-center opacity-50">
                                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <p class="font-bold">Invalid YouTube Resource</p>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <video id="player" controls class="w-full h-full object-contain" controlsList="nodownload">
                            <source src="<?= $isUrl ? esc($videoPath) : base_url('customer/videos/stream/' . $video['id']) ?>" type="video/mp4">
                            Your browser does not support HTML5 video.
                        </video>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
