<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ផ្ទាំងគ្រប់គ្រងសង្ខេប') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <div class="bg-white p-6 rounded-xl shadow-sm border border-blue-100 flex items-center">
                    <div class="p-3 bg-blue-50 rounded-full mr-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">របាយការណ៍សរុប</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Report::count() }}</p>
                    </div>
                </div>

                @if(auth()->user()->role == 'admin')
                <div class="bg-white p-6 rounded-xl shadow-sm border border-green-100 flex items-center">
                    <div class="p-3 bg-green-50 rounded-full mr-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">បុគ្គលិកសរុប</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                    </div>
                </div>
                @endif

                <a href="{{ route('reports.index') }}" class="bg-white p-6 rounded-xl shadow-sm border border-yellow-100 flex items-center hover:bg-yellow-50 transition">
                    <div class="p-3 bg-yellow-50 rounded-full mr-4">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">ពិនិត្យមើល</p>
                        <p class="text-xl font-bold text-gray-900">បញ្ជីរបាយការណ៍</p>
                    </div>
                </a>

            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-8 text-center">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">សួស្តី! {{ auth()->user()->name }}</h1>
                <p class="text-gray-600">ស្វាគមន៍មកកាន់ប្រព័ន្ធគ្រប់គ្រងការងាររបស់អ្នក។ អ្នកកំពុងប្រើប្រាស់ក្នុងនាមជា <span class="text-blue-600 font-bold uppercase">{{ auth()->user()->role }}</span>។</p>
            </div>

        </div>
    </div>

    
</x-app-layout>