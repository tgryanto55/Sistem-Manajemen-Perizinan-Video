<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Videos<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto" x-data="{ 
        showCreateModal: <?= session()->has('show_create_modal') ? 'true' : 'false' ?>,
        showEditModal: <?= session()->has('show_edit_modal') ? 'true' : 'false' ?>,
        editingVideo: {
            id: '<?= session('edit_video_id') ?>',
            title: '<?= old('title') ?>',
            description: '<?= old('description') ?>',
            video_path: ''
        },
        openEditModal(video) {
            this.editingVideo = { ...video };
            this.showEditModal = true;
        }
    }" x-init="const urlParams = new URLSearchParams(window.location.search); if (urlParams.get('create')) showCreateModal = true"
       @video-saved.window="showCreateModal = false; showEditModal = false">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Video Library</h1>
                <p class="mt-1 text-xs sm:text-sm text-gray-500">Manage your video content and metadata.</p>
            </div>
            <button @click="showCreateModal = true" class="hidden sm:inline-flex items-center px-4 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add Video
            </button>
        </div>

        <!-- Create Video Modal -->
        <div x-show="showCreateModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/75 transition-opacity" @click="showCreateModal = false" aria-hidden="true"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="showCreateModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom sm:align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg w-full relative z-10">
                    
                    <form hx-post="<?= base_url('admin/videos') ?>" hx-target="#video-table-body">
                        <?= csrf_field() ?>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="text-center sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Add New Video</h3>
                            </div>
                            <div class="mt-4 space-y-4 text-left">
                                <div>
                                    <label for="title" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Video Title</label>
                                    <input type="text" name="title" id="title" required class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="Enter video title">
                                </div>
                                <div>
                                    <label for="description" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Description</label>
                                    <textarea name="description" id="description" rows="3" required class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="Enter video description"></textarea>
                                </div>
                                <div>
                                    <label for="video_url" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Video Source URL</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                        </div>
                                        <input type="url" name="video_url" id="video_url" required class="block w-full pl-10 px-4 py-2.5 text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="YouTube or Direct link">
                                    </div>
                                    <p class="mt-1.5 text-[10px] text-gray-400">Supported: YouTube, Google Drive, or any direct MP4 URL.</p>
                                </div>
                                <?php if (session()->has('errors') && !session()->has('show_edit_modal')): ?>
                                    <div class="rounded-xl bg-red-50 p-4 border border-red-100 mt-2"><div class="text-sm text-red-700"><?= implode('<br>', session('errors')) ?></div></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-col sm:flex-row-reverse gap-3 sm:gap-0">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2.5 bg-indigo-600 text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto transition-all active:scale-95">Save Video</button>
                            <button type="button" @click="showCreateModal = false" class="w-full inline-flex justify-center rounded-xl border border-gray-200 shadow-sm px-4 py-2.5 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto transition-all active:scale-95">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Video Modal -->
        <div x-show="showEditModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/75 transition-opacity" @click="showEditModal = false" aria-hidden="true"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="showEditModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom sm:align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg w-full relative z-10">
                    
                    <form hx-post="<?= base_url('admin/videos') ?>" hx-target="#video-table-body">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" :value="editingVideo.id">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="text-center sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Video Details</h3>
                            </div>
                            <div class="mt-4 space-y-4 text-left">
                                <div>
                                    <label for="edit_title" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Video Title</label>
                                    <input type="text" name="title" id="edit_title" x-model="editingVideo.title" required class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                </div>
                                <div>
                                    <label for="edit_description" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Description</label>
                                    <textarea name="description" id="edit_description" x-model="editingVideo.description" rows="3" required class="block w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"></textarea>
                                </div>
                                <div>
                                    <label for="edit_video_url" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Update Video URL <span class="text-gray-400 font-normal normal-case">(Optional)</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                        </div>
                                        <input type="url" name="video_url" id="edit_video_url" x-model="editingVideo.video_path" class="block w-full pl-10 px-4 py-2.5 text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                    </div>
                                    <p class="mt-1.5 text-[10px] text-gray-400">Current source: <span x-text="editingVideo.video_path" class="italic"></span></p>
                                </div>
                                <?php if (session()->has('errors') && session()->has('show_edit_modal')): ?>
                                    <div class="rounded-xl bg-red-50 p-4 border border-red-100 mt-2"><div class="text-sm text-red-700"><?= implode('<br>', session('errors')) ?></div></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-col sm:flex-row-reverse gap-3 sm:gap-0">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2.5 bg-indigo-600 text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto transition-all active:scale-95">Update Video</button>
                            <button type="button" @click="showEditModal = false" class="w-full inline-flex justify-center rounded-xl border border-gray-200 shadow-sm px-4 py-2.5 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto transition-all active:scale-95">Cancel</button>
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
                            <th scope="col" class="px-4 lg:px-6 py-3 lg:py-4 text-left text-[10px] lg:text-xs font-semibold text-gray-500 uppercase tracking-wider">Video Details</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 lg:py-4 text-left text-[10px] lg:text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Source info</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 lg:py-4 text-left text-[10px] lg:text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Uploaded</th>
                            <th scope="col" class="relative px-4 lg:px-6 py-3 lg:py-4"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody id="video-table-body" class="bg-white divide-y divide-gray-100" hx-get="<?= base_url('admin/videos/rows') ?>" hx-trigger="every 5s">
                        <?= $this->include('admin/videos/_rows') ?>
                    </tbody>
                </table>
            </div>
            <?php if (empty($videos)): ?>
                <div class="text-center py-12">
                     <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.818v6.364a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <p class="mt-2 text-gray-500 text-sm">No videos uploaded yet.</p>
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
