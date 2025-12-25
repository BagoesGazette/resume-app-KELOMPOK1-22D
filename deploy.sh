#!/bin/bash

set -e

echo "=========================================="
echo "🚀 Resume App - Docker Deployment"
echo "=========================================="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if .env exists
if [ ! -f .env ]; then
    echo -e "${YELLOW}⚠️  .env file not found${NC}"
    echo "Creating from .env.production.example..."
    cp .env.production.example .env
    echo -e "${GREEN}✅ .env file created${NC}"
    echo ""
    echo -e "${RED}⚠️  IMPORTANT: Edit .env file and set:${NC}"
    echo "   - APP_KEY (run: php artisan key:generate)"
    echo "   - DB_PASSWORD"
    echo "   - REDIS_PASSWORD"
    echo "   - Other credentials"
    echo ""
    read -p "Press Enter after editing .env file..."
fi

# Check APP_KEY
if grep -q "APP_KEY=$" .env || grep -q "APP_KEY=\"\"" .env; then
    echo -e "${RED}❌ APP_KEY is not set!${NC}"
    echo "Run: php artisan key:generate"
    exit 1
fi

echo "1️⃣  Building Docker images..."
docker compose build --no-cache

echo ""
echo "2️⃣  Starting services..."
docker compose up -d

echo ""
echo "3️⃣  Waiting for database to be ready..."
sleep 10

echo ""
echo "4️⃣  Running migrations..."
docker compose exec -T app php artisan migrate --force
echo ""
echo "🌱 Checking database state..."
USER_COUNT=$(docker compose exec -T app php artisan tinker --execute="echo \DB::table('users')->count();")
USER_COUNT=$(echo "$USER_COUNT" | tr -d '\r\n')

if [ "$USER_COUNT" -eq "0" ]; then
    echo "📭 Database appears empty (0 users). Running seed..."
    docker compose exec -T app php artisan db:seed --force
    echo "✅ Database seeded successfully!"
else
    echo "📦 Database already contains data ($USER_COUNT users). Skipping seed."
fi

echo ""
echo "5️⃣  Creating storage symlink..."
docker compose exec -T app php artisan storage:link
echo "Fixing permissions..."
docker compose exec -T app chown -R www-data:www-data /var/www/html/storage

echo ""
echo "6️⃣  Optimizing application..."
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache

echo ""
echo "7️⃣  Setting up API keys..."
echo ""
read -p "Do you want to store Gemini API key now? (y/n) " -n 1 -r
echo ""
if [[ $REPLY =~ ^[Yy]$ ]]; then
    read -sp "Enter Gemini API key: " GEMINI_KEY
    echo ""
    docker compose exec -T app php artisan tinker << EOF
\$apiKey = '$GEMINI_KEY';
\$encrypted = Crypt::encryptString(\$apiKey);
DB::table('api_keys')->updateOrInsert(
    ['service' => 'gemini'],
    [
        'encrypted_key' => \$encrypted,
        'is_active' => true,
        'environment' => 'production',
        'updated_at' => now(),
        'updated_by' => 'deployment',
        'created_at' => now(),
    ]
);
echo "✅ API key stored\n";
EOF
fi

echo ""
echo "=========================================="
echo -e "${GREEN}✅ Deployment Complete!${NC}"
echo "=========================================="
echo ""
echo "📊 Service Status:"
docker compose ps

echo ""
echo "🌐 Application URLs:"
echo "   App: http://localhost:8000"
echo "   DB:  localhost:5432"
echo ""
echo "📝 Useful Commands:"
echo "   View logs:        docker compose logs -f app"
echo "   View all logs:    docker compose logs -f"
echo "   Stop services:    docker compose down"
echo "   Restart:          docker compose restart"
echo "   Shell access:     docker compose exec app sh"
echo "   Run artisan:      docker compose exec app php artisan [command]"
echo ""
echo "🔐 Security Checklist:"
echo "   ☐ Change default passwords in .env"
echo "   ☐ Set strong APP_KEY"
echo "   ☐ Configure firewall"
echo "   ☐ Set up SSL/TLS (for production)"
echo "   ☐ Regular backups"
echo ""