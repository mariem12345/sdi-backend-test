#!/bin/bash

echo "🔐 Configurando HTTPS con ngrok..."

# Iniciar ngrok en segundo plano
ngrok http 8000 &

# Esperar a que ngrok se inicie
sleep 5

# Obtener la URL HTTPS de ngrok
NGROK_URL=$(curl -s http://localhost:4040/api/tunnels | jq -r '.tunnels[0].public_url')

if [ "$NGROK_URL" != "null" ] && [ ! -z "$NGROK_URL" ]; then
    echo "✅ URL HTTPS obtenida: $NGROK_URL"

    # Actualizar el .env con la nueva URL
    sed -i.bak -e "s|SPOTIFY_REDIRECT_URI=.*|SPOTIFY_REDIRECT_URI=$NGROK_URL/auth/callback|" .env

    echo "🔄 Redirect URI actualizado en .env"
    echo "📝 Actualiza esta URL en tu Spotify Developer Dashboard:"
    echo "   $NGROK_URL/auth/callback"
else
    echo "❌ No se pudo obtener la URL de ngrok"
    echo "💡 Asegúrate de que ngrok esté instalado y funcionando"
fi
