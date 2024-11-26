# coachtechフリマ  
  
## 環境構築  
### Dockerビルド  
  1.`git clone https://github.com/ayuka-maruyama/coachtech_flea_markets.git`  
  2.`docker-compose up -d --build`  
  
### Laravel環境構築  
  1.`docker-compose exec php bash`  
  2.`composer install`  
  3.`cp .env.example .env` 環境変数を適宜変更  
  4.`php artisan key:generate`  
  <!-- 5.`php artisan migrate`   -->
  <!-- 6.`php artisan db:seed`   -->
  
## 開発環境  
  
  
  
## 使用技術（実行環境）  
  ・ PHP 8.2.26
  ・ Laravel Framework 11.33.2  
  ・ MySQL 9.0.1  
  ・ nginx 1.26.2  
  
## ER図  
  
  
## URL  
  開発環境 : http://localhost/
  phpMyAdmin : http://localhost:8080/
  MailHog : http://localhost:8025/
  
## その他  
  
