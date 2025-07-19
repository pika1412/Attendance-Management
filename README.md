# 勤怠管理アプリ

## 環境構築
### 1. Dockerビルド
* git clone リンク
* docker compose up -d --build

### 2. Laravel環境構築
* docker compose exec php bash
* composer install
* cp .env.example .env(envファイルを以下のように環境を編集)  
APP_NAME="Attendance Management"  
DB_CONNECTION=mysql  
DB_HOST=mysql  
DB_PORT=3306  
DB_DATABASE=mock_exam_db  
DB_USERNAME=mock_exam_user  
DB_PASSWORD=mock_exam__pass  

* php artisan key:generate
* php artisan migrate
* php artisan db:seed(ダミーデータ投入)


### 管理者情報
* メールアドレス : admin@example.com
* パスワード : password  
 ※管理者ログイン画面でこちらを入力してください

## 使用技術（実行環境）
* Laravel
* PHP 8.2
* MySQL
* phpMyAdmin

## URL
* 新規登録画面 : http://localhost/register
* スタッフログイン画面 : http://localhost/login
* 管理者ログイン画面 : http://localhost/admin/login
* phpMyAdmin : http://localhost:8080/

### ER図
* 
