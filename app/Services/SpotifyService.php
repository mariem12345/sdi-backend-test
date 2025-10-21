<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SpotifyService
{
    private string $baseUrl = 'https://api.spotify.com/v1';
    private string $token;

    public function __construct()
    {
        $this->token = $this->getAccessToken();
    }

    public function getAccessToken(): string
    {
        return Cache::remember('spotify_access_token', 3600, function () {
            $response = Http::asForm()->withHeaders([
                'Authorization' => 'Basic ' . base64_encode(
                        config('spotify.client_id') . ':' . config('spotify.client_secret')
                    )
            ])->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'client_credentials'
            ]);

            if ($response->failed()) {
                Log::error('Failed to get Spotify access token', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('Failed to authenticate with Spotify API');
            }

            return $response->json()['access_token'];
        });
    }

    private function makeRequest(string $endpoint, array $params = [])
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->get($this->baseUrl . $endpoint, $params);

        if ($response->failed()) {
            Log::error('Spotify API request failed', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            throw new \Exception('Spotify API request failed: ' . $response->body());
        }

        return $response->json();
    }

    public function search(string $query, string $type = 'track', int $limit = 10)
    {
        return $this->makeRequest('/search', [
            'q' => $query,
            'type' => $type,
            'limit' => $limit
        ]);
    }

    public function getArtist(string $id)
    {
        return $this->makeRequest("/artists/{$id}");
    }

    public function getArtistTopTracks(string $id, string $country = 'US')
    {
        return $this->makeRequest("/artists/{$id}/top-tracks", [
            'country' => $country
        ]);
    }

    public function getTrack(string $id)
    {
        return $this->makeRequest("/tracks/{$id}");
    }

    public function getRecommendations(array $params)
    {
        return $this->makeRequest("/recommendations", $params);
    }
}
