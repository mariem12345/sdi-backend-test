# 🎵 Spotify API - Laravel REST API

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql" alt="MySQL 8.0">
  <img src="https://img.shields.io/badge/Docker-Enabled-2496ED?style=for-the-badge&logo=docker" alt="Docker">
  <img src="https://img.shields.io/badge/Redis-DC382D?style=for-the-badge&logo=redis" alt="Redis">
</p>

## 🎯 About Spotify API

A complete RESTful API built with **Laravel 11** that integrates with the **Spotify Web API** to provide artist and track information. Features full CRUD operations, search, recommendations, and automatic API documentation.

## ✨ Features

- ✅ **Complete REST API** for artists and tracks
- ✅ **Spotify Web API Integration** with real-time data
- ✅ **Automatic API Documentation** with Scramble
- ✅ **Dockerized Environment** for easy setup
- ✅ **Full CRUD Operations** with local storage
- ✅ **Advanced Search & Filtering**
- ✅ **Recommendations System**
- ✅ **Redis Caching** for performance
- ✅ **Comprehensive Error Handling**
- ✅ **PHPUnit Tests** with database testing
- ✅ **PHP 8.2+** with modern features

## 🛠️ Tech Stack

- **Backend**: Laravel 11, PHP 8.2
- **Database**: MySQL 8.0, Redis
- **Cache**: Redis
- **Documentation**: Scramble
- **Containerization**: Docker & Docker Compose
- **Testing**: PHPUnit, HTTP Mocking

## 📋 Requirements

- Docker
- Docker Compose
- Spotify Developer Account (for API credentials)

## 🚀 Quick Installation

### 1. Clone and Setup

```bash
# Clone the repository
git clone <repository-url>
cd spotify-api

# Run the automatic setup script
chmod +x crear-setup.sh
./crear-setup.sh
2. Manual Installation
bash
# 1. Create project structure
mkdir spotify-api && cd spotify-api

# 2. Start Docker containers
docker-compose up -d --build

# 3. Install Laravel and dependencies
docker-compose exec app composer create-project laravel/laravel . --prefer-dist
docker-compose exec app composer require dedoc/scramble guzzlehttp/guzzle

# 4. Configure environment
cp .env.docker .env
docker-compose exec app php artisan key:generate

# 5. Run migrations
docker-compose exec app php artisan migrate

# 6. Generate documentation
docker-compose exec app php artisan scramble:generate
3. Configure Spotify API
Get Spotify Credentials:

Visit Spotify Developer Dashboard

Create a new app

Copy Client ID and Client Secret

Update Environment:

bash
# Edit .env file
SPOTIFY_CLIENT_ID=your_spotify_client_id
SPOTIFY_CLIENT_SECRET=your_spotify_client_secret
Restart Application:

bash
docker-compose restart app
🔐 HTTPS Setup with ngrok (Required for Spotify Callback)
Important: Spotify requires HTTPS for callback URLs. Use ngrok to create a secure tunnel:

Install and Configure ngrok
bash
# Install ngrok (macOS with Homebrew)
brew install ngrok/ngrok/ngrok

# Or download from: https://ngrok.com/download

# Create free account and get authtoken
ngrok config add-authtoken YOUR_AUTH_TOKEN
Start ngrok and Configure Spotify
bash
# Start ngrok tunnel
ngrok http 8000

# This will provide a URL like: https://abc1-123-45-67-89.ngrok-free.app
Update Spotify Developer Dashboard
Go to your Spotify App Settings

Add the ngrok URL as Redirect URI:

text
https://your-subdomain.ngrok-free.app/auth/callback
Update your .env file:

env
SPOTIFY_REDIRECT_URI=https://your-subdomain.ngrok-free.app/auth/callback
APP_URL=https://your-subdomain.ngrok-free.app
Alternative: Script for Automatic ngrok Setup
bash
# Run the ngrok setup script
chmod +x setup-ngrok.sh
./setup-ngrok.sh
🌐 Access Points
API: http://localhost:8000

HTTPS API (via ngrok): https://your-subdomain.ngrok-free.app

Documentation: http://localhost:8000/docs

PHPMyAdmin: http://localhost:8080

📚 API Documentation
Interactive Documentation
Access automatic API documentation at: http://localhost:8000/docs

🎵 Artists Endpoints
Method	Endpoint	Description	Parameters
GET	/api/artists	Search artists	query, limit
GET	/api/artists/{id}	Get artist by Spotify ID	-
GET	/api/artists/{id}/top-tracks	Get artist's top tracks	country
POST	/api/artists/local	Save artist locally	spotify_id
DELETE	/api/artists/local/{id}	Remove artist from local DB	-
🎶 Tracks Endpoints
Method	Endpoint	Description	Parameters
GET	/api/tracks	Search tracks	query, limit
GET	/api/tracks/{id}	Get track by Spotify ID	-
GET	/api/tracks/recommendations	Get track recommendations	seed_artists, seed_tracks, limit
POST	/api/tracks/local	Save track locally	spotify_id
DELETE	/api/tracks/local/{id}	Remove track from local DB	-
💡 Usage Examples
Search Artists
bash
curl "http://localhost:8000/api/artists?query=metallica&limit=5"
Get Artist Details
bash
curl "http://localhost:8000/api/artists/0TnOYISbd1XYRBk9myaseg"
Get Top Tracks
bash
curl "http://localhost:8000/api/artists/0TnOYISbd1XYRBk9myaseg/top-tracks?country=US"
Search Tracks
bash
curl "http://localhost:8000/api/tracks?query=bohemian+rhapsody&limit=5"
Get Recommendations
bash
curl "http://localhost:8000/api/tracks/recommendations?seed_artists=0TnOYISbd1XYRBk9myaseg&limit=10"
Save Artist Locally
bash
curl -X POST "http://localhost:8000/api/artists/local" \
  -H "Content-Type: application/json" \
  -d '{"spotify_id": "0TnOYISbd1XYRBk9myaseg"}'

Run All Tests
bash
docker-compose exec app php artisan test
