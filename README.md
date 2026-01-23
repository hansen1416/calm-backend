## Start and stop containers
./vendor/bin/sail up -d

./vendor/bin/sail down

## Stop and remove data (DB data)
./vendor/bin/sail down -v



## create tables

./vendor/bin/sail exec laravel.test bash -lc 'cat database/migrations/tables.sql | mysql -h mysql -usail -ppassword laravel'

## run qeury

./vendor/bin/sail exec mysql mysql -usail -ppassword laravel -e "SHOW TABLES;"

./vendor/bin/sail exec mysql mysql -usail -ppassword laravel -e "SHOW TABLES;DESCRIBE contacts;DESCRIBE tags;DESCRIBE contact_tags;"

./vendor/bin/sail artisan tinker --execute="dump(DB::selectOne('select database() as db, @@hostname as host, @@port as port')); dump(Schema::hasTable('contacts'));"




