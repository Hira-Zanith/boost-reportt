<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h3 class="font-bold text-lg mb-4">➕ បង្កើតគណនីបុគ្គលិកថ្មី</h3>
                <form action="{{ route('users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @csrf
                    <input type="text" name="name" placeholder="ឈ្មោះពេញ" class="rounded-md border-gray-300" required>
                    <input type="email" name="email" placeholder="អ៊ីមែល" class="rounded-md border-gray-300" required>
                    <input type="password" name="password" placeholder="លេខសម្ងាត់" class="rounded-md border-gray-300" required>
                    <select name="role" class="rounded-md border-gray-300">
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                    </select>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md font-bold">បង្កើត Account</button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3">ឈ្មោះ</th>
                            <th class="px-6 py-3">អ៊ីមែល</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)

                        <tr class="border-b">
                            <td class="px-6 py-4">{{ $user->name }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="{{ $user->role == 'admin' ? 'text-red-600 font-bold' : 'text-blue-600' }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <form action="{{ route('users.reset_password', $user->id) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="new_password" placeholder="Pass ថ្មី" 
                                            class="text-xs rounded border-gray-300 w-24 px-2 py-1" required>
                                        <button type="submit" class="bg-yellow-500 text-black px-2 py-1 rounded text-xs font-bold hover:bg-yellow-600">
                                            Reset
                                        </button>
                                    </form>

                                   
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('លុបមែនទេ?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:underline">🗑️ លុបចេញ</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>