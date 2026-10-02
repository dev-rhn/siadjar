#!/usr/bin/env bash
#
# Deploy script for SIADJAR (Laravel 12 + Filament 4).
#
# Usage (on the server, from the project directory):
#   ./deploy.sh            # deploy branch "main"
#   ./deploy.sh staging    # deploy another branch
#
# Also works for the first deploy: clone the repo, create .env, then run it.
#
# Run as the user that owns the project files (member of the www-data group).

set -euo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BRANCH="${1:-main}"
PHP="${PHP:-php}"
WEB_GROUP="${WEB_GROUP:-www-data}"

cd "$APP_DIR"

log() {
    printf '\n\033[1;34m==> %s\033[0m\n' "$1"
}

# Bring the app back up if any step fails, so a broken deploy does not leave
# the site stuck in maintenance mode.
on_error() {
    printf '\n\033[1;31m!! Deploy failed on line %s. Bringing app back up.\033[0m\n' "$1"
    "$PHP" artisan up || true
}
trap 'on_error $LINENO' ERR

if [ ! -f .env ]; then
    echo "Missing .env file. Copy .env.example to .env and configure it first."
    exit 1
fi

# On the very first deploy there is no vendor/ yet, so artisan cannot run.
if [ -f vendor/autoload.php ]; then
    log "Entering maintenance mode"
    "$PHP" artisan down --retry=60 || true
fi

log "Pulling latest code ($BRANCH)"
git fetch origin "$BRANCH"
git reset --hard "origin/$BRANCH"

log "Installing PHP dependencies"
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

if ! grep -qE '^APP_KEY=.+' .env; then
    log "Generating application key (first deploy)"
    "$PHP" artisan key:generate --force
fi

log "Building frontend assets"
npm ci --no-audit --no-fund
npm run build

log "Running migrations"
"$PHP" artisan migrate --force

log "Linking storage"
if [ ! -L public/storage ]; then
    "$PHP" artisan storage:link
fi

log "Caching config, routes, views and Filament components"
"$PHP" artisan optimize:clear
"$PHP" artisan optimize
"$PHP" artisan filament:optimize

log "Fixing permissions"
chgrp -R "$WEB_GROUP" storage bootstrap/cache 2>/dev/null || true
chmod -R ug+rwX storage bootstrap/cache

log "Restarting queue workers"
"$PHP" artisan queue:restart

log "Leaving maintenance mode"
"$PHP" artisan up

trap - ERR
log "Deploy finished"
