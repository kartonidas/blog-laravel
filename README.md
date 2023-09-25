<h1>Laravel - blog</h1>

<h2>Instalacja<h2>
<ul>
    <li>composer install</li>
    <li>cp .env.example .env</li>
    <li>php artisan key:generate</li>
    <li>W .env skonfigurowanie połączenia z bazą danych oraz ze skrzynką pocztową</li>
    <li>php artisan migrate</li>
    <li>W razie potrzeby ustawienie odpowiednich uprawnień na katalogach: storage, bootstrap</li>
    <li>Wiadomości e-mail wysyłane są z kolejki (konieczne uruchomienie: php artisan queue:work)</li>
</ul>

<p>
    Domyślne konto administratora: admin@example.com, hasło: admin.
</p>
<p>
    Dokumentacja API pod adresem [DOMENA]/docs/api (https://blog.netextend.ovh/docs/api#/).
</p>
