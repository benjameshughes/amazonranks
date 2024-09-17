<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAmazonListingRequest;
use App\Http\Requests\UpdateAmazonListingRequest;
use App\Jobs\ScrapeAmazonListing;
use App\Models\AmazonListing;
use Illuminate\Http\Request;

class AmazonListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listings = AmazonListing::all();
        return view('listings.index', compact('listings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('listings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url|unique:amazon_listings,url'
        ]);

        $listing = AmazonListing::create([
            'url' => $request->url,
            'title' => 'Updating the information', // We'll update this when we scrape
            // Explode the url to get the ASIN
            'asin' => explode('/', $request->url)[4],
            'current_rank' => 0, // We'll update this when we scrape
        ]);

        // Dispatch a job to scrape this new listing immediately
        ScrapeAmazonListing::dispatch($listing)->delay(now()->addSeconds(10));

        return redirect()->route('listings.index')->with('success', 'Listing added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(AmazonListing $amazonListing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AmazonListing $amazonListing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAmazonListingRequest $request, AmazonListing $amazonListing, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AmazonListing $listing)
    {
        // Get the id from the request
        $listing->delete();

        return redirect()->route('listings.index')->with('success', 'Listing deleted successfully');
    }

    /**
     * Scrape the specified listing.
     */
    public function scrape(AmazonListing $listing)
    {
        // Get the id from the request
        $listing->update([
            'current_rank' => 0,
        ]);

        // Dispatch a job to scrape this listing
        ScrapeAmazonListing::dispatch($listing);

        return redirect()->route('listings.index')->with('success', 'Listing scraped successfully');
    }
}
