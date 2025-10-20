<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Track;
use App\Services\SpotifyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrackController extends Controller
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
                'track',
                $request->limit ?? 10
            );

            return response()->json([
                'data' => $results['tracks']['items'] ?? [],
                'meta' => [
                    'total' => $results['tracks']['total'] ?? 0,
                    'limit' => $request->limit ?? 10
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to search tracks',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $track = $this->spotifyService->getTrack($id);

            return response()->json([
                'data' => $track
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Track not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function recommendations(Request $request): JsonResponse
    {
        $request->validate([
            'seed_artists' => 'sometimes|string',
            'seed_tracks' => 'sometimes|string',
            'limit' => 'sometimes|integer|min:1|max:100'
        ]);

        try {
            $params = array_filter([
                'seed_artists' => $request->seed_artists,
                'seed_tracks' => $request->seed_tracks,
                'limit' => $request->limit ?? 20
            ]);

            $recommendations = $this->spotifyService->getRecommendations($params);

            return response()->json([
                'data' => $recommendations['tracks'] ?? []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get recommendations',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeLocal(Request $request): JsonResponse
    {
        $request->validate([
            'spotify_id' => 'required|string'
        ]);

        try {
            $trackData = $this->spotifyService->getTrack($request->spotify_id);

            $track = Track::create([
                'spotify_id' => $trackData['id'],
                'name' => $trackData['name'],
                'duration_ms' => $trackData['duration_ms'],
                'explicit' => $trackData['explicit'],
                'popularity' => $trackData['popularity'],
                'preview_url' => $trackData['preview_url'],
                'external_url' => $trackData['external_urls']['spotify'],
                'artists' => $trackData['artists'],
                'album' => $trackData['album']
            ]);

            return response()->json([
                'message' => 'Track saved locally',
                'data' => $track
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to save track',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyLocal(string $id): JsonResponse
    {
        $track = Track::where('spotify_id', $id)->first();

        if (!$track) {
            return response()->json([
                'error' => 'Track not found in local database'
            ], 404);
        }

        $track->delete();

        return response()->json([
            'message' => 'Track removed from local database'
        ]);
    }
}
