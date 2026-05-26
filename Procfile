web: cd backend && php artisan serve --host=0.0.0.0 --port=$PORT
worker: cd backend && php artisan queue:work --sleep=3 --tries=3
scheduler: cd backend && php artisan schedule:work
