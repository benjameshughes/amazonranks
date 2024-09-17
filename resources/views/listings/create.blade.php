<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Amazon Listing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container">
                        <h1 class="text-3xl underline font-bold">Add New Amazon Listing</h1>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="flex flex-col space-y-4" action="{{ route('listings.store') }}" method="POST">
                            @csrf
                                <label for="url">Amazon Product URL</label>
                                <input type="url" class="form-control" id="url" name="url" required placeholder="https://www.amazon.com/dp/ASIN" value="{{ old('url') }}">
                                <small class="form-text text-muted">Enter the full URL of the Amazon product page.</small>

                            <button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white">Submit</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>