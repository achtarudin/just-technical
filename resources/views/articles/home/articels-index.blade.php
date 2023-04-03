@extends('articles.layouts')


@section('content')
    <aside aria-label="Related articles" class="py-8 lg:py-24 bg-gray-50 dark:bg-gray-800">
        <div class="px-4 mx-auto max-w-screen-xl">
            <div class="grid gap-12 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($articles as $item)
                    <article class="max-w-xs">
                        <a href="#">
                            <img src="{{ $item->banner }}"
                                class="mb-5 rounded-lg" alt="Image 1">
                        </a>
                        <h2 class="mb-2 text-xl font-bold leading-tight text-gray-900 dark:text-white">
                            <a href="#">{{ $item->author->name }}</a>
                        </h2>
                        <p class="mb-4 font-light text-gray-500 dark:text-gray-400">{{ $item->title }}</p>
                        <a href="{{ route('articels.read', $item->id) }}"
                            class="inline-flex items-center font-medium underline underline-offset-4 text-primary-600 dark:text-primary-500 hover:no-underline">
                            Read More
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </aside>

    <div class="mx-5 my-5">
        {{ $articles->links() }}
    </div>
@endsection
