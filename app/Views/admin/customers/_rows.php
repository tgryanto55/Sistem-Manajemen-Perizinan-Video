<?php foreach ($customers as $customer): ?>
    <tr class="hover:bg-gray-50 transition-colors">
        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-8 w-8 lg:h-10 lg:w-10">
                    <div class="h-8 w-8 lg:h-10 lg:w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs lg:text-base">
                        <?= strtoupper(substr($customer['name'], 0, 1)) ?>
                    </div>
                </div>
                <div class="ml-3 lg:ml-4">
                    <div class="text-xs lg:text-sm font-medium text-gray-900 line-clamp-1 max-w-[100px] lg:max-w-none"><?= esc($customer['name']) ?></div>
                    <div class="text-[10px] lg:text-xs text-gray-400">ID: #<?= $customer['id'] ?></div>
                </div>
            </div>
        </td>
        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
            <div class="text-xs lg:text-sm text-gray-500 truncate max-w-[120px] lg:max-w-none"><?= esc($customer['email']) ?></div>
        </td>
        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap hidden sm:table-cell">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                Active
            </span>
        </td>
        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-[10px] lg:text-sm text-gray-500 hidden md:table-cell">
            <?= date('M d, Y', strtotime($customer['created_at'])) ?>
        </td>
        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-right text-[10px] lg:text-sm font-medium">
            <button type="button" @click='openEditModal(<?= json_encode($customer) ?>)' class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-2 lg:px-3 py-1 lg:py-1.5 rounded-lg mr-1 lg:mr-2 hover:bg-indigo-100 transition-colors">Edit</button>
            <button hx-get="<?= base_url('admin/customers/delete/' . $customer['id']) ?>" hx-confirm="Are you sure you want to delete this customer?" hx-target="closest tr" hx-swap="outerHTML" class="text-red-600 hover:text-red-900 bg-red-50 px-2 lg:px-3 py-1 lg:py-1.5 rounded-lg hover:bg-red-100 transition-colors">Delete</button>
        </td>
    </tr>
<?php endforeach; ?>
