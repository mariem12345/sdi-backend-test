#!/bin/bash

echo "🔧 Reparando configuración del proyecto Spotify API..."

# Detener y eliminar contenedores
echo "🛑 Deteniendo contenedores..."
docker-compose down

# Eliminar volúmenes problemáticos si existen
echo "🧹 Limpiando volúmenes..."
docker volume rm -f spotify-api_spotify_mysql_data 2>/dev/null || true

# Reconstruir con cache limpio
echo "🐳 Reconstruyendo contenedores..."
docker-compose build --no-cache

# Levantar servicios
echo "🚀 Iniciando servicios..."
docker-compose up -d

# Esperar más tiempo para MySQL
echo "⏳ Esperando que MySQL esté listo (esto puede tomar hasta 60 segundos)..."
sleep 60

# Verificar estado de contenedores
echo "🔍 Verificando estado de contenedores..."
docker-compose ps

# Verificar conexión a MySQL
echo "🔌 Probando conexión a MySQL..."
docker-compose exec mysql mysql -u spotify_user -psecret -e "SHOW DATABASES;" || {
    echo "❌ No se pudo conectar a MySQL, esperando más tiempo..."
    sleep 30
    docker-compose exec mysql mysql -u spotify_user -psecret -e "SHOW DATABASES;" || {
        echo "❌ Error crítico con MySQL"
        exit 1
    }
}

echo "✅ MySQL está funcionando correctamente"

# Ejecutar migraciones
echo "🗃️ Ejecutando migraciones..."
docker-compose exec app php artisan migrate --force

echo "🎉 ¡Reparación completada!"
echo "🌐 API disponible en: http://localhost:8000"
