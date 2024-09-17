<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Amazon Listings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Add listings button to listings.create --}}
                    <a href="{{ route('listings.create') }}" class="mt-6 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Add Listing
                    </a>
                </div>

                <div class="p-6 grid grid-cols-3 gap-4 w-full">
                    @forelse ($listings as $listing)
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-4 width-full">
                                <div class="flex items-center">
                                    <div class="">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $listing->title }}
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500">
                                            <a href="{{ $listing->url }}" target="_blank" class="no-underline hover:underline">
                                                {{ $listing->url }}
                                            </a>
                                        </div>
                                    <div class="">
                                        <div class="text-sm font-medium text-gray-900">
                                            Current Rank: {{ $listing->current_rank }}
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500">
                                            {{ $listing->asin }}
                                        </div>
                                    </div>
                                        <div class="">
                                            <form action="{{ route('listings.destroy', $listing) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                        <!-- Button to scrape listing using the job -->
                                        <div class="">
                                        <form action="{{ route('listings.scrape', $listing) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Scrape
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                            @empty
                                No listings found :(
                        @endforelse


            </div>
        </div>
    </div>
</x-app-layout>