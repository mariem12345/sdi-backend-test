<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArtistController extends Controller
{
    /**
     * Display a listing of artists.
     *
     * @queryParam search string Search artists by name. Example: Pitbull
     * @queryParam genre string Filter by genre. Example: pop
     * @queryParam popular boolean Order by popularity. Example: true
     * @queryParam page integer Page number for pagination. Example: 1
     * @queryParam per_page integer Number of items per page. Example: 10
     *
     * @response array{"data": Artist[], "message": string, "pagination": array}
     */
    public function index(Request $request): JsonResponse
    {
        $query = Artist::query();

        // Search by name
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Filter by genre
        if ($request->has('genre')) {
            $query->byGenre($request->genre);
        }

        // Order by popularity
        if ($request->boolean('popular')) {
            $query->popular();
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $artists = $query->paginate($perPage);

        return response()->json([
            'data' => $artists->items(),
            'message' => 'Artists retrieved successfully',
            'pagination' => [
                'current_page' => $artists->currentPage(),
                'per_page' => $artists->perPage(),
                'total' => $artists->total(),
                'last_page' => $artists->lastPage(),
            ]
        ]);
    }

    /**
     * Store a newly created artist.
     *
     * @bodyParam spotify_id string required The Spotify ID of the artist. Example: 0TnOYISbd1XYRBk9myaseg
     * @bodyParam name string required The name of the artist. Example: Pitbull
     * @bodyParam genres array required The genres of the artist. Example: ["pop", "latin"]
     * @bodyParam popularity integer The popularity of the artist (0-100). Example: 85
     * @bodyParam followers integer The number of followers. Example: 15000000
     * @bodyParam image_url string The URL of the artist's image. Example: https://i.scdn.co/image/ab6761610000e5eb...
     * @bodyParam spotify_url string The Spotify URL of the artist. Example: https://open.spotify.com/artist/0TnOYISbd1XYRBk9myaseg
     *
     * @response 201 {"data": Artist, "message": string}
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'spotify_id' => 'required|string|unique:artists,spotify_id',
            'name' => 'required|string|max:255',
            'genres' => 'required|array',
            'genres.*' => 'string|max:100',
            'popularity' => 'nullable|integer|min:0|max:100',
            'followers' => 'nullable|integer|min:0',
            'image_url' => 'nullable|url|max:500',
            'spotify_url' => 'nullable|url|max:500',
        ]);

        $artist = Artist::create($validated);

        return response()->json([
            'data' => $artist,
            'message' => 'Artist created successfully'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified artist.
     *
     * @urlParam artist required The ID of the artist. Example: 1
     *
     * @response {"data": Artist, "message": string}
     * @response 404 {"message": "Artist not found"}
     */
    public function show(Artist $artist): JsonResponse
    {
        return response()->json([
            'data' => $artist,
            'message' => 'Artist retrieved successfully'
        ]);
    }

    /**
     * Update the specified artist.
     *
     * @urlParam artist required The ID of the artist. Example: 1
     * @bodyParam name string The name of the artist. Example: Pitbull Updated
     * @bodyParam genres array The genres of the artist. Example: ["pop", "latin", "hip-hop"]
     * @bodyParam popularity integer The popularity of the artist (0-100). Example: 90
     * @bodyParam followers integer The number of followers. Example: 16000000
     * @bodyParam image_url string The URL of the artist's image.
     * @bodyParam spotify_url string The Spotify URL of the artist.
     *
     * @response {"data": Artist, "message": string}
     * @response 404 {"message": "Artist not found"}
     */
    public function update(Request $request, Artist $artist): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'genres' => 'sometimes|array',
            'genres.*' => 'string|max:100',
            'popularity' => 'nullable|integer|min:0|max:100',
            'followers' => 'nullable|integer|min:0',
            'image_url' => 'nullable|url|max:500',
            'spotify_url' => 'nullable|url|max:500',
        ]);

        $artist->update($validated);

        return response()->json([
            'data' => $artist->fresh(),
            'message' => 'Artist updated successfully'
        ]);
    }

    /**
     * Remove the specified artist.
     *
     * @urlParam artist required The ID of the artist. Example: 1
     *
     * @response 200 {"message": "Artist deleted successfully"}
     * @response 404 {"message": "Artist not found"}
     */
    public function destroy(Artist $artist): JsonResponse
    {
        $artist->delete();

        return response()->json([
            'message' => 'Artist deleted successfully'
        ]);
    }

    /**
     * Get artists by popularity range.
     *
     * @queryParam min integer Minimum popularity (0-100). Example: 80
     * @queryParam max integer Maximum popularity (0-100). Example: 100
     *
     * @response {"data": Artist[], "message": string}
     */
    public function byPopularity(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'min' => 'required|integer|min:0|max:100',
            'max' => 'required|integer|min:0|max:100',
        ]);

        $artists = Artist::whereBetween('popularity', [$validated['min'], $validated['max']])
            ->orderBy('popularity', 'desc')
            ->get();

        return response()->json([
            'data' => $artists,
            'message' => 'Artists filtered by popularity successfully'
        ]);
    }
}
