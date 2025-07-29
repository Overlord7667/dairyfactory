📁 Структура проекта

dairyfactory/
├── backend/        # PHP (Yii) сервер, API, подключение к БД
├── frontend/       # Vue 3 интерфейс с Vuetify
└── database/       # SQL-дамп базы данных (если есть)

📋 Требования
Node.js v20.16.0 или выше
npm v10.8.1 или выше
PHP 8.1+
Composer
PostgreSQL
Vue 3
Vuetify
Vite
Axios

🚀 Запуск проекта
1. Клонируйте репозиторий

git clone https://github.com/Overlord7667/dairyfactory.git
cd dairyfactory

2. Создайте базу данных
Создайте базу dairyfactory в PostgreSQL. Затем:

Либо импортируйте дамп dairyfactory_dump.sql если он есть

Либо создайте таблицы вручную:

CREATE TABLE milk_entries (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    volume INTEGER,
    tank_number INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tanks (
    id SERIAL PRIMARY KEY,
    current_volume INTEGER DEFAULT 0
);


3. Настройте подключение к БД
Откройте файл backend/config/db.php и убедитесь, что username и password совпадают с вашими параметрами PostgreSQL:

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;dbname=dairyfactory',
    'username' => 'ваш_логин',
    'password' => 'ваш_пароль',
    'charset' => 'utf8',
];


⚙️ Запуск backend

cd backend
composer install
php -S localhost:8080


💻 Запуск frontend

bash
Копировать
Редактировать
cd ../frontend
npm install
npm run dev

Убедитесь, что в frontend/vite.config.js или .env указан корректный адрес API:

VITE_API_URL=http://localhost:8080
