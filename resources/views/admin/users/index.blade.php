@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Users</h2>

        <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}"
                class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Search</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                            class="text-sm font-medium text-blue-600 hover:text-blue-900 border-b border-transparent hover:border-blue-900">{{ $user->name }}</a>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @foreach($user->roles as $role)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                {{ $role->name === 'admin' ? 'bg-red-100 text-red-800' :
                                    ($role->name === 'staff' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $user->status === 'approved' ? 'bg-green-100 text-green-800' :
                    ($user->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($user->status ?? 'Pending') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-3">
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                    class="text-blue-600 hover:text-blue-900">View</a>

                                <button onclick="openRoleModal(this.dataset.userId, JSON.parse(this.dataset.roles))"
                                    data-user-id="{{ $user->id }}" data-roles="{{ json_encode($user->roles->pluck('id')) }}"
                                    class="text-indigo-600 hover:text-indigo-900">Edit Role</button>

                                @if($user->status !== 'approved')
                                    <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900"
                                            onclick="return confirm('Approve this user?')">Approve</button>
                                    </form>
                                @endif

                                @if($user->status !== 'rejected')
                                    <form action="{{ route('admin.users.reject', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Reject this user?')">Reject</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Role Modal -->
    <div id="roleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-bold mb-4">Manage User Roles</h3>
            <form id="roleForm" method="POST">
                @csrf
                <div class="space-y-3 mb-6">
                    @foreach($roles as $role)
                        <div class="flex items-center">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="role_{{ $role->id }}" class="ml-2 block text-sm text-gray-900">
                                {{ ucfirst($role->name) }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRoleModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRoleModal(userId, currentRoles) {
            const form = document.getElementById('roleForm');
            form.action = `/admin/users/${userId}/roles`;

            // Reset checkboxes
            document.querySelectorAll('input[name="roles[]"]').forEach(cb => cb.checked = false);

            // Check current roles
            currentRoles.forEach(roleId => {
                const cb = document.getElementById(`role_${roleId}`);
                if (cb) cb.checked = true;
            });

            document.getElementById('roleModal').classList.remove('hidden');
        }

        function closeRoleModal() {
            document.getElementById('roleModal').classList.add('hidden');
        }
    </script>
@endsection