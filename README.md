<h1>Мини-CRM</h1>
<p>Сбор и обработка заявок с сайта через виджет</p>

<br>

<p><b>🛠 Используемые технологии и технические решения: <a href="https://github.com/vad-dom/laravel-crm-test/blob/main/TECHNICAL_NOTES.md">TECHNICAL_NOTES.md</a></b></p>

<h2>📂 Структура проекта</h2>
<pre>
  ├── docker/                            # настройки Docker
  ├── laravel/                                 
  │   ├── app/                            
  │       ├── Enums/                     # enum TicketStatus
  │       ├── Http/                       
  │           ├── Controllers/             
  │               ├── Admins/              
  │               ├── Api/                 
  │           ├── Requests/                
  │               ├── Admins/              
  │               ├── Api/                 
  │           ├── Resources/               
  │       ├── Models/                      
  │       ├── Services/                  # Ticket services    
  │           ├── Admin/                 # TicketStatusService   
  │   ├── database/                     
  │       ├── factories/                 # Customer, User, Ticket    
  │       ├── migrations/             
  │       ├── seeders/              
  │   ├── lang/                     
  │       ├── ru/                        # переводы    
  │   ├── public/                     
  │       ├── docs/                      # документация Swagger    
  │   ├── resources/
  │       ├── css/                       # виджет
  │       ├── views/                     # домашняя страница и виджет
  │           ├── admin/          
  │               ├── tickets/           # список заявок и детальная информация
  │           ├── layouts/               # iframe
  │           ├── vendor/                
  │               ├── media-library/     # опубликовано
  │               ├── pagination/        # опубликовано
  │           ├── layouts/               # iframe
  │   ├── routes/                        # api, web
  │   ├── tests/
  │       ├── Feature/                   # TicketApiTest
  │   ├── .env
  │   ├── .env.example
  ├── docker-compose.yml
  ├── TECHNICAL_NOTES.md
  └── README.md
</pre>

<br>
<h2>🚀 Как запустить проект:</h2>
<ol>
  <li>
    <strong>Клонировать репозиторий laravel-crm-test:</strong>
    <pre><code>git clone https://github.com/vad-dom/laravel-crm-test.git</code></pre>
  </li> 
  <li>
    <strong>Перейти в папку laravel-crm-test:</strong>
    <pre><code>cd laravel-crm-test</code></pre>
  </li> 
  <li> 
    <strong>Собрать и запустить контейнеры:</strong> 
    <pre><code>docker compose up --build</code></pre> 
    <p>При этом автоматически:</p> 
    <ul> 
      <li>Соберутся Docker-образы для сервисов приложения</li> 
      <li>Поднимутся контейнеры <code>app</code>, <code>db</code>, <code>phpmyadmin</code> и <code>node</code></li>
      <li>Создадутся необходимые Docker volumes <code>db_data</code>, <code>node_modules</code></li>
      <li><code>.env.example</code> скопируется в <code>.env</code></li>
      <li>Установятся PHP-зависимости через Composer</li> 
      <li>Выполнятся миграции базы данных</li> 
      <li>Выполнится заполнение базы тестовыми данными (seeders): 1 менеджер, 10 клиентов, 10 заявок</li> 
      <li>Настроятся права доступа на необходимые папки Laravel</li> 
      <li>Запустится Apache внутри контейнера приложения</li> 
      <li>Установятся Node-зависимости <code>npm ci</code> или <code>npm install</code></li> 
      <li>Запустится Vite dev-server для сборки frontend-ассетов</li> 
      <li>Laravel начнёт использовать dev-сборку ассетов через Vite (CSS/JS будут доступны сразу после запуска)</li> 
      <li>Создастся символическая ссылка <code>public/storage</code> для доступа к файлам из <code>storage/app/public</code></li> 
    </ul> 
  </li> 
  <br>
  <li> 
    <strong>Приложение будет доступно по адресу:</strong> 
    <pre><code>http://localhost:8080</code></pre> 
  </li> 
  <li> 
    <strong>Доступ к базе данных:</strong> 
    <pre><code>http://localhost:8081</code></pre>
  </li>
</ol>

<h2>API</h2>

<p>Документация API доступна в Swagger (есть ссылка с главной страницы):</p>

<code>http://localhost:8080/docs</code>

<h2>📋 Тестовые данные</h2>

<p>После запуска проекта в базе создаются следующие данные:</p>

<h3>Менеджер</h3>

<code>Email: manager@example.com</code>
<code>Пароль: password</code>

<p>Этот пользователь имеет роль `manager` и может войти в админ-панель</p>

<h3>Тестовые заявки</h3>

<p>Seeder создаёт 10 тестовых заявок со случайными данными</p>

<ul>
  Каждая заявка содержит:
  <li>клиента (Customer)</li>
  <li>тему</li>
  <li>текст сообщения</li>
  <li>статус `new`</li>
</ul>

<h2>✅ Как запустить API-тесты:</h2>
<p>(внутри контейнера приложения)</p>
  <pre><code>docker compose exec app php artisan test --filter TicketApiTest</code></pre> 
  <p>Результаты будут выведены в терминале</p>
<h2>⚠️ Важно:</h2>
<p>Отдельная база данных для тестов не создавалась, поэтому при запуске тестов существующие данные удалятся</p>
