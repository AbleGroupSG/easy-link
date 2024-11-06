## EasyLink laravel package

### Installation
#### 1.	Add the Service Provider
```
\Serengiy\EasyLink\Providers\EasyLinkServiceProvider::class
```
#### 2.	Publish the Configuration File
Run the following Artisan command to publish the EasyLink configuration file:
```
php artisan vendor:publish --provider="Serengiy\EasyLink\Providers\EasyLinkServiceProvider"
```

#### 3.	Add Environment Variables
Add the following configuration values to your .env file:

```dotenv
EASY_LINK_URL='URL sendbox or easylink'
EASY_LINK_CK='Your company key'
EASY_LINK_APP_ID='Your app id'
EASY_LINK_APP_SECRET='Your app secret'
```
#### 4.	Set the Private Key Path 
In the published configuration file (config/easylink.php), specify the path to your private key file for the private_key parameter:
```php
'private_key' => env('EASY_LINK_PRIVATE_KEY_PATH', '/path/to/your/private.key'),
```
#### 5.	Ensure Required PHP Extension
Your application needs the openssl PHP extension to use this package. Make sure ext-openssl is added to your main composer.json file to enforce this requirement:
```json
{
    "require": {
        "php": "^8.0",
        "ext-openssl": "*"
    }
}
```
