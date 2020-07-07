# Laravel de Twitter Log in

## 新規プロジェクト
composer create-project --prefer-dist laravel/laravel . "6.*"

## 開発立ち上げ
php artisan serve

## 設定
'timezone' => 'Asia/Tokyo',   
'locale' => 'ja',   
'faker_locale' => 'ja_JP',  
php artisan storage:link  
php artisan migrate  

## Git
git init

## Laravel Socialiteをインストール
composer require laravel/socialite  




