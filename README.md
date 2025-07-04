# Laravel Postal Mail Driver

A custom Laravel mail transport driver to send emails via the [Postal](https://postal.atech.media) mail server using its **HTTP API** instead of SMTP.

---

## 📦 Features

- Send transactional emails via Postal HTTP API
- Uses Laravel's native `Mail::to()->send()` interface
- Clean, framework-native mail transport driver
- Compatible with Laravel version 8.0+

---

## 🚀 Installation

### Step 1: Require the package (from local path or Git)

If using local development:

```bash
composer require php-monsters/laravel-postal-driver
```

Or add to your composer.json:

```json
"repositories": [
  {
    "type": "path",
    "url": "./packages/PhpMonsters/LaravelPostalDriver"
  }
],
"require": {
  "php-monsters/laravel-postal-driver": "*"
}
```
Then run:
```bash
composer update
```

## ⚙️ Configuration
### Step 2: Set up .env variables
```dotenv
MAIL_MAILER=postal
POSTAL_API_KEY=your_postal_api_key_here
POSTAL_API_ENDPOINT=https://postal.yourdomain.com
```

### Step 3: Configure config/mail.php
Add to the mailers array:
```php
'mailers' => [
    'postal' => [
        'transport' => 'postal',
        'key' => env('POSTAL_API_KEY'),
        'endpoint' => env('POSTAL_API_ENDPOINT'),
    ],
],
```

## ✉️ Usage
Use Laravel's standard Mail facade:
```php
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

Mail::to('recipient@example.com')->send(new TestMail());
```
Your Mailable class should work as usual.

## 📐 Under the Hood
This driver makes HTTP POST requests to:
```bash
POST /api/v1/send/message
```
With headers:
```http request
X-Server-API-Key: {your_api_key}
Content-Type: application/json
```
Payload:

```json
{
  "to": "recipient@example.com",
  "from": "you@yourdomain.com",
  "subject": "Subject Line",
  "plain_body": "Text version",
  "html_body": "<p>HTML version</p>"
}
```

## 🛠 Requirements
- Laravel 8 or newer 
- Postal mail server with API access 
- PHP 8.0+ 
- Guzzle 7+

## 📝 License
This project is open-sourced under the MIT license.

## 🤝 Credits
Made with ❤️ by Aboozar Ghaffari
Postal by [Atech Media](https://postal.atech.media)