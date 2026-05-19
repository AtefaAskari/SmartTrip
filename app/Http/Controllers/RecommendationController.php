<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    public function index()
    {
        $myRecommendations = Auth::user()->recommendations()->latest()->take(5)->get();
        return view('recommendations.index', compact('myRecommendations'));
    }

    public function create()
    {
        return view('recommendations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'budget' => 'required|numeric|min:0',
            'country' => 'required|string|max:100',
            'interests' => 'required|string|max:500',
        ]);

        $suggestions = $this->generateSuggestionsWithImages($validated['country'], $validated['budget'], $validated['interests']);

        $recommendation = Recommendation::create([
            'user_id' => Auth::id(),
            'country' => $validated['country'],
            'budget' => $validated['budget'],
            'interests' => $validated['interests'],
            'suggestions' => $suggestions,
        ]);

        return redirect()->route('recommendations.show', $recommendation)
            ->with('success', 'Recommendations with images generated!');
    }

    public function show(Recommendation $recommendation)
    {
        if ($recommendation->user_id !== Auth::id()) abort(403);
        return view('recommendations.show', compact('recommendation'));
    }

    public function destroy(Recommendation $recommendation)
    {
        if ($recommendation->user_id !== Auth::id()) abort(403);
        $recommendation->delete();
        return redirect()->route('recommendations.index')->with('success', 'Recommendation deleted.');
    }

    private function generateSuggestionsWithImages($country, $budget, $interests)
    {
        $interestsArray = explode(',', $interests);
        $countrySlug = strtolower(str_replace(' ', '-', $country));
        
        // Image placeholder function – uses placekitten, but you can replace with real Unsplash URLs
        $image = function($keyword, $width=400, $height=300) {
            return "https://picsum.photos/id/" . rand(1, 200) . "/$width/$height";
            // Alternative with Unsplash (requires valid query):
            // return "https://source.unsplash.com/featured/{$width}x{$height}?{$keyword}";
        };

        // Destinations with images
        $destinations = $this->getDestinationsForCountry($country);
        $destinationItems = [];
        foreach ($destinations as $dest) {
            $destinationItems[] = [
                'name' => $dest,
                'image' => $image("city $dest", 400, 300),
                'description' => "Explore the beauty of $dest, $country."
            ];
        }

        // Hotels based on budget
        $hotelNames = $this->getHotelsForBudget($budget);
        $hotelItems = [];
        foreach ($hotelNames as $hotel) {
            $hotelItems[] = [
                'name' => $hotel,
                'image' => $image("hotel $hotel", 400, 300),
                'price' => $budget < 1000 ? '$80/night' : ($budget < 3000 ? '$150/night' : '$300/night'),
            ];
        }

        // Restaurants
        $restaurantNames = $this->getRestaurantsForBudget($budget);
        $restaurantItems = [];
        foreach ($restaurantNames as $rest) {
            $restaurantItems[] = [
                'name' => $rest,
                'image' => $image("restaurant $rest", 400, 300),
                'cuisine' => $budget < 1000 ? 'Local' : ($budget < 3000 ? 'International' : 'Fine Dining'),
            ];
        }

        // Activities
        $activities = $this->getActivitiesFromInterests($interestsArray);
        $activityItems = [];
        foreach ($activities as $act) {
            $activityItems[] = [
                'name' => $act,
                'image' => $image("activity $act", 400, 300),
                'duration' => rand(2, 6) . ' hours',
            ];
        }

        return [
            'destinations' => $destinationItems,
            'hotels' => $hotelItems,
            'restaurants' => $restaurantItems,
            'activities' => $activityItems,
        ];
    }

    // Helper methods (mock data)
    private function getDestinationsForCountry($country)
    {
        $map = [
            'Japan' => ['Tokyo', 'Kyoto', 'Osaka', 'Hokkaido'],
            'France' => ['Paris', 'Lyon', 'Nice', 'Bordeaux'],
            'Italy' => ['Rome', 'Florence', 'Venice', 'Milan'],
            'USA' => ['New York', 'Los Angeles', 'Chicago', 'Miami'],
            'Thailand' => ['Bangkok', 'Phuket', 'Chiang Mai', 'Krabi'],
        ];
        return $map[$country] ?? ['Capital City', 'Popular Tourist Spot', 'Coastal Town', 'Mountain Village'];
    }

    private function getHotelsForBudget($budget)
    {
        if ($budget < 1000) {
            return ['Budget Inn', 'Hostel World', 'Economy Stay', 'Backpacker Lodge'];
        } elseif ($budget < 3000) {
            return ['Comfort Suites', 'City Hotel', 'Business Lodge', 'Holiday Inn'];
        } else {
            return ['Luxury Resort', 'Grand Plaza', 'Boutique Hotel', 'Four Seasons'];
        }
    }

    private function getRestaurantsForBudget($budget)
    {
        if ($budget < 1000) {
            return ['Street Food Market', 'Local Eatery', 'Fast Food Chain', 'Food Court'];
        } elseif ($budget < 3000) {
            return ['Family Diner', 'Casual Bistro', 'Seafood Grill', 'Pizzeria'];
        } else {
            return ['Michelin Star', 'Fine Dining', 'Rooftop Restaurant', 'Chef’s Table'];
        }
    }

    private function getActivitiesFromInterests($interests)
    {
        $activities = [];
        foreach ($interests as $interest) {
            $interest = trim(strtolower($interest));
            if (str_contains($interest, 'culture')) $activities[] = 'Museum Tour';
            if (str_contains($interest, 'nature')) $activities[] = 'National Park Hike';
            if (str_contains($interest, 'adventure')) $activities[] = 'Zipline & Rafting';
            if (str_contains($interest, 'nightlife')) $activities[] = 'Pub Crawl / Club';
            if (str_contains($interest, 'shopping')) $activities[] = 'Local Markets';
            if (str_contains($interest, 'food')) $activities[] = 'Cooking Class';
        }
        if (empty($activities)) $activities = ['City Walking Tour', 'Historical Sites', 'Photography Walk'];
        return array_slice($activities, 0, 5);
    }
}