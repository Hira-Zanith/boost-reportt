<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight khmer-font">
            📊 Ads Reporting System
        </h2>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap');
        .khmer-font { font-family: 'Kantumruy Pro', sans-serif; }
    </style>

    <div class="py-12 khmer-font bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 mb-6 border border-gray-200">
                <h3 class="text-center text-blue-600 font-bold text-xl mb-6">📝 បញ្ចូលរបាយការណ៍</h3>
                
                <form method="POST" action="{{ route('report.store') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-red-600 mb-1">👤 ឈ្មោះបុគ្គលិក</label>
                        <input type="text" name="staff_name" value="{{ Auth::user()->name }}" readonly 
                            class="w-full rounded-md border-gray-200 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 text-gray-600">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-orange-700 mb-1">ឈ្មោះក្បាល</label>
                        <input type="text" name="product" placeholder="បញ្ចូលឈ្មោះក្បាល..." required
                            class="w-full rounded-md border-gray-200 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-yellow-700 mb-1">💰 ចំណាយ ($)</label>
                        <input type="number" step="0.01" name="spend" placeholder="បញ្ចូលចំនួនទឹកប្រាក់..." required
                            class="w-full rounded-md border-gray-200 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-green-700 mb-1">🧾 វិក្កយបត្រ (Invoice $)</label>
                        <input type="number" step="0.01" name="invoice_amount" placeholder="0.00" required
                            class="w-full rounded-md border-gray-200 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-purple-700 mb-1">💬 Messages</label>
                        <input type="number" name="messages" placeholder="បញ្ចូលចំនួនសារ..." required
                            class="w-full rounded-md border-gray-200 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-blue-900 mb-1">👥 New ID (មនុស្សពិត)</label>
                        <input type="number" name="new_id" placeholder="បញ្ចូលចំនួន ID ថ្មី..." required
                            class="w-full rounded-md border-gray-200 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-md shadow transition duration-200">
                        បាញ់ទៅ Telegram 🚀
                    </button>
                    <button type="reset" class="w-full text-gray-400 text-xs hover:text-gray-600 transition">សម្អាត (Reset)</button>
                </form>
            </div>
             <!-- Summary Cards -->
            <div class="bg-blue-900 rounded-xl shadow-lg p-6 text-white text-center mb-6 border-b-4 border-blue-700">
                <p class="text-xs opacity-70 uppercase tracking-widest">ចំណាយសរុបទាំងអស់</p>
                <h2 class="text-4xl font-bold my-2">${{ number_format($total_spend, 2) }}</h2>
                <div class="flex justify-around mt-4 pt-4 border-t border-blue-800 opacity-80">
                    <div><p class="text-xs">Messages</p><p class="font-bold text-lg">{{ $total_msg }}</p></div>
                    <div><p class="text-xs">New ID</p><p class="font-bold text-lg">{{ $total_new }}</p></div>
                    <div><p class="text-xs">Invoice</p><p class="font-bold text-lg">${{ number_format($total_invoice_amount, 2) }}</p></div>
                </div>
            </div>

             <!-- Search & Filter Form -->
            <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search_staff" value="{{ request('search_staff') }}" placeholder="ឈ្មោះបុគ្គលិក..." class="rounded-md border-gray-300 shadow-sm">

                

                <div class="flex flex-col">
                    <label class="text-[10px] font-bold text-gray-500 uppercase">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded-md border-gray-300 shadow-sm">
                </div>

                <div class="flex flex-col">
                    <label class="text-[10px] font-bold text-gray-500 uppercase">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded-md border-gray-300 shadow-sm">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-bold w-full">ស្វែងរក 🔍</button>
                    <a href="{{ route('dashboard') }}" class="bg-gray-200 px-4 py-2 rounded-md hover:bg-gray-300 text-center">Reset</a>
                </div>
                

            </form>
            
            <!-- Export Section -->
                <div class="bg-gray-50 p-4 rounded-lg border mb-6 mt-6" >
                    <h4 class="text-sm font-bold mb-3 text-gray-700">⚙️ កំណត់ការ Export (Excel/PDF)</h4>
                    <form id="exportForm" method="GET" action="">
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <input type="hidden" name="search_staff" value="{{ request('search_staff') }}">

                        <div class="flex flex-wrap gap-4 mb-4">
                            <label class="flex items-center text-sm"><input type="checkbox" name="columns[]" value="created_at" checked class="mr-2"> កាលបរិច្ឆេទ</label>
                            <label class="flex items-center text-sm"><input type="checkbox" name="columns[]" value="staff_name" checked class="mr-2"> ឈ្មោះបុគ្គលិក</label>
                            <label class="flex items-center text-sm"><input type="checkbox" name="columns[]" value="product" checked class="mr-2"> ផលិតផល</label>
                            <label class="flex items-center text-sm"><input type="checkbox" name="columns[]" value="spend" checked class="mr-2"> Spend</label>
                            <label class="flex items-center text-sm"><input type="checkbox" name="columns[]" value="invoice_amount" checked class="mr-2"> Invoice</label>
                            <label class="flex items-center text-sm"><input type="checkbox" name="columns[]" value="messages" checked class="mr-2"> Messages</label>
                            <label class="flex items-center text-sm"><input type="checkbox" name="columns[]" value="new_id" checked class="mr-2"> New ID</label>
                        </div>

                        <div class="flex gap-2">
                            <button type="button" onclick="submitExport('{{ route('export.excel') }}')" class="bg-green-600 text-white px-4 py-2 rounded font-bold text-sm">Export Excel 🟢</button>
                            <button type="button" onclick="submitExport('{{ route('export.pdf') }}')" class="bg-red-600 text-white px-4 py-2 rounded font-bold text-sm">Export PDF 🔴</button>
                        </div>
                    </form>
                </div>
                <!--  JavaScript function to handle Export with current filters -->
                <script>
                    function submitExport(url) {
                        const exportForm = document.getElementById('exportForm');
                        
                        // ១. ទាញយកតម្លៃពី Search Form (Form ស្វែងរកខាងលើ)
                        const searchStaff = document.querySelector('input[name="search_staff"]').value;
                        const startDate = document.querySelector('input[name="start_date"]').value;
                        const endDate = document.querySelector('input[name="end_date"]').value;

                        // ២. បញ្ចូលតម្លៃទាំងនោះទៅក្នុង Hidden Input របស់ Export Form
                        exportForm.querySelector('input[name="search_staff"]').value = searchStaff;
                        exportForm.querySelector('input[name="start_date"]').value = startDate;
                        exportForm.querySelector('input[name="end_date"]').value = endDate;

                        // ៣. Submit Form ទៅកាន់ URL (Excel ឬ PDF)
                        exportForm.action = url;
                        exportForm.submit();
                    }
                </script>

             <!-- Reports Table show -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b bg-gray-50">
                    <h4 class="font-bold text-gray-700 italic">📊 ប្រវត្តិការងារ ១០ ថ្ងៃចុងក្រោយ</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 font-bold text-gray-600">កាលបរិច្ឆេទ</th>
                                <th class="px-4 py-3 font-bold text-gray-600">ឈ្មោះ</th>
                                <th class="px-4 py-3 font-bold text-gray-600">ឈ្មោះក្បាល</th>
                                <th class="px-4 py-3 font-bold text-gray-600">Spend</th>
                                <th class="px-4 py-3 font-bold text-gray-600">Invoice $</th>
                                <th class="px-4 py-3 font-bold text-gray-600">Msg</th>
                                <th class="px-4 py-3 font-bold text-gray-600">New ID</th>
                                <th class="px-4 py-3 font-bold text-gray-600">$/Msg</th>
                                <th class="px-4 py-3 font-bold text-gray-600 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($reports as $row)
                                @php $cpm = $row->messages > 0 ? ($row->spend / $row->messages) : 0; @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-4 text-gray-400 text-xs">{{ $row->created_at->format('M d, H:i') }}</td>
                                    <td class="px-4 py-4 font-bold">{{ $row->staff_name }}</td>
                                    <td class="px-4 py-4 font-bold text-blue-600">{{ $row->product }}</td>
                                    <td class="px-4 py-4 font-bold text-gray-800">${{ number_format($row->spend, 2) }}</td>
                                    <td class="px-4 py-4 font-bold text-green-600">${{ number_format($row->invoice_amount, 2) }}</td>
                                    <td class="px-4 py-4">{{ $row->messages }}</td>
                                    <td class="px-4 py-4">{{ $row->new_id }}</td>
                                    <td class="px-4 py-4 font-bold text-red-500">${{ number_format($cpm, 2) }}</td>
                                    <td class="px-4 py-4 text-center">
                                        @if(Auth::user()->role === 'admin' || Auth::id() === $row->user_id)
                                            <form action="{{ route('report.destroy', $row->id) }}" method="POST" onsubmit="return confirm('លុបទិន្នន័យនេះ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-500">🗑️</button>
                                            </form>
                                        @else
                                            <span class="text-gray-300 text-[10px]">🔒 No Access</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400 italic">មិនទាន់មានរបាយការណ៍នៅឡើយទេ។</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t bg-gray-50">
                    {{ $reports->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>