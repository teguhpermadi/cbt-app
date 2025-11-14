<div class="p-4 sm:p-6 lg:p-8">

    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">
            User Management
        </h2>
        
        @if (session('success'))
            <div 
                x-data="{ show: true }" 
                x-init="setTimeout(() => show = false, 3000)" 
                x-show="show" 
                x-transition
                class="mt-2 sm:mt-0 sm:ml-4 text-sm font-medium text-green-600 dark:text-green-400"
            >
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="mb-4">
        <nav class="flex space-x-2" aria-label="Tabs">
            @php
                $types = ['student', 'teacher', 'admin', 'parent'];
            @endphp

            @foreach ($types as $type)
                <button
                    wire:click="changeUserType('{{ $type }}')"
                    class="capitalize px-3 py-2 font-medium text-sm rounded-md transition-colors
                           {{ $user_type === $type 
                                ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-200' 
                                : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}"
                >
                    {{ $type }}
                </button>
            @endforeach
        </nav>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
        
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <div>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Search name, email, or username..."
                    class="block w-full sm:w-64 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-sm shadow-sm placeholder-gray-400
                           focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:text-gray-200"
                >
            </div>

            <div>
                @if ($selectedUsers)
                    <button 
                        wire:click="confirmBulkDelete"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium px-4 py-2 
                               bg-red-600 text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600
                               disabled:opacity-50"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="confirmBulkDelete">
                            Delete Selected ({{ count($selectedUsers) }})
                        </span>
                        <span wire:loading wire:target="confirmBulkDelete">
                            Loading...
                        </span>
                    </button>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th scope="col" class="p-4">
                            <input 
                                wire:model.live="selectAll" 
                                type="checkbox" 
                                class="h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500 dark:bg-gray-700"
                            >
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Username</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                        
                        @if ($user_type === 'student')
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">NISN</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">NIS</th>
                        @endif

                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($this->users as $user)
                        <tr wire:key="user-{{ $user->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="p-4">
                                <input 
                                    wire:model.live="selectedUsers" 
                                    value="{{ $user->id }}" 
                                    type="checkbox" 
                                    class="h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500 dark:bg-gray-700"
                                >
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                            
                            @if ($user_type === 'student')
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->nisn ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->nis ?? '-' }}</td>
                            @endif

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button 
                                    wire:click="confirmDelete({{ $user->id }})"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $user_type === 'student' ? '7' : '5' }}" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                No users found for type '{{ $user_type }}'
                                @if($search)
                                    with search query '{{ $search }}'.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($this->users->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $this->users->links() }}
            </div>
        @endif

    </div>

    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50" x-transition>
            <div 
                @click.away="$wire.set('showDeleteModal', false)" 
                class="w-full max-w-md bg-white dark:bg-gray-900 rounded-lg shadow-xl"
            >
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirm Deletion</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Are you sure you want to delete <strong>{{ $userToDelete?->name }}</strong>? This action cannot be undone.
                    </p>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button 
                            wire:click="$set('showDeleteModal', false)"
                            type="button" 
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium px-4 py-2 
                                   bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 
                                   hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm"
                        >
                            Cancel
                        </button>
                        <button 
                            wire:click="delete"
                            type="button" 
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium px-4 py-2 
                                   bg-red-600 text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600
                                   disabled:opacity-50"
                            wire:loading.attr="disabled"
                            wire:target="delete"
                        >
                            <span wire:loading.remove wire:target="delete">Delete</span>
                            <span wire:loading wire:target="delete">Deleting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showBulkDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50" x-transition>
            <div 
                @click.away="$wire.set('showBulkDeleteModal', false)" 
                class="w-full max-w-md bg-white dark:bg-gray-900 rounded-lg shadow-xl"
            >
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirm Bulk Deletion</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Are you sure you want to delete <strong>{{ count($selectedUsers) }}</strong> selected user(s)? This action cannot be undone.
                    </p>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button 
                            wire:click="$set('showBulkDeleteModal', false)"
                            type="button" 
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium px-4 py-2 
                                   bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 
                                   hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm"
                        >
                            Cancel
                        </button>
                        <button 
                            wire:click="deleteSelected"
                            type="button" 
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium px-4 py-2 
                                   bg-red-600 text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600
                                   disabled:opacity-50"
                            wire:loading.attr="disabled"
                            wire:target="deleteSelected"
                        >
                            <span wire:loading.remove wire:target="deleteSelected">Delete Selected</span>
                            <span wire:loading wire:target="deleteSelected">Deleting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>