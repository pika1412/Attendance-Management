# 勤怠管理アプリ

## 環境構築
### 1. Dockerビルド
* git clone リンク
* docker-compose up -d --build

### 2. Laravel環境構築
* docker-compose exec php bash
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

### 3. メールアドレス認証
* 会員登録後認証画面へ遷移認証はこちらボタンを押下すると勤怠打刻画面へ遷移します。 
* Mailhog使用
* Mailhog認証サイト　http://localhost:8025
* メールが届かない場合は再送ボタンを押下してください。

### 4. テスト実行方法 
| 内容                                      | ファイル名                                                           |
|-------------------------------------------|----------------------------------------------------------------------|
| 認証機能(一般ユーザー)                   | php artisan test tests/Feature/RegisterTestTest.php                 |
| ログイン認証機能(一般ユーザー)           | php artisan test tests/Feature/LoginTestTest.php                    |
| ログイン機能(管理者)                     | php artisan test tests/Feature/AdminLoginTestTest.php               |
| 日時取得機能                              | php artisan test tests/Feature/DateTestTest.php                     |
| ステータス確認機能                        | php artisan test tests/Feature/CheckStatusTestTest.php              |
| 出勤機能                                  | php artisan test tests/Feature/WorkingTestTest.php                  |
| 休憩機能                                  | php artisan test tests/Feature/BreakTestTest.php                    |
| 退勤機能                                  | php artisan test tests/Feature/FinishedTestTest.php                 |
| 勤怠一覧情報取得(一般ユーザー)           | php artisan test tests/Feature/AttendanceListTestTest.php           |
| 勤怠詳細情報取得機能(一般ユーザー)       | php artisan test tests/Feature/DetailTestTest.php                   |
| 勤怠詳細情報修正機能(一般ユーザー)       | php artisan test tests/Feature/ApplicationTestTest.php              |
| 勤怠一覧情報取得機能(管理者)             | php artisan test tests/Feature/AdminAttendanceTestTest.php          |
| 勤怠詳細情報取得修正機能(管理者)         | php artisan test tests/Feature/AdminInformationTestTest.php         |
| ユーザー情報取得機能(管理者)             | php artisan test tests/Feature/AdminUserInformationTestTest.php     |
| 勤怠情報修正機能(管理者)                 | php artisan test tests/Feature/AdminApprovalTestTest.php            |
| メール認証機能                            | php artisan test tests/Feature/TestTest.php                         |

### 管理者情報
* メールアドレス : admin@example.com
* パスワード : password  
 ※管理者ログイン画面でこちらを入力してください

## 使用技術（実行環境）
* Laravel
* PHP 8.2
* MySQL
* phpMyAdmin
* Mailhog

## URL
* 新規登録画面 : http://localhost/register
* スタッフログイン画面 : http://localhost/login
* 管理者ログイン画面 : http://localhost/admin/login
* phpMyAdmin : http://localhost:8080/

### ER図
<img width="421" height="406" alt="readme" src="https://github.com/user-attachments/assets/3fd7a4b6-c24d-43f0-bc13-1bbabd26b166" />


