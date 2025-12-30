`
./vendor/bin/sail up -d

./vendor/bin/sail down

./vendor/bin/sail down -v
`



./vendor/bin/sail exec laravel.test bash -lc 'cat database/migrations/tables.sql | mysql -h mysql -usail -ppassword laravel'

./vendor/bin/sail exec mysql mysql -usail -ppassword laravel -e "SHOW TABLES;DESCRIBE users;DESCRIBE email_campaigns;"



