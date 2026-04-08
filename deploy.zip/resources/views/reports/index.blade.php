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



    <div class="py-6 khmer-font bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">



            {{-- ១. បង្ហាញ Form តែនៅលើទំព័រ Dashboard ប៉ុណ្ណោះ --}}

            @if(request()->routeIs('dashboard'))

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 border border-gray-200">

                <h3 class="text-center text-blue-600 font-bold text-xl mb-6">📝 បញ្ចូលរបាយការណ៍</h3>

               

                <form method="POST" action="{{ route('report.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                    @csrf

                    <div class="md:col-span-1 lg:col-span-1">

                        <label class="block text-sm font-bold text-red-600 mb-1">👤 ឈ្មោះបុគ្គលិក</label>

                        <input type="text" name="staff_name" value="{{ Auth::user()->name }}" readonly

                            class="w-full rounded-md border-gray-200 bg-gray-50 text-gray-600">

                    </div>



                    <div>

                        <label class="block text-sm font-bold text-orange-700 mb-1">ឈ្មោះក្បាល</label>

                        <input type="text" name="product" placeholder="បញ្ចូលឈ្មោះក្បាល..." required

                            class="w-full rounded-md border-gray-200 focus:ring-blue-500">

                    </div>



                    <div>

                        <label class="block text-sm font-bold text-yellow-700 mb-1"> ចំណាយ ($)</label>

                        <input type="number" step="0.01" name="spend" placeholder="0.00" required

                            class="w-full rounded-md border-gray-200 focus:ring-blue-500">

                    </div>



                    <div>

                        <label class="block text-sm font-bold text-green-700 mb-1"> វិក្កយបត្រ (Invoice $)</label>

                        <input type="number" step="0.01" name="invoice_amount" placeholder="0.00" required

                            class="w-full rounded-md border-gray-200 focus:ring-green-500">

                    </div>



                    <div>

                        <label class="block text-sm font-bold text-purple-700 mb-1"> Messages</label>

                        <input type="number" name="messages" placeholder="0" required

                            class="w-full rounded-md border-gray-200 focus:ring-blue-500">

                    </div>



                    <div>

                        <label class="block text-sm font-bold text-blue-900 mb-1"> New ID</label>

                        <input type="number" name="new_id" placeholder="0" required

                            class="w-full rounded-md border-gray-200 focus:ring-blue-500">

                    </div>

                    <div>

                        <label class="block text-sm font-bold text-gray-700 mb-1">ACC Test</label>

                        <input type="text" name="acc_test" placeholder="សម្រាប់តេស្ត..."

                            class="w-full rounded-md border-gray-200 focus:ring-blue-500">

                    </div>





                    <div class="md:col-span-2 lg:col-span-3 flex flex-col sm:flex-row gap-2 mt-2">

                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-md shadow transition">

                            Save and Send Telegram 🚀

                        </button>

                        <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-600 px-6 py-3 rounded-md text-sm transition">

                            Reset

                        </button>

                    </div>

                </form>

            </div>

            @endif

            {{-- ផ្នែកបង្ហាញសរុបដែលបាន Search (ដាក់ពីលើតារាង) --}}
            <div class="bg-gradient-to-r from-blue-700 to-indigo-800 rounded-xl shadow-lg p-6 text-white mb-6 border-b-4 border-blue-900">
                <div class="flex justify-between items-center border-b border-blue-600 pb-3 mb-4">
                    <h3 class="font-bold text-lg">📊 សរុបតាមការស្វែងរក (Filtered Total)</h3>
                    <span class="text-xs bg-blue-500 px-2 py-1 rounded">
                        @if(request('start_date') && request('end_date'))
                            {{ request('start_date') }} ដល់ {{ request('end_date') }}
                        @else
                            លទ្ធផលទាំងអស់
                        @endif
                    </span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div class="border-r border-blue-600/50">
                        <p class="text-[10px] opacity-70 uppercase">Spend សរុប</p>
                        <p class="text-2xl font-black">${{ number_format($total_spend, 2) }}</p>
                    </div>
                    <div class="border-r border-blue-600/50">
                        <p class="text-[10px] opacity-70 uppercase">Invoice សរុប</p>
                        <p class="text-2xl font-black">${{ number_format($total_invoice_amount, 2) }}</p>
                    </div>
                    <div class="border-r border-blue-600/50">
                        <p class="text-[10px] opacity-70 uppercase">Messages</p>
                        <p class="text-2xl font-black">{{ number_format($total_msg) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] opacity-70 uppercase">New ID</p>
                        <p class="text-2xl font-black">{{ number_format($total_new) }}</p>
                    </div>
                </div>
            </div>



            {{-- ៣. ប៊ូតុង និង Filter --}}

            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

                <h2 class="text-xl font-bold">📊 បញ្ជីរបាយការណ៍ទាំងអស់</h2>

                @if(request()->routeIs('reports.index'))

                    <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md font-bold text-sm hover:bg-blue-700 w-full md:w-auto text-center">

                        + បញ្ចូលរបាយការណ៍ថ្មី

                    </a>
                    

                @endif

            </div>



            {{-- ៤. Search Form --}}

            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <form method="GET" action="{{ url()->current() }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">ស្វែងរកឈ្មោះ</label>
                        <input type="text" name="search_staff" value="{{ request('search_staff') }}" class="w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">ឈ្មោះក្បាល</label>
                        <input type="text" name="search_product" value="{{ request('search_product') }}" placeholder="Ads_01..." class="w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md font-bold flex-1">ស្វែងរក</button>
                        <a href="{{ url()->current() }}" class="bg-gray-200 px-4 py-2 rounded-md italic text-xs">Reset</a>
                    </div>
                </form>
            </div>

            



            {{-- ៥. Export Section --}}

            <div class="bg-gray-50 p-4 rounded-lg border border-dashed border-gray-300 mb-6 text-center">

                <h4 class="text-sm font-bold mb-3 text-gray-700">⚙️ កំណត់ការ Export (Excel/PDF)</h4>

                <form id="exportForm" method="GET" action="">

                    <input type="hidden" name="search_staff" value="{{ request('search_staff') }}">
                    <input type="hidden" name="search_product" value="{{ request('search_product') }}">

                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">

                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">



                    <div class="flex flex-wrap justify-center gap-3 mb-4">

                        @foreach(['created_at' => 'Date', 'staff_name' => 'ឈ្មោះ', 'product' => 'ផលិតផល', 'spend' => 'Spend', 'invoice_amount' => 'Invoice', 'messages' => 'Messages', 'new_id' => 'New ID', 'acc_test' => 'ACC Test'] as $val => $label)

                            <label class="flex items-center text-xs bg-white px-2 py-1 rounded border shadow-sm cursor-pointer">

                                <input type="checkbox" name="columns[]" value="{{ $val }}" checked class="mr-1 rounded text-blue-600"> {{ $label }}

                            </label>

                        @endforeach

                    </div>



                    <div class="flex justify-center gap-2">

                        <button type="button" onclick="submitExport('{{ route('export.excel') }}')" class="bg-green-600 text-white px-4 py-2 rounded font-bold text-xs hover:bg-green-700 transition">Excel 🟢</button>

                        <button type="button" onclick="submitExport('{{ route('export.pdf') }}')" class="bg-red-600 text-white px-4 py-2 rounded font-bold text-xs hover:bg-red-700 transition">PDF 🔴</button>

                    </div>

                </form>

            </div>



            {{-- ៦. Table Section (Responsive) --}}

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

                <div class="p-4 border-b bg-gray-50 flex justify-between items-center">

                    <h4 class="font-bold text-gray-700 italic text-sm md:text-base">📊 ប្រវត្តិការងារ</h4>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full text-left text-xs md:text-sm">

                        <thead class="bg-gray-100 border-b">

                            <tr>

                                <th class="px-2 md:px-4 py-3 font-bold text-gray-600">កាលបរិច្ឆេទ</th>

                                <th class="px-2 md:px-4 py-3 font-bold text-gray-600">ឈ្មោះ</th>

                                <th class="px-2 md:px-4 py-3 font-bold text-gray-600">ឈ្មោះក្បាល</th>

                                <th class="px-2 md:px-4 py-3 font-bold text-gray-600">Spend</th>

                                <th class="px-2 md:px-4 py-3 font-bold text-gray-600">Invoice</th>

                                <th class="px-2 md:px-4 py-3 font-bold text-gray-600">Messages</th>

                                <th class="px-2 md:px-4 py-3 font-bold text-gray-600">ACC Test</th>

                                <th class="px-2 md:px-4 py-3 font-bold text-gray-600">New ID</th>

                                <th class="px-2 md:px-4 py-3 font-bold text-red-500">$/Msg</th>

                                <th class="px-2 md:px-4 py-3 font-bold text-center">Action</th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse($reports as $row)

                                @php $cpm = $row->messages > 0 ? ($row->spend / $row->messages) : 0; @endphp

                                <tr class="hover:bg-gray-50 transition text-[11px] md:text-sm"> {{-- បន្ថែមការតម្រូវទំហំអក្សរតិចតួចលើ mobile --}}

                                    <td class="px-2 md:px-4 py-4 text-gray-400 text-[10px]">{{ $row->created_at->format('d/M H:i') }}</td>

                                    <td class="px-2 md:px-4 py-4 font-bold">{{ Str::limit($row->staff_name, 10) }}</td>

                                    <td class="px-2 md:px-4 py-4 font-bold text-blue-600">{{ $row->product }}</td>

                                    <td class="px-2 md:px-4 py-4 font-bold text-gray-800">${{ number_format($row->spend, 2) }}</td>

                                    <td class="px-2 md:px-4 py-4 font-bold text-green-600">${{ number_format($row->invoice_amount, 2) }}</td>

                                   

                                    {{-- លុប hidden md:table-cell ចេញ ដើម្បីឱ្យបង្ហាញលើ mobile --}}

                                    <td class="px-2 md:px-4 py-4 text-center">{{ $row->messages }}</td>

                                    <td class="px-2 md:px-4 py-4 text-center">{{ $row->acc_test}}</td>

                                    <td class="px-2 md:px-4 py-4 text-center">{{ $row->new_id }}</td>

                                   

                                    <td class="px-2 md:px-4 py-4 font-bold text-red-500">${{ number_format($cpm, 2) }}</td>

                                   

                                    <td class="px-2 md:px-4 py-4 text-center">

                                        @if(Auth::user()->role === 'admin' || Auth::id() === $row->user_id)

                                            <form action="{{ route('report.destroy', $row->id) }}" method="POST" onsubmit="return confirm('លុបទិន្នន័យនេះ?')">

                                                @csrf @method('DELETE')

                                                <button type="submit" class="text-gray-400 hover:text-red-500">🗑️</button>

                                            </form>

                                        @else

                                            <span class="text-gray-300 text-[10px]">🔒</span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr><td colspan="9" class="px-4 py-8 text-center text-gray-400 italic">មិនទាន់មានរបាយការណ៍នៅឡើយទេ។</td></tr>

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



    <script>

        function submitExport(url) {

            const exportForm = document.getElementById('exportForm');

            exportForm.action = url;

            exportForm.submit();

        }

    </script>

</x-app-layout>