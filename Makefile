build:
	composer install --ignore-platform-reqs && ./vendor/bin/sail up -d && ./vendor/bin/sail artisan migrate

up:
	./vendor/bin/sail up -d

up-full:
	./vendor/bin/sail up -d && sleep 30 && ./vendor/bin/sail artisan migrate && ./vendor/bin/sail artisan queue:consume records_analyzed

migrate:
	./vendor/bin/sail artisan migrate

up-consumer:
	./vendor/bin/sail artisan queue:consume records_analyzed

test:
	./vendor/bin/sail artisan test


shell:
	docker exec -it laravel-backend-fabric-laravel.test-1 /bin/bash
