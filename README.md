# coachtechフリマ  
  
## 環境構築  
### Dockerビルド  
  1.`git clone https://github.com/ayuka-maruyama/coachtech_flea_markets.git`  
  2.`cd coachtech_flea_markets`  
  2.`docker-compose up -d --build`  
  
### Laravel環境構築  
  1.`docker-compose exec php bash`  
  2.`composer install`  
  3.`cp .env.example .env`  
  Windowsの場合は権限不足の可能性があるので権限を付与する  
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
  6.シンボリックリンクの作成  
  ``` bash
  php artisan storage:link
  ```
  7.マイグレーション、シーダーの実行  
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
  ![Image](https://github.com/user-attachments/assets/323e618c-fca8-4665-89a6-285743fba68c)  
  
## その他  
  ### ログインユーザー  
  テストユーザー(メール認証、プロフィール設定済み)  
  name: テスト  
  email: test@example.com  
  password: password  
  
  ### PHPunitテスト  
  テスト環境構築  
  1.MySQLコンテナへログイン  
  `docker-compose exec mysql bash`  
  2.MySQLへログイン
  `mysql -u root -p`  
  3.パスワード入力  
  docker-compose.ymlのMYSQL_ROOT_PASSWORD参照  
  4.テスト用データベースの作成  
  `CREATE DATABASE demo_test;`  
  5.データベースの作成確認  
  `SHOW DATABASES;`  
  6.PHPコンテナへログイン  
  `docker-compose exec php bash`  
  7..env.testingファイルを作成  
  `cp .env.example .env.testing`  
  8.環境変数を適宜変更  
  ```text
  APP_ENV=test
  APP_KEY=
  
  DB_CONNECTION=mysql
  DB_HOST=mysql
  DB_PORT=3306
  DB_DATABASE=demo_test
  DB_USERNAME=root
  DB_PASSWORD=root
  
  MAIL_MAILER=log
  MAIL_HOST=mailhog
  MAIL_PORT=1025
  MAIL_USERNAME=null
  MAIL_PASSWORD=null
  MAIL_ENCRYPTION=null
  MAIL_FROM_ADDRESS="info@example.com"
  MAIL_FROM_NAME="${APP_NAME}"
  ```  
  9.アプリケーションキーの作成  
  ``` bash
  php artisan key:generate --env=testing
  ```  
  10.`php artisan test`でテスト実行

