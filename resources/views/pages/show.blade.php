@extends('layouts.app')

@section('title', ($page->meta_title ?? $page->title) . ' - PickKart')
@section('meta_description', $page->meta_description ?? Str::limit(strip_tags($page->content), 160))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
        <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-800 font-medium">{{ $page->title }}</span>
    </nav>

    {{-- Page Title --}}
    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8">{{ $page->title }}</h1>

    {{-- Page Content --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-10">
        <div class="prose prose-sm sm:prose lg:prose-lg max-w-none
                    prose-headings:text-gray-900 prose-headings:font-bold
                    prose-p:text-gray-600 prose-p:leading-relaxed
                    prose-a:text-primary-600 prose-a:no-underline hover:prose-a:underline
                    prose-strong:text-gray-800
                    prose-ul:text-gray-600 prose-ol:text-gray-600
                    prose-li:text-gray-600
                    prose-blockquote:border-primary-500 prose-blockquote:text-gray-600
                    prose-img:rounded-xl prose-img:shadow-sm
                    prose-table:text-sm
                    prose-th:bg-gray-50 prose-th:text-gray-700
                    prose-td:text-gray-600">
            {!! $page->content !!}
        </div>
    </div>

</div>
@endsection
