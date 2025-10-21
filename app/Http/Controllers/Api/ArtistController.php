<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Services\SpotifyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    public function __construct(private SpotifyService $spotifyService) {}

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:1',
            'limit' => 'sometimes|integer|min:1|max:50'
        ]);

        try {
            $results = $this->spotifyService->search(
                $request->query,
                'artist',
                $request->limit ?? 10
            );

            return response()->json([
                'data' => $results['artists']['items'] ?? [],
                'meta' => [
                    'total' => $results['artists']['total'] ?? 0,
                    'limit' => $request->limit ?? 10
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to search artists',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $artist = $this->spotifyService->getArtist($id);

            return response()->json([
                'data' => $artist
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Artist not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function topTracks(string $id, Request $request): JsonResponse
    {
        $request->validate([
            'country' => 'sometimes|string|size:2'
        ]);

        try {
            $tracks = $this->spotifyService->getArtistTopTracks(
                $id,
                $request->country ?? 'US'
            );

            return response()->json([
                'data' => $tracks['tracks'] ?? []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get top tracks',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeLocal(Request $request): JsonResponse
    {
        $request->validate([
            'spotify_id' => 'required|string',
            'name' => 'required|string'
        ]);

        try {
            $artistData = $this->spotifyService->getArtist($request->spotify_id);

            $artist = Artist::create([
                'spotify_id' => $artistData['id'],
                'name' => $artistData['name'],
                'genres' => $artistData['genres'],
                'popularity' => $artistData['popularity'],
                'followers' => $artistData['followers']['total'],
                'external_url' => $artistData['external_urls']['spotify'],
                'images' => $artistData['images']
            ]);

            return response()->json([
                'message' => 'Artist saved locally',
                'data' => $artist
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to save artist',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyLocal(string $id): JsonResponse
    {
        $artist = Artist::where('spotify_id', $id)->first();

        if (!$artist) {
            return response()->json([
                'error' => 'Artist not found in local database'
            ], 404);
        }

        $artist->delete();

        return response()->json([
            'message' => 'Artist removed from local database'
        ]);
    }
}
