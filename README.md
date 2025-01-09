# coachtechフリマ  
  
## 環境構築  
### Dockerビルド  
  1.`git clone https://github.com/ayuka-maruyama/coachtech_flea_markets.git`  
  2.`docker-compose up -d --build`  
  
### Laravel環境構築  
  1.`docker-compose exec php bash`  
  2.`composer install`  
  3.`cp .env.example .env`  
  環境変数を適宜変更  
  ```text
  DB_CONNECTION=mysql  
  DB_HOST=mysql  
  DB_PORT=3306  
  DB_DATABASE=laravel_db  
  DB_USERNAME=laravel_user  
  DB_PASSWORD=laravel_pass  
    
  STRIPE_KEY=公開可能キーを入力  
  STRIPE_SECRET=シークレットキーを入力  
  STRIPE_WEBHOOK_SECRET=制限付きのキーのトークンを入力  
  CASHIER_CURRENCY=ja_JP  
  CASHIER_CURRENCY_LOCALE=ja_JP  
  CASHIER_LOGGER=daily  
    
  MAIL_MAILER=smtp  
  MAIL_HOST=mailhog  
  MAIL_PORT=1025  
  MAIL_USERNAME=null  
  MAIL_PASSWORD=null  
  MAIL_ENCRYPTION=null  
  MAIL_FROM_ADDRESS="info@example.com"  
  MAIL_FROM_NAME="${APP_NAME}"  
  ```  
  5.アプリケーションキーの作成  
  ``` bash
  php artisan key:generate
  ```  
    
  6.マイグレーション、シーダーの実行  
  ``` bash
  php artisan migrate --seed
  ```  
  
## 開発環境  
  ### URL  
  開発環境 : http://localhost/  
  phpMyAdmin : http://localhost:8080/  
  MailHog : http://localhost:8025/  
  
## 使用技術（実行環境）  
  ・ PHP 8.2.26  
  ・ Laravel Framework 11.33.2  
  ・ MySQL 9.0.1  
  ・ nginx 1.26.2  
  
## ER図  
  ![flea_markets_er2](https://github.com/user-attachments/assets/560afd8c-2eae-4729-b320-12dc2d6263f3)  
  
## その他  
  テストユーザー(メール認証、プロフィール設定済み)  
  name: テスト  
  email: test@example.com  
  password: password  
