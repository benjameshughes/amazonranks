<?php
// app/Jobs/ScrapeAmazonListing.php
namespace App\Jobs;

use App\Models\AmazonListing; // Make sure this import is correct
use App\Models\RankHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScrapeAmazonListing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $listing; // Change to AmazonListing type

    const FALLBACK_RANK = 999999;

    public function __construct($listing) // Change to AmazonListing type
    {
        $this->listing = $listing;
    }

    public function handle(): void
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36',
        ])->get($this->listing->url);

        if ($response->successful()) {
            $html = $response->body();
            $title = $this->extractTitle($html);
            $rank = $this->extractRank($html);

            $this->listing->update([
                'title' => $title ?? 'Unknown Title',
                'current_rank' => $rank ?? self::FALLBACK_RANK,
            ]);

            RankHistory::create([
                'amazon_listing_id' => $this->listing->id,
                'rank' => $this->listing->current_rank,
                'date' => now()->toDateString(),
            ]);

            // Schedule the next scrape
            dispatch(new self($this->listing))->delay(now()->addSeconds(10));

            Log::info("Updated listing {$this->listing->id}. Title: $title, Rank: $rank");
        } else {
            Log::error("Failed to fetch listing {$this->listing->id}. Status: {$response->status()}");
        }
    }

    protected function extractRank($html)
    {
        $patterns = [
            '/Best Sellers Rank.*?<span>\s*#?([0-9,]+)/s',
            '/Best Sellers Rank.*?<span>\s*#?([0-9,]+)/s',
            '/<span class="a-size-small a-color-secondary">\s*#([0-9,]+)/s',
            '/zg_hrsr_rank">\s*#([0-9,]+)/s',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $matches)) {
                return (int) str_replace(',', '', $matches[1]);
            }
        }

        return self::FALLBACK_RANK;
    }

    protected function extractTitle($html)
    {
        if (!preg_match('/id="productTitle"[^>]*>(.*?)<\/span>/s', $html, $titleMatches)) {
            Log::error("Failed to extract title for listing {$this->listing->id}");
            return 'Unknown Title';
        }

        $title = trim($titleMatches[1] ?? '');
        return $title;
    }
}