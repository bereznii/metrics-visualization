rollback:
	php artisan migrate:rollback --step=1

remigrate:
	make rollback && php artisan migrate
