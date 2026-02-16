<?php foreach ($requests as $request): ?>
    <tr class="hover:bg-gray-50 transition-colors" x-data="{ isEditing: false }">
        <td class="px-4 lg:px-6 py-3 lg:py-4">
            <div class="flex flex-col">
                <span class="text-xs lg:text-sm font-bold text-gray-900 line-clamp-1"><?= esc($request['video_title']) ?></span>
                <span class="text-[10px] lg:text-xs text-gray-500 truncate">By: <span class="font-medium text-gray-700"><?= esc($request['user_name']) ?></span></span>
            </div>
        </td>
        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-[10px] lg:text-sm text-gray-500 hidden md:table-cell">
            <?= date('M d, Y H:i', strtotime($request['requested_at'])) ?>
        </td>
        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap hidden sm:table-cell">
            <?php 
                $isExpired = ($request['status'] === 'approved' && !empty($request['expired_at']) && strtotime($request['expired_at']) < time());
                $statusClass = 'bg-gray-100 text-gray-800';
                $statusLabel = ucfirst(esc($request['status']));

                if ($isExpired) {
                    $statusClass = 'bg-red-100 text-red-800';
                    $statusLabel = 'Expired';
                } elseif ($request['status'] === 'approved') {
                    $statusClass = 'bg-green-100 text-green-800';
                } elseif ($request['status'] === 'pending') {
                    $statusClass = 'bg-yellow-100 text-yellow-800';
                } elseif ($request['status'] === 'rejected') {
                    $statusClass = 'bg-red-50 text-red-600';
                }
            ?>
            <span class="px-2 inline-flex text-[10px] lg:text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                <?= $statusLabel ?>
            </span>
        </td>
        <td class="px-4 lg:px-6 py-3 lg:py-4 pr-8 lg:pr-12 whitespace-nowrap text-right text-[10px] lg:text-sm font-medium">
            <?php if ($request['status'] === 'pending'): ?>
                <div class="flex items-center justify-end space-x-2 lg:space-x-3" x-data="{ h: 24, m: 0 }">
                    <form hx-post="<?= base_url('admin/requests/approve/' . $request['id']) ?>" @submit="$dispatch('edit-stopped')" hx-target="closest tr" hx-swap="outerHTML" class="flex items-center space-x-2 lg:space-x-4">
                        <?= csrf_field() ?>
                        <!-- Clock Style Input (Larger for Touch) -->
                        <div class="flex items-center bg-gray-50 border border-gray-200 rounded-2xl px-3 lg:px-4 py-2 lg:py-3 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 group transition-all shadow-sm">
                            <input type="number" name="duration_h" x-model="h" min="0" class="no-spinner-mobile w-10 lg:w-12 bg-transparent border-none p-0 text-center text-sm lg:text-base font-bold text-gray-900 focus:ring-0" placeholder="00">
                            <span class="text-gray-400 font-bold mx-1 group-focus-within:text-indigo-500">:</span>
                            <input type="number" name="duration_m" x-model="m" min="0" max="59" class="no-spinner-mobile w-10 lg:w-12 bg-transparent border-none p-0 text-center text-sm lg:text-base font-bold text-gray-900 focus:ring-0" placeholder="00">
                            <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-700 p-3 lg:px-5 lg:py-4 rounded-2xl transition-all shadow-lg shadow-indigo-500/20 active:scale-95 border border-indigo-500" title="Approve">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </form>
                    <div class="h-10 w-px bg-gray-200 mx-1"></div>
                    <form hx-post="<?= base_url('admin/requests/reject/' . $request['id']) ?>" hx-target="closest tr" hx-swap="outerHTML">
                        <?= csrf_field() ?>
                        <button type="submit" class="bg-white text-red-600 hover:bg-red-50 p-3 lg:px-5 lg:py-4 rounded-2xl transition-all border border-red-100 hover:border-red-200 shadow-sm active:scale-95" title="Reject">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </form>
                </div>
            <?php elseif ($request['status'] === 'approved'): 
                $start = new \DateTime($request['approved_at']);
                $end = new \DateTime($request['expired_at']);
                $diff = $start->diff($end);
                $currentH = ($diff->days * 24) + $diff->h;
                $currentM = $diff->i;
            ?>
                <div class="flex items-center justify-end space-x-2 lg:space-x-4" x-data="{ h: <?= $currentH ?>, m: <?= $currentM ?> }">
                    <div x-show="!isEditing" class="flex items-center justify-end space-x-2 lg:space-x-4">
                        <div class="flex items-center text-[11px] lg:text-sm text-gray-700 bg-gray-50 px-2 lg:px-3 py-1.5 lg:py-2 rounded-xl border border-gray-100 font-medium">
                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Exp: <?= date('M d, H:i', strtotime($request['expired_at'])) ?>
                        </div>
                        <div class="flex items-center space-x-1 lg:space-x-2">
                            <button @click="isEditing = true; $dispatch('edit-started')" class="p-2.5 lg:p-3 text-indigo-600 hover:bg-indigo-50 rounded-2xl transition-all border border-transparent hover:border-indigo-100" title="Edit Duration">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button hx-get="<?= base_url('admin/requests/delete/' . $request['id']) ?>" hx-confirm="Revoke this access?" hx-target="closest tr" hx-swap="outerHTML" class="p-2.5 lg:p-3 text-red-600 hover:bg-red-50 rounded-2xl transition-all border border-transparent hover:border-red-100" title="Revoke Access">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                    
                    <form x-show="isEditing" x-cloak hx-post="<?= base_url('admin/requests/update/' . $request['id']) ?>" @submit="$dispatch('edit-stopped')" hx-target="closest tr" hx-swap="outerHTML" class="flex items-center justify-end space-x-2 lg:space-x-4">
                        <?= csrf_field() ?>
                        <!-- Clock Style Input (Larger for Touch) -->
                        <div class="flex items-center bg-white border border-gray-200 rounded-2xl px-3 lg:px-4 py-2 lg:py-3 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 group transition-all shadow-sm">
                            <input type="number" name="duration_h" x-model="h" min="0" class="no-spinner-mobile w-10 lg:w-12 bg-transparent border-none p-0 text-center text-sm lg:text-base font-bold text-gray-900 focus:ring-0">
                            <span class="text-gray-400 font-bold mx-1 group-focus-within:text-indigo-500">:</span>
                            <input type="number" name="duration_m" x-model="m" min="0" max="59" class="no-spinner-mobile w-10 lg:w-12 bg-transparent border-none p-0 text-center text-sm lg:text-base font-bold text-gray-900 focus:ring-0">
                        </div>
                        <div class="flex items-center space-x-1 lg:space-x-2">
                            <button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-700 p-3 lg:px-4 lg:py-3.5 rounded-2xl transition-all shadow-lg shadow-indigo-500/20 active:scale-95 border border-indigo-500" title="Save">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                            <button type="button" @click="isEditing = false; $dispatch('edit-stopped')" class="bg-gray-100 text-gray-600 hover:bg-gray-200 p-3 lg:px-4 lg:py-3.5 rounded-2xl transition-all active:scale-95" title="Cancel">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="flex justify-end">
                    <button hx-get="<?= base_url('admin/requests/delete/' . $request['id']) ?>" hx-confirm="Remove this record?" hx-target="closest tr" hx-swap="outerHTML" class="text-gray-400 hover:text-red-600 text-[10px] lg:text-xs transition-colors font-medium">Remove Record</button>
                </div>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>
