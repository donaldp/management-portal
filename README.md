## Management crud app

### Clone

```bash
git clone git@github.com:donaldp/management-portal.git
cd management-portal
```

### Install deps

```bash
composer install
npm i --legacy-peer-deps
```

### Configure

```bash
cp .env.example .env
php artisan key:gen
```

Set your mysql and mail server information.

> For faster setup, you can leave mail config as is. All outgoing emails will be logged to `storage/logs/laravel.log`

Once done configuring your app, cache your env:

```bash
php artisan config:cache
```

### Migration and seeder

```bash
php artisan migrate:fresh --seed
```

### Build assets

```bash
npm run build
```

### Run app

```bash
php artisan serve
```

### Login creds:

```txt
email: admin@example.com
password: supersecurepassword
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
