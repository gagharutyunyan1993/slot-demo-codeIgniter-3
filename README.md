![demo](https://github.com/user-attachments/assets/7ec506e1-9f5a-495e-93d8-519ca1476423)

# Slot Demo (CodeIgniter 3)

Этот проект — демо-версия слота "Burning Love", написанная на PHP с использованием CodeIgniter 3.

## 📌 Требования

Перед запуском убедитесь, что у вас установлены:

- PHP 7.4 (с поддержкой MySQLi)
- MySQL
- Apache/Nginx
- Composer (для установки зависимостей CodeIgniter)
- [CodeIgniter 3](https://codeigniter.com/download)

## 🚀 Установка и настройка

### 1️⃣ Склонируйте репозиторий
```bash
git clone https://github.com/gagharutyunyan1993/slot-demo-codeIgniter-3.git
cd slot-demo-codeIgniter-3
```

### 2️⃣ Установите зависимости
```bash
composer install
```

### 3️⃣ Настройка базы данных

Создайте базу данных в MySQL:

```sql
CREATE DATABASE slots;
```

Настройте подключение в файле `application/config/database.php`:

```php
$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => '',
	'database' => 'slots',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
```

### 4️⃣ Запустите миграцию базы данных
```bash
php index.php migrate
```

### 5️⃣ Запуск локального сервера

Запустите встроенный сервер PHP:

```bash
php -S localhost:8000
```

Или настройте виртуальный хост в Apache/Nginx.

### 7️⃣ Открытие в браузере

Перейдите в браузере по адресу:

```
http://localhost:8000/
```

## 🕹 Геймплей

1. Нажмите кнопку `Начать игру`, чтобы создать игровую сессию.
2. Вам будет начислен начальный баланс (1000).
3. Нажмите `SPIN (Bet 10)`, чтобы запустить вращение.
4. Если выпадает выигрышная комбинация — вы получите приз.
5. Игра продолжается, пока баланс не закончится.

## 📁 Структура проекта

```
/application
  /config         # Конфигурация CodeIgniter
  /controllers    # Контроллеры (Game.php, ResourceServer.php)
  /models         # Модели (Game_model.php)
  /views          # Фронтенд (game/index.php)
  /libraries      # Игровая логика (GameLogic.php)
  /migrations     # Миграции базы данных
/public
  /resources      # Статические файлы (изображения, звуки)
```

## 🛠 Основные API-эндпоинты

- `POST /init-session` — создать игровую сессию
- `POST /do-spin` — выполнить спин

## ❓ FAQ

### Как изменить символы или таблицу выплат?
Измените массив `$paytable` и `$reels` в `application/libraries/GameLogic.php`.

### Где хранятся игровые сессии?
В таблице `sessions` базы данных.

### Как запустить проект на удалённом сервере?
1. Скопируйте файлы на сервер.
2. Настройте базу данных.
3. Настройте виртуальный хост.
4. Запустите сервер.
