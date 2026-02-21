<?php foreach ($videos as $video): ?>
    <tr class="hover:bg-gray-50 transition-colors">
        <td class="px-4 lg:px-6 py-3 lg:py-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 lg:h-16 lg:w-16 bg-gray-900 rounded-lg flex items-center justify-center text-white text-xs overflow-hidden">
                    <svg class="h-5 w-5 lg:h-6 lg:w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </div>
                <div class="ml-3 lg:ml-4">
                    <div class="text-xs lg:text-sm font-bold text-gray-900 line-clamp-1 max-w-[120px] lg:max-w-none"><?= esc($video['title']) ?></div>
                    <div class="text-[10px] lg:text-xs text-gray-500 mt-1 line-clamp-1 max-w-[150px] lg:max-w-xs"><?= esc($video['description']) ?></div>
                </div>
            </div>
        </td>
        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap hidden sm:table-cell">
            <div class="text-[10px] lg:text-xs text-gray-500 font-mono bg-gray-50 px-2 py-1 rounded border border-gray-200 inline-block truncate max-w-[100px] lg:max-w-none">
                <?= substr(esc($video['video_path']), 0, 30) ?><?= strlen($video['video_path']) > 30 ? '...' : '' ?>
            </div>
        </td>
        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-[10px] lg:text-sm text-gray-500 hidden md:table-cell">
            <?= date('M d, Y', strtotime($video['created_at'])) ?>
        </td>
        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-right text-[10px] lg:text-sm font-medium">
            <button type="button" @click='openEditModal(<?= json_encode($video) ?>)' class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-2 lg:px-3 py-1 lg:py-1.5 rounded-lg mr-1 lg:mr-2 hover:bg-indigo-100 transition-colors">Edit</button>
            <button type="button" @click="if(confirm('Are you sure you want to delete this video?')) { $ajax('<?= base_url('admin/videos/delete/' . $video['id']) ?>', { target: 'main-content' }) }" class="text-red-600 hover:text-red-900 bg-red-50 px-2 lg:px-3 py-1 lg:py-1.5 rounded-lg hover:bg-red-100 transition-colors">Delete</button>
        </td>
    </tr>
<?php endforeach; ?>
