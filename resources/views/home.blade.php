@extends('layouts.app')

@section('title', 'Home - Lost & Found')

@section('content')
<div class="max-w-4xl mx-auto text-center py-12">
    <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">Lose it? We'll find it.</h1>
    <p class="text-lg text-gray-600 mb-12">Platform terpercaya untuk melaporkan dan menemukan barang hilang.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
        <!-- Report Item -->
        <a href="{{ route('items.lost.create') }}" class="group bg-white rounded-2xl shadow-sm border border-gray-200 p-8 hover:shadow-md transition-all duration-200 flex flex-col items-center">
            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-blue-100 transition-colors">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Report Item</h3>
            <p class="text-sm text-gray-500 mt-2">Laporkan barang hilang</p>
        </a>

        <!-- Find Something -->
        <a href="{{ route('items.found.index') }}" class="group bg-white rounded-2xl shadow-sm border border-gray-200 p-8 hover:shadow-md transition-all duration-200 flex flex-col items-center">
            <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-green-100 transition-colors">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Find Something</h3>
            <p class="text-sm text-gray-500 mt-2">Cari barang temuan</p>
        </a>

        <!-- Connect -->
        <a href="{{ route('chat.index') }}" class="group bg-white rounded-2xl shadow-sm border border-gray-200 p-8 hover:shadow-md transition-all duration-200 flex flex-col items-center">
            <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-purple-100 transition-colors">
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Connect</h3>
            <p class="text-sm text-gray-500 mt-2">Hubungi penemu</p>
        </a>
    </div>
</div>
@endsection