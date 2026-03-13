# Hola Framework

> Tài liệu mô tả toàn bộ cấu trúc, API và cách sử dụng các hàm của **Hola Framework** - một PHP framework đơn giản, yêu cầu PHP >= 8.0.

---

## Mục lục

- [1. Cấu trúc dự án](#1-cấu-trúc-dự-án)
- [2. Vòng đời ứng dụng](#2-vòng-đời-ứng-dụng)
- [3. Routing](#3-routing)
- [4. Controller](#4-controller)
- [5. Request](#5-request)
- [6. Response](#6-response)
- [7. Middleware](#7-middleware)
- [8. Database & Model (ORM)](#8-database--model-orm)
- [9. Migration (Schema)](#9-migration-schema)
- [10. Validation & FormRequest](#10-validation--formrequest)
- [11. View & Template Engine](#11-view--template-engine)
- [12. Collection](#12-collection)
- [13. Container (Dependency Injection)](#13-container-dependency-injection)
- [14. Events](#14-events)
- [15. Cache](#15-cache)
- [16. Rate Limiter](#16-rate-limiter)
- [17. Session & Cookie](#17-session--cookie)
- [18. Mailing](#18-mailing)
- [19. Queue Jobs](#19-queue-jobs)
- [20. CLI Commands](#20-cli-commands)
- [21. HTTP Client (Curl)](#21-http-client-curl)
- [22. Date Utility](#22-date-utility)
- [23. File Management](#23-file-management)
- [24. Helper Functions (Global)](#24-helper-functions-global)
- [25. Config](#25-config)
- [26. Share Data](#26-share-data)
- [27. Logging](#27-logging)

---

## 1. Cấu trúc dự án

```
hola-framework/
├── App/
│   ├── App.php                    # Class chính, kế thừa Application
│   ├── Commands/                  # CLI commands tự tạo
│   ├── Http/
│   │   ├── Controllers/           # Controllers
│   │   ├── Middleware/            # Middleware (Kernel, Auth, Cors, Csrf)
│   │   └── Request/              # FormRequest validation
│   ├── Mails/                    # Mail classes
│   ├── Models/                   # Eloquent-like Models
│   ├── QueueJobs/                # Queue job classes
│   └── Views/                    # View templates (.view.php)
├── config/
│   ├── cache.php                 # Cấu hình cache
│   ├── database.php              # Cấu hình database
│   └── queue.php                 # Cấu hình queue
├── database/
│   └── SchemaMigrate/            # Migration files
├── language/                     # Ngôn ngữ (vi.php, en.php)
├── public/                       # Assets (css, js, scss)
├── router/
│   ├── index.php                 # Đăng ký nhóm router
│   ├── web.php                   # Web routes
│   └── api.php                   # API routes
├── storage/                      # Logs, cache, render
├── bootstrap.php                 # Bootstrap ứng dụng
├── index.php                     # Entry point HTTP
├── cli.php                       # Entry point CLI
└── .env                          # Biến môi trường
```

### Namespace conventions

| Thư mục | Namespace |
|---------|-----------|
| `App/` | `App\` |
| `App/Http/Controllers/` | `App\Http\Controllers\` |
| `App/Http/Middleware/` | `App\Http\Middleware\` |
| `App/Http/Request/` | `App\Http\Request\` |
| `App/Models/` | `App\Models\` |
| `App/Commands/` | `App\Commands\` |
| `App/Mails/` | `App\Mails\` |
| `App/QueueJobs/` | `App\QueueJobs\` |

### Framework Core Namespace: `Hola\`

| Module | Namespace |
|--------|-----------|
| Routing | `Hola\Routings\Router` |
| Model/ORM | `Hola\Database\Model` |
| DBO (raw query) | `Hola\Database\DBO` |
| Request | `Hola\Transport\Request` |
| Response | `Hola\Transport\Response` |
| Middleware | `Hola\Transport\Middleware` |
| Container | `Hola\Container\Container` |
| Validation | `Hola\Core\Validation` |
| FormRequest | `Hola\Core\FormRequest` |
| Curl | `Hola\Core\Curl` |
| Date | `Hola\Core\Date` |
| Files | `Hola\Core\Files` |
| Command | `Hola\Core\Command` |
| Mailer | `Hola\Mailing\Mailer`, `Hola\Mailing\MailerBuilder` |
| Queue | `Hola\Queue\CreateQueue` |
| Events | `Hola\Events\AppEvents` |
| Collection | `Hola\Data\Collection` |
| Session | `Hola\Data\Session` |
| Cookie | `Hola\Data\Cookie` |
| Cache | `Hola\Data\Cache\CacheManager` |
| RateLimit | `Hola\Data\Cache\RateLimit\RateLimiter` |
| ViewRender | `Hola\Views\ViewRender` |
| Migration | `Hola\Database\TableMigration` |
| Schema | `Hola\Database\Structure\DBSchema` |

---

## 2. Vòng đời ứng dụng

### HTTP Request Lifecycle

1. `index.php` → load `vendor/autoload.php` → load `bootstrap.php`
2. `bootstrap.php`:
   - Tạo `RegisterLoad` → load `.env`, configs, router files, timezone, language
   - Gọi `initApp()` để include các file cần thiết
3. `App\App` extends `Hola\Application`:
   - `register()`: đăng ký dependency injection, cache view HTML
   - `run()`: `registerShutdown()` → `initializeCore()` → `handleHttpRequest()`
4. Trong `initializeCore()`:
   - Đăng ký singletons: `Request`, `Response`, `Router`, `AppEvents`, `ConnectionManager`
   - Gọi `register()`, `registerEvent()` (user-defined)
   - Resolve router → tìm controller + middleware
5. Chạy middleware pipeline → gọi controller method → trả response

### CLI Lifecycle

1. `cli.php` → load autoload → `RegisterLoad::initCLI()` → `App::register()` → `App::runCLI()`
2. Tự động scan `App/Commands/` và framework commands
3. Chạy bằng: `php cli.php <command_name>`

---

## 3. Routing

### File cấu hình router: `router/index.php`

```php
use Hola\Routings\RouterConfig;
RouterConfig::init()
    ->add([
        ['url' => '/', 'file' => 'web'],       // load router/web.php, prefix = /
        ['url' => 'admin', 'file' => 'api']     // load router/api.php, prefix = /admin/
    ])
    ->work();
```

### Định nghĩa routes: `router/web.php`

```php
use Hola\Routings\Router;
use App\Http\Controllers\HomeController;

// Các HTTP methods
Router::get('/path', [Controller::class, 'method']);
Router::post('/path', [Controller::class, 'method']);
Router::put('/path', [Controller::class, 'method']);
Router::patch('/path', [Controller::class, 'method']);
Router::delete('/path', [Controller::class, 'method']);
Router::options('/path', [Controller::class, 'method']);
Router::head('/path', [Controller::class, 'method']);

// Route parameters (dynamic segments)
Router::get('/users/{id}', [UserController::class, 'show']);
Router::get('/posts/{postId}/comments/{commentId}', [CommentController::class, 'show']);

// Middleware cho route đơn
Router::middleware('auth')->get('/dashboard', [DashboardController::class, 'index']);

// Prefix
Router::prefix('/api/v1')->get('/users', [UserController::class, 'index']);

// Group routes (middleware + prefix dùng chung)
Router::prefix('/api')->middleware('auth')->group(function ($router) {
    $router->get('/users', [UserController::class, 'index']);
    $router->post('/users', [UserController::class, 'store']);
    $router->get('/users/{id}', [UserController::class, 'show']);
    $router->put('/users/{id}', [UserController::class, 'update']);
    $router->delete('/users/{id}', [UserController::class, 'destroy']);
});

// Chain middleware trên route đã tạo
Router::get('/profile', [ProfileController::class, 'show'])->middleware('auth');
```

### Middleware alias

Đăng ký trong `App/Http/Middleware/Kernel.php`:
```php
public $routerMiddleware = [
    "auth" => \App\Http\Middleware\AuthMiddleware::class,
    // thêm alias khác ở đây
];
```

---

## 4. Controller

### Tạo Controller

- Đặt trong `App/Http/Controllers/`
- Namespace: `App\Http\Controllers`

```php
<?php
namespace App\Http\Controllers;
use Hola\Transport\Request;
use Hola\Transport\Response;

class UserController {
    public function __construct() {}

    // Dependency injection tự động: Request, Response, FormRequest, v.v.
    public function index(Request $request) {
        $users = \App\Models\User::get();
        return Response::json($users);
    }

    // Route parameters được inject tự động theo thứ tự
    public function show(Request $request, $id) {
        $user = \App\Models\User::find($id);
        return Response::json($user);
    }

    public function store(Request $request) {
        $data = $request->all();
        $user = \App\Models\User::create($data);
        return Response::json($user)->setStatus(201);
    }

    // Trả view
    public function home(Request $request) {
        return Response::view('welcome', ['name' => 'Hola']);
    }

    // Redirect
    public function redirectExample() {
        return Response::redirect('/dashboard');
    }
}
```

### Dependency Injection trong Controller

Framework tự động resolve dependencies qua constructor và method parameters:
- Nếu parameter có type-hint là class → tự động `make()` và inject
- Nếu parameter không có type-hint hoặc là builtin → lấy từ route parameters theo thứ tự

---

## 5. Request

### Class: `Hola\Transport\Request`

```php
use Hola\Transport\Request;

public function index(Request $request) {
    // Lấy dữ liệu
    $request->get('key');              // GET parameter
    $request->get('key', 'default');   // Với giá trị mặc định
    $request->post('key');             // POST parameter
    $request->value('key');            // Body (php://input JSON)
    $request->put('key');              // PUT data
    $request->patch('key');            // PATCH data
    $request->all();                   // Tất cả data (GET + POST + body JSON)
    $request->any('key');              // Lấy từ all() theo key
    $request->has('key');              // Kiểm tra key tồn tại

    // Set custom data
    $request->set('custom_key', 'value');

    // File upload
    $request->file('avatar');          // Trả về object file
    $request->getFile('avatar');       // Lấy raw $_FILES
    $request->file('avatar')->tmpName();
    $request->file('avatar')->size();
    $request->file('avatar')->type();
    $request->file('avatar')->extension();
    $request->file('avatar')->originName();
    $request->file('avatar')->errorFile();
    $request->isFile('avatar');        // Kiểm tra file tồn tại

    // Headers
    $request->headers('Authorization');
    $request->headers('Accept', 'text/html');  // Với default
    $request->headers();               // Object headers (set, get, has, remove, all, clear)

    // Session & Cookie
    $request->session('key');          // Lấy session value
    $request->session();               // Trả về Session object
    $request->cookie('key');           // Lấy cookie value
    $request->cookie();                // Trả về Cookie object

    // Request info
    $request->method();                // GET, POST, PUT, ...
    $request->isMethod('POST');        // Kiểm tra method
    $request->isGet();
    $request->isPost();
    $request->isPut();
    $request->isPatch();
    $request->isDelete();
    $request->isOptions();
    $request->isHead();
    $request->isAjax();
    $request->isSecure();              // HTTPS
    $request->isJson();                // Accept: application/json
    $request->isJsonRequest();         // Content-Type: application/json
    $request->isFormRequest();
    $request->isMultipartRequest();
    $request->isXmlRequest();
    $request->isHtmlRequest();
    $request->isTextRequest();

    // URL info
    $request->path();                  // Request URI path
    $request->hasPath('/admin');       // So sánh path
    $request->domain();                // HTTP_HOST
    $request->domainName();            // SERVER_NAME
    $request->originalDomain();        // HTTP_ORIGIN

    // Client info
    $request->ip();
    $request->userAgent();
    $request->referer();
}
```

---

## 6. Response

### Class: `Hola\Transport\Response`

```php
use Hola\Transport\Response;

// JSON response
Response::json(['message' => 'success', 'data' => $data]);
Response::json($data)->setStatus(201);
Response::json($data)->setHeaders(['X-Custom' => 'value']);

// View response
Response::view('welcome', ['name' => 'Hola']);
Response::view('test.index', $data);   // Dấu chấm = thư mục: App/Views/test/index.view.php

// Redirect
Response::redirect('/login');
Response::redirect('/login')->setStatus(301);

// Text response
Response::text('Hello World');

// XML response
Response::xml(['item' => 'value']);

// File response (inline)
Response::file('/path/to/file.pdf');

// File download
Response::download('/path/to/file.pdf');

// No content (204)
Response::noContent();

// Set status
Response::setStatus(404);

// Set headers
Response::setHeaders(['X-Header' => 'value']);

// Meta tags cho SEO (dùng với view)
Response::metaTag([
    'title' => 'Page Title',
    'description' => 'Page description',
    'keywords' => 'keyword1, keyword2',
    'robots' => 'index, follow',
    'canonical' => 'https://example.com/page',
    'viewport' => 'width=device-width, initial-scale=1',
    'favicon' => '/favicon.ico',
    'og' => [
        'title' => 'OG Title',
        'description' => 'OG Description',
        'image' => 'https://example.com/image.jpg',
    ],
    'twitter' => [
        'card' => 'summary_large_image',
        'title' => 'Twitter Title',
    ],
    'schema' => [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => 'Page Name',
    ]
]);

// Terminate (exit ngay)
Response::terminate();
```

### Sử dụng helper `res()`

```php
// Helper function trả về ResponseBuilder
return res()->json($data);
return res()->view('home', $data);
return res()->redirect('/login');
```

### Controller có thể trả về trực tiếp

```php
// Trả array/object → tự động convert JSON
return ['message' => 'success'];

// Trả string → text response
return 'Hello World';

// Trả ResponseBuilder → send()
return Response::json($data)->setStatus(200);
```

---

## 7. Middleware

### Tạo Middleware

File: `App/Http/Middleware/AuthMiddleware.php`

```php
<?php
namespace App\Http\Middleware;
use Hola\Transport\Middleware;
use Hola\Transport\Request;
use Hola\Transport\Response;

class AuthMiddleware extends Middleware {
    public function forward(Request $request, \Closure $continue) {
        // Logic kiểm tra
        $token = $request->headers('Authorization');
        if (!$token) {
            // Chặn request → trả response
            return Response::json(['message' => 'Unauthorized'])->setStatus(401);
        }
        // Cho phép tiếp tục
        return $continue($request);
    }
}
```

### Đăng ký Middleware

File: `App/Http/Middleware/Kernel.php`

```php
class Kernel implements IKernel {
    // Middleware alias cho router
    public $routerMiddleware = [
        "auth" => \App\Http\Middleware\AuthMiddleware::class,
        "admin" => \App\Http\Middleware\AdminMiddleware::class,
    ];

    // Middleware chạy cho MỌI request (global)
    public $requireMiddleware = [
        \App\Http\Middleware\CorsMiddleware::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
    ];
}
```

### CORS Middleware

```php
class CorsMiddleware extends Middleware {
    protected array $config = [
        'paths' => ['*'],
        'allowed_methods' => ['*'],
        'allowed_origins' => ['*'],      // hoặc ['http://example.com']
        'allowed_headers' => ['*'],
        'exposed_headers' => [],
        'max_age' => 0,
        'supports_credentials' => true,
    ];
}
```

### CSRF Middleware

```php
class VerifyCsrfToken extends Middleware {
    // Bỏ qua CSRF cho các path
    public $except = [
        '/api/*',           // Wildcard
        '/webhook/stripe',  // Path cụ thể
    ];
}
```

Trong view sử dụng `csrfToken()` để tạo hidden input:
```html
<form method="POST">
    <?php csrfToken(); ?>
    <!-- hoặc gửi header X-CSRF-TOKEN -->
</form>
```

---

## 8. Database & Model (ORM)

### Cấu hình: `config/database.php`

```php
return [
    "default" => "mysql",
    "connections" => [
        "mysql" => [
            'driver' => 'mysql',
            "host" => conval('DB_HOST', '127.0.0.1'),
            "port" => conval('DB_PORT', '3306'),
            "db_name" => conval('DB_NAME', 'blog'),
            "username" => conval('DB_USERNAME', 'root'),
            "password" => conval('DB_PASSWORD', ''),
        ],
        "pgsql" => [ ... ],
        "redis" => [ ... ],
    ]
];
```

### Tạo Model

File: `App/Models/User.php`

```php
<?php
namespace App\Models;
use Hola\Database\Model;

class User extends Model {
    // Tên bảng (mặc định = lowercase class name)
    protected static $table = 'users';

    // Tự động thêm timestamps
    protected static $time_auto = true;
    protected static $date_create = "created_at";
    protected static $date_update = "updated_at";

    // Danh sách field cho phép
    protected static $field = ['name', 'email', 'password'];

    // Ẩn field khi trả kết quả
    protected static $hidden = ['password'];

    // Mutator: biến đổi data TRƯỚC khi lưu vào DB
    public function setAttributePassword($value) {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    // Accessor: biến đổi data SAU khi đọc từ DB
    public function getAttributeName($value) {
        return ucfirst($value);
    }

    // Soft delete field
    public function softDeleteField() {
        return ['field' => 'deleted_at', 'value' => date('Y-m-d H:i:s')];
    }

    // Relationships
    public function posts() {
        return $this->hasMany(\App\Models\Post::class, 'user_id', 'id');
    }

    public function profile() {
        return $this->hasOne(\App\Models\Profile::class, 'user_id', 'id');
    }

    public function role() {
        return $this->belongsTo(\App\Models\Role::class, 'role_id', 'id');
    }

    public function tags() {
        return $this->belongsToMany(
            \App\Models\Tag::class,  // Related model
            'user_tags',             // Bảng trung gian
            'user_id',               // Foreign key trong bảng trung gian
            'tag_id',                // Foreign key 2 trong bảng trung gian
            'id'                     // Primary key
        );
    }
}
```

### Query Builder (CRUD)

```php
use App\Models\User;
use Hola\Database\DBO;

// === SELECT ===
User::get();                                    // SELECT * → Collection
User::select(['id', 'name', 'email'])->get();   // SELECT id, name, email
User::find(1);                                  // WHERE id = 1 → Collection (single)
User::first();                                  // Lấy 1 record đầu tiên → Collection (single)
User::where('status', 'active')->get();         // WHERE status = 'active'
User::where('age', '>', 18)->get();             // WHERE age > 18
User::where(['status' => 'active', 'role' => 'admin'])->get(); // Multiple WHERE (array)

// WHERE nâng cao
User::where('status', 'active')
    ->orWhere('role', 'admin')
    ->get();

// Nested WHERE
User::where(function ($query) {
    $query->where('age', '>', 18)
          ->where('status', 'active');
})->orWhere('role', 'admin')->get();

User::whereLike('name', '%john%')->get();
User::orWhereLike('email', '%@gmail.com')->get();
User::whereBetween('age', [18, 30])->get();
User::whereIn('id', [1, 2, 3])->get();
User::orWhereIn('status', ['active', 'pending'])->get();
User::whereNotIn('id', [4, 5])->get();
User::orWhereNotIn('role', ['banned'])->get();
User::whereRaw('age > 18 AND status = "active"')->get();
User::orWhereRaw('role = "admin"')->get();

// Conditional query
User::when($request->has('status'), function ($query) use ($request) {
    $query->where('status', $request->get('status'));
})->get();

// JOIN
User::join('profiles', 'users.id', '=', 'profiles.user_id')->get();
User::leftJoin('profiles', 'users.id', '=', 'profiles.user_id')->get();
User::rightJoin('orders', 'users.id', '=', 'orders.user_id')->get();
User::crossJoin('roles', 'users.role_id', '=', 'roles.id')->get();

// ORDER BY
User::orderBy('name', 'asc')->get();
User::orderBy(['name' => 'asc', 'created_at' => 'desc'])->get();

// GROUP BY + HAVING
User::groupBy('role')->having('count', '>', 5)->get();

// LIMIT + OFFSET
User::limit(10)->offset(20)->get();

// UNION
User::select(['name'])->from('users')
    ->union(User::select(['name'])->from('admins')->toSql())
    ->get();

// SubQuery
User::subQuery("SELECT * FROM users WHERE age > 18", 'sub_users')
    ->where('sub_users.status', 'active')
    ->get();

// Pagination
User::pagination($limit = 10, $page = 1);           // Trả Collection
User::paginationWithCount($limit = 10, $page = 1);  // Trả Collection có total, last_page, next_page, prev_page

// Aggregate
User::count();                                    // COUNT(*)
User::count('id', 'user_count');                  // COUNT(id) AS user_count
User::sum('salary', 'total_salary');              // SUM(salary) AS total_salary

// === INSERT ===
User::create(['name' => 'John', 'email' => 'john@mail.com']);  // Insert + return created record
User::insert(['name' => 'John', 'email' => 'john@mail.com']);  // Insert → bool
User::insertLastId(['name' => 'John']);                         // Insert → return last insert ID

// === UPDATE ===
User::update(['name' => 'John Updated'], 1);                   // UPDATE ... WHERE id = 1
User::update(['status' => 'banned'], ['email' => 'x@y.com']);  // UPDATE ... WHERE email = ...
User::where('status', 'pending')->update(['status' => 'active']); // UPDATE với WHERE

// === UPSERT ===
User::updateOrInsert(['name' => 'John', 'email' => 'j@m.com'], 1); // Update nếu id=1 tồn tại, ngược lại insert

// === DELETE ===
User::delete(1);                                  // DELETE WHERE id = 1
User::where('status', 'banned')->delete();        // DELETE với WHERE
User::softDelete(1);                              // Soft delete (cần khai báo softDeleteField())

// === SAVE (ORM style) ===
$user = new User();
$user->name = 'John';
$user->email = 'john@mail.com';
$user->save();                                    // Insert nếu không có id, Update nếu có id

// === Transaction ===
User::beginTransaction();
try {
    User::create([...]);
    User::update([...], 1);
    User::commit();
} catch (\Exception $e) {
    User::rollBack();
}

// === Debug SQL ===
User::where('id', 1)->toSql();       // Trả SQL string
User::where('id', 1)->dump();        // In ra SQL + exit

// === Query Log ===
User::enableQueryLog();
User::get();
$logs = User::getQueryLog();

// === Switch connection ===
User::connection('pgsql')->get();
```

### Relationships (Eager Loading)

```php
// Load quan hệ
User::with('posts')->get();
User::with('profile')->get();
User::with(['posts', 'profile'])->get();

// Chọn cột cho quan hệ
User::with('posts:title,content')->get();

// Custom query cho quan hệ
User::with(['posts' => function ($query) {
    $query->where('status', 'published')
          ->orderBy('created_at', 'desc');
}])->get();

// N+1 Query mode (mỗi record query riêng)
User::with('posts', true)->get();  // tham số 2 = useN1Query
```

### Các loại Relationship

```php
// Trong Model:
public function posts() {
    return $this->hasMany(Post::class, 'user_id', 'id');      // 1-N
}
public function profile() {
    return $this->hasOne(Profile::class, 'user_id', 'id');     // 1-1
}
public function role() {
    return $this->belongsTo(Role::class, 'role_id', 'id');     // N-1
}
public function tags() {
    return $this->belongsToMany(Tag::class, 'user_tags', 'user_id', 'tag_id', 'id');  // N-N
}
public function categories() {
    return $this->manyToMany(Category::class, 'user_categories', 'user_id', 'category_id', 'id');
}
```

### DBO (Raw Query)

```php
use Hola\Database\DBO;

// Raw SQL
DBO::query("SELECT * FROM users WHERE id = 1");

// Dùng Query Builder không cần Model
DBO::from('users')->where('status', 'active')->get();
DBO::from('users')->select(['id', 'name'])->where('age', '>', 18)->get();
```

---

## 9. Migration (Schema)

### Tạo Migration

File: `database/SchemaMigrate/2024_01_01_000000_create_users_table.php`

```php
<?php
namespace App\Database\SchemaMigrate;
use Hola\Database\Structure\Table;
use Hola\Database\Structure\DBSchema;
use Hola\Database\TableMigration;

class CreateUsers extends TableMigration {
    public function up() {
        DBSchema::createTable('users', function(Table $table) {
            $table->integer('id', true)->notNull();           // AUTO_INCREMENT PRIMARY KEY
            $table->varchar('name', 100)->notNull();
            $table->varchar('email', 255)->unique()->notNull();
            $table->varchar('password', 255)->notNull();
            $table->tinyInteger('status', 1)->default(1);
            $table->text('bio')->null();
            $table->integer('role_id')->unsigned();
            $table->dateTime('created_at')->defaultCurrentTimestamp();
            $table->dateTime('updated_at')->null();
        });
    }

    public function down() {
        DBSchema::dropTable('users');
    }
}
```

### DBSchema API

```php
use Hola\Database\Structure\DBSchema;

DBSchema::createTable('table_name', function(Table $table) { ... });
DBSchema::createTableIfNotExists('table_name', function(Table $table) { ... });
DBSchema::dropTable('table_name');
DBSchema::hasTable('table_name');        // bool
DBSchema::dropColumn('table_name', 'column_name');

// Modify table (thêm/sửa/xóa cột)
DBSchema::useTable('users', function(Table $table) {
    $table->varchar('phone', 20)->null()->add();         // Thêm cột
    $table->varchar('name', 200)->notNull()->modify();   // Sửa cột
    $table->varchar('old_column')->drop();               // Xóa cột
    $table->varchar('old_name')->change('new_name');     // Đổi tên cột
});
```

### Column Types

| Method | SQL Type |
|--------|----------|
| `integer($name, $autoIncrement)` | INT |
| `bigInteger($name, $autoIncrement)` | BIGINT |
| `smallInteger($name, $length)` | SMALLINT |
| `mediumInteger($name, $length)` | MEDIUMINT |
| `tinyInteger($name, $length)` | TINYINT |
| `decimal($name, $total, $places)` | DECIMAL |
| `float($name, $total, $places)` | FLOAT |
| `double($name, $total, $places)` | DOUBLE |
| `boolean($name)` | BOOLEAN |
| `serial($name)` | SERIAL |
| `varchar($name, $length)` | VARCHAR |
| `char($name, $length)` | CHAR |
| `text($name)` | TEXT |
| `tinyText($name)` | TINYTEXT |
| `mediumText($name)` | MEDIUMTEXT |
| `longText($name)` | LONGTEXT |
| `binary($name)` | BINARY |
| `blob($name)` | BLOB |
| `date($name)` | DATE |
| `dateTime($name)` | DATETIME |
| `timestamp($name)` | TIMESTAMP |
| `time($name)` | TIME |
| `year($name)` | YEAR |

### Column Attributes

```php
$table->varchar('name', 100)
    ->notNull()                    // NOT NULL
    ->null()                       // DEFAULT NULL
    ->default('value')             // DEFAULT 'value'
    ->defaultCurrentTimestamp()    // DEFAULT CURRENT_TIMESTAMP
    ->unsigned()                   // UNSIGNED
    ->autoIncrement()              // AUTO_INCREMENT
    ->primaryKey()                 // PRIMARY KEY
    ->unique()                     // UNIQUE
    ->comment('description')       // COMMENT
    ->index()                      // INDEX
    ->fullTextIndex()              // FULLTEXT INDEX
    ->spatialIndex()               // SPATIAL INDEX
    ->foreignKey()                 // FOREIGN KEY
    ->references('users', 'id')   // REFERENCES users(id)
    ->onDelete('CASCADE')         // ON DELETE CASCADE
    ->onUpdate('CASCADE');         // ON UPDATE CASCADE
```

---

## 10. Validation & FormRequest

### Validation trực tiếp

```php
use Hola\Core\Validation;

$data = $request->all();
$validate = Validation::create($data, [
    'name' => ['required' => 'Tên không được để trống', 'string' => 'Tên phải là chuỗi'],
    'email' => ['required' => 'Email bắt buộc', 'email' => 'Email không hợp lệ'],
    'age' => ['required' => 'Tuổi bắt buộc', 'number' => 'Tuổi phải là số', 'min:18' => 'Tối thiểu 18'],
    'password' => ['required' => 'Mật khẩu bắt buộc', 'min:6' => 'Tối thiểu 6 ký tự'],
]);

if ($validate->errors()) {
    return Response::json(['errors' => $validate->errors()])->setStatus(422);
}

$validData = $validate->data(); // object
```

### Validation Rules

| Rule | Mô tả |
|------|--------|
| `required` | Bắt buộc |
| `string` | Phải là chuỗi |
| `number` | Phải là số |
| `email` | Email hợp lệ |
| `boolean` | Boolean |
| `array` | Phải là array |
| `date` | Ngày hợp lệ |
| `max:N` | Giá trị tối đa N |
| `min:N` | Giá trị tối thiểu N |
| `pattern:/regex/` | Khớp regex |
| `not_pattern:/regex/` | Không khớp regex |

### FormRequest (auto validation)

File: `App/Http/Request/AuthRequest.php`

```php
<?php
namespace App\Http\Request;
use Hola\Core\FormRequest;

class AuthRequest extends FormRequest {
    public function __construct() {
        parent::__construct();
    }

    // (Tùy chọn) Kiểm tra quyền truy cập
    public function auth() {
        return true; // false → trả 403
    }

    // (Bắt buộc) Định nghĩa rules
    public function rules() {
        return [
            'email' => ['required' => 'Email bắt buộc', 'email' => 'Email không hợp lệ'],
            'password' => ['required' => 'Mật khẩu bắt buộc'],
        ];
    }

    // (Tùy chọn) View khi auth() fail
    public function failedView() {
        return 'error.index';
    }

    // (Tùy chọn) Data khi auth() fail
    public function withData() {
        return ['message' => 'unauthorized', 'code' => 403];
    }
}
```

Sử dụng trong Controller (inject qua type-hint):

```php
public function login(AuthRequest $request) {
    if ($request->errors()) {
        return Response::json(['errors' => $request->errors()])->setStatus(422);
    }
    $data = $request->data();
    // ... login logic
}
```

Lấy errors trong view:

```php
// Trong view dùng helper function
<?php $emailError = errors('email'); ?>
```

---

## 11. View & Template Engine

### Quy ước

- File view đặt trong `App/Views/`
- Extension: `.view.php`
- Dùng dấu chấm làm separator thư mục: `test.index` → `App/Views/test/index.view.php`

### Render view

```php
// Trong controller
return Response::view('welcome', ['name' => 'Hola', 'items' => [1, 2, 3]]);
return res()->view('test.layout.home', ['name' => 'longdh']);
```

### Template Syntax

#### Hiển thị biến
```html
{{ $name }}
{{ $user->email }}
```

#### Pipe (filter)
```html
{{ $name | uppercase }}
{{ $name | uppercase: 'param1' }}
```

#### Điều kiện
```html
@if ($condition) {
    <p>True</p>
} @elseif ($other) {
    <p>Other</p>
} @else {
    <p>False</p>
}

@empty ($data) {
    <p>Data is empty</p>
}

@notEmpty ($data) {
    <p>Data is not empty</p>
}
```

#### Vòng lặp
```html
@for ($i = 0; $i < 10; $i++) {
    <p>Item {{ $i }}</p>
}

@foreach ($items as $item) {
    <div>{{ $item->name }}</div>
}

@forelse ($items as $item) {
    <div>{{ $item->name }}</div>
} @empty {
    <p>No items found</p>
}
```

#### Control flow
```html
@break
@continue
```

#### Switch
```html
@switch($role) {
    @case('admin') {
        <p>Admin panel</p>
        @break
    }
    @case('user') {
        <p>User dashboard</p>
        @break
    }
    @default {
        <p>Guest</p>
    }
}
```

#### PHP thuần
```html
@php {
    $name = 'Hola';
    $computed = strtoupper($name);
}
```

#### Layout inheritance
```html
<!-- Layout: App/Views/layouts/main.view.php -->
<!DOCTYPE html>
<html>
<head><title>App</title></head>
<body>
    @yield('content')
    @yield('footer')
</body>
</html>

<!-- Child: App/Views/home.view.php -->
@inherit('layouts.main')

@startContent('content')
<h1>Home Page</h1>
<p>Welcome!</p>
@stopContent

@startContent('footer')
<footer>Footer here</footer>
@stopContent
```

#### Include
```html
@include('partials.header')
@include('partials.sidebar')
```

#### HTML attributes
```html
<div @class(['btn', 'btn-primary'])>Click</div>
<div @style(['color: red', 'font-size: 16px'])>Styled</div>
<input @checked($isActive)>
<option @selected($isDefault)>Option</option>
<input @disabled($isDisabled)>
<input @readonly($isReadonly)>
```

#### Event binding JS
```html
<button @click="handleClick()">Click</button>
<input @input="handleInput()">
<select @change="handleChange()">
```

#### CSRF Token
```html
<form method="POST">
    <?php csrfToken(); ?>
</form>
```

#### SEO Meta Tags
```php
// Trong controller
return Response::metaTag([
    'title' => 'Page Title',
    'description' => 'Description',
])->view('home', $data);
```

Trong view hiển thị:
```html
<head>
    {{ $metaTags }}
</head>
```

### Cache view HTML

Trong `App/App.php`:
```php
public function register() {
    ViewRender::cacheFileHtml([
        'welcome',        // Cache App/Views/welcome.view.php thành HTML tĩnh
        'about',
    ]);
}
```

---

## 12. Collection

### Class: `Hola\Data\Collection`

Collection bao bọc data dạng object, hỗ trợ chuỗi method (chainable).

```php
// Tạo collection
$collection = collection([
    ['id' => 1, 'name' => 'John', 'role' => 'admin'],
    ['id' => 2, 'name' => 'Jane', 'role' => 'user'],
    ['id' => 3, 'name' => 'Bob', 'role' => 'user'],
]);

// Hoặc
$collection = new \Hola\Data\Collection($data);

// Convert
$collection->toArray();          // Về array
$collection->toObject();         // Về stdClass
$collection->values();           // Về indexed array
$collection->keys();             // Lấy keys

// Truy xuất
$collection->value('name');      // Lấy giá trị 'name' của phần tử đầu tiên
$collection->last();             // Phần tử cuối cùng
$collection->count();            // Đếm
$collection->isEmpty();          // Kiểm tra rỗng
$collection->exists('key');      // Kiểm tra key tồn tại

// Biến đổi
$collection->map(function ($item) {
    $item->name = strtoupper($item->name);
    return $item;
});
$collection->mapFirst(function ($item) {
    return $item; // chỉ transform phần tử đầu
});
$collection->filter(function ($item) {
    return $item->role === 'admin';
});
$collection->forEach(function ($item, $key) {
    // side effect
});

// Thao tác
$collection->push($newItem);
$collection->add($item, 'key');   // Thêm với key
$collection->merge($otherData);
$collection->union();
$collection->except(['key1']);     // Loại bỏ keys
$collection->only(['key1']);       // Chỉ giữ keys
$collection->chunk(2);            // Chia nhóm
$collection->flat();              // Flatten
$collection->dataColumn('name');  // Lấy 1 cột giá trị (pluck)
$collection->set($newData);       // Thay thế data

// Sắp xếp
$collection->sortBy('name');
$collection->sortBy('name', 'desc');
$collection->sortByAsc('name');
$collection->sortByDesc('name');

// Nhóm
$collection->group('role');

// Phân trang thủ công
$collection->limit(10);
$collection->offset(5);

// JSON
json_encode($collection);        // JsonSerializable
```

---

## 13. Container (Dependency Injection)

### Class: `Hola\Container\Container`

```php
// Lấy container instance
$container = app();

// Tạo instance (auto resolve dependencies)
$instance = app()->make(SomeClass::class);
// Hoặc shortcut
$instance = app(SomeClass::class);

// Đăng ký singleton
app()->singleton(ServiceInterface::class, function () {
    return new ServiceImplementation();
});

// Đăng ký singleton nếu chưa có
app()->singletonIf(ServiceInterface::class, function () {
    return new ServiceImplementation();
});

// Kiểm tra
app()->bound(ServiceInterface::class);   // Đã đăng ký?
app()->resolved(ServiceInterface::class); // Đã resolve?
app()->has(ServiceInterface::class);      // bound || resolved

// Lấy instance đã resolve
app()->get(ServiceInterface::class);

// Gọi method với auto dependency injection
app()->call([UserController::class, 'index']);

// Request & Response singletons
app()->request();   // Hola\Transport\Request
app()->response();  // Hola\Transport\Response
app()->event();     // Hola\Events\AppEvents
```

### Đăng ký DI trong `App/App.php`

```php
class App extends Application {
    public function register() {
        // Bind interface → implementation
        $this->set(BlogInterface::class, BlogRepository::class);

        // Singleton
        $this->singleton(PaymentService::class, function () {
            return new StripePayment(conval('STRIPE_KEY'));
        });
    }
}
```

---

## 14. Events

### Class: `Hola\Events\AppEvents`

```php
// Đăng ký event trong App::registerEvent()
public function registerEvent() {
    // Lắng nghe mọi request
    app()->event()->listenRequest(function ($data) {
        // $data chứa: type, method, uri, matches
        logs()->write([$data['method'] . ' ' . $data['uri']]);
    });

    // Lắng nghe exceptions
    app()->event()->listenException(function ($data) {
        // $data chứa: type, message, code, line, file, trace, class, previous
        // Gửi notification, log, v.v.
    });
}

// Built-in events
// 'app.start'       - Khi app khởi động
// 'app.request'     - Khi có HTTP request
// 'app.exceptions'  - Khi có exception
// 'app.model'       - Khi model thực hiện query (create, update, delete, get)

// Trigger custom event
app()->event()->trigger('custom.event', ['key' => 'value']);

// Trigger once (chỉ chạy 1 lần rồi remove)
app()->event()->triggerOnce('one.time.event', $data);

// Kiểm tra event
app()->event()->hasEvent('custom.event');       // bool
app()->event()->getListeners('custom.event');   // array

// Xóa listeners
app()->event()->clearListeners('custom.event');
app()->event()->clearAll();
```

---

## 15. Cache

### Cấu hình: `config/cache.php`

```php
return [
    'default' => 'file',           // 'file', 'redis', 'apc'
    'stores' => [
        'file' => ['driver' => 'file', 'path' => 'storage/cache', 'expire' => 3600],
        'redis' => ['driver' => 'redis', 'expire' => 3600],
    ],
    'prefix' => 'cache_',
];
```

### Sử dụng

```php
// Helper function
$cache = cache();

// Chọn driver
cache()->file();                     // File cache
cache()->redis();                    // Redis cache
cache()->apc();                      // APCu cache

// Các thao tác
cache()->file()->store('key', $data, $ttl);       // Lưu cache ($ttl tính bằng giây)
cache()->file()->get('key');                        // Đọc cache
cache()->file()->has('key');                        // Kiểm tra tồn tại
cache()->file()->delete('key');                     // Xóa
cache()->file()->clear();                           // Xóa tất cả

// Get or Store (lấy nếu có, ngược lại lưu và trả)
cache()->file()->getOrStore('key', $data, $ttl);

// Set path cho file cache
cache()->file()->setPath('storage/cache');

// Set prefix
cache()->file()->setPrefix('my_prefix_');

// Dùng Redis
cache()->redis()->store('key', $value, 3600);
cache()->redis()->get('key');
```

---

## 16. Rate Limiter

### Class: `Hola\Data\Cache\RateLimit\RateLimiter`

```php
use Hola\Data\Cache\RateLimit\RateLimiter;

$rateLimit = new RateLimiter(
    maxAttempts: 5,       // Số lần tối đa
    delaySeconds: 60,     // Thời gian reset (giây)
    limiter: 'file'       // 'file' hoặc 'redis'
);

$rateLimit->setKey('api:login');

if ($rateLimit->tooManyAttempts()) {
    return Response::json(['message' => 'Too many attempts'])->setStatus(429);
}

$rateLimit->increment('api:login');

// Các method khác
$rateLimit->decrement('api:login');
$rateLimit->attempts();             // Số lần đã thử
$rateLimit->maxAttempts();          // Giới hạn tối đa
$rateLimit->setMaxAttempts(10);
$rateLimit->setDelaySeconds(120);
$rateLimit->useLimiter('redis');    // Đổi driver
```

---

## 17. Session & Cookie

### Session

```php
use Hola\Data\Session;

// Phải bật session trong bootstrap.php
// $appRegister->registerSession();

Session::set('key', 'value');
Session::get('key');
Session::remove('key');
Session::isExited('key');     // bool

// Qua Request
$request->session('key');              // Lấy giá trị
$request->session()->set('k', 'v');    // Set
$request->session()->get('k');         // Get
$request->session()->remove('k');      // Remove
```

### Cookie

```php
use Hola\Data\Cookie;

Cookie::set('key', 'value', 3600);    // 3600 giây (1 giờ)
Cookie::get('key');
Cookie::remove('key');
Cookie::isExited('key');               // bool

// Qua Request
$request->cookie('key');
$request->cookie()->set('k', 'v', 7200);
```

---

## 18. Mailing

### Tạo Mail class

File: `App/Mails/WelcomeMail.php`

```php
<?php
namespace App\Mails;
use Hola\Mailing\MailerBuilder;
use Hola\Transport\Response;

class WelcomeMail extends MailerBuilder {
    private $user;

    public function __construct($user) {
        parent::__construct();
        $this->user = $user;
    }

    public function mailFrom() {
        return 'noreply@example.com';
    }

    public function mailFromName() {
        return 'My App';
    }

    public function mailTo() {
        return $this->user['email'];
    }

    public function title() {
        return 'Welcome to My App!';
    }

    // Trả HTML string hoặc ResponseBuilder (view render)
    public function view() {
        return Response::view('emails.welcome', [
            'name' => $this->user['name']
        ]);
    }

    // (Tùy chọn) Xử lý khi gửi fail
    public function failed(\Throwable $e) {
        logs()->write(['Mail failed: ' . $e->getMessage()]);
    }
}
```

### Gửi mail

```php
use App\Mails\WelcomeMail;

$mail = new WelcomeMail($user);
$success = $mail->send();  // true/false
```

### Gửi mail trực tiếp (không cần class)

```php
use Hola\Mailing\Mailer;

$mailer = new Mailer();
$mailer->config()                          // Auto load từ .env
    ->from('from@example.com', 'Sender')
    ->to('to@example.com')
    ->setSubject('Hello')
    ->setBody('<h1>Hi</h1>')
    ->withHTML()
    ->withAttachment('/path/file.pdf')
    ->withCC(['cc@example.com'])
    ->withBCC(['bcc@example.com'])
    ->work();
```

### Cấu hình SMTP trong `.env`

```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=user@gmail.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=ssl
MAIL_AUTH=true
MAIL_CHARSET=UTF-8
MAIL_DEBUG=0
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="My App"
```

---

## 19. Queue Jobs

### Cấu hình: `config/queue.php`

```php
return [
    "default" => "database",              // Driver: 'database', 'redis', 'rabbitmq'
    "default_connections" => "database",
    "queue_default" => "jobs",             // Tên queue mặc định
    "timeout" => 600,                      // Timeout (giây)
    "connections" => [
        "database" => [ ... ],
        "redis" => [ ... ],
        "rabbitmq" => [ ... ],
    ]
];
```

### Tạo Job

File: `App/QueueJobs/SendEmailJob.php`

```php
<?php
namespace App\QueueJobs;

class SendEmailJob {
    public $email;
    public $subject;

    public function __construct($email, $subject) {
        $this->email = $email;
        $this->subject = $subject;
    }

    // Method bắt buộc
    public function handle() {
        // Logic xử lý job
        // $this->email, $this->subject có sẵn
    }
}
```

### Dispatch Job

```php
use App\QueueJobs\SendEmailJob;

// Cách 1: Dùng helper
sendJobs(new SendEmailJob('user@mail.com', 'Hello'));

// Với options
sendJobs(
    job: new SendEmailJob('user@mail.com', 'Hello'),
    queue_name: 'emails',
    drive: 'redis',
    connection: 'redis',
    timeout: 120
);

// Cách 2: Dùng CreateQueue trực tiếp
use Hola\Queue\CreateQueue;

$queue = CreateQueue::instance();
$queue->setQueue('emails');
$queue->driver('redis');
$queue->connection('redis');
$queue->setTimeOut(120);
$queue->enQueue(new SendEmailJob('user@mail.com', 'Hello'));
```

### Chạy Queue Worker

```bash
php cli.php queue:work
php cli.php queue:work --queue=emails
php cli.php queue:work --connection=redis
```

---

## 20. CLI Commands

### Tạo Command

File: `App/Commands/SyncDataCommand.php`

```php
<?php
namespace App\Commands;
use Hola\Core\Command;

class SyncDataCommand extends Command {
    public function __construct() {
        parent::__construct();
    }

    protected $command = "sync:data";
    protected $command_description = "Sync data from external API";

    // Arguments: required hoặc optional (prefix ?)
    protected $arguments = ['source', '?limit'];

    // Options: required hoặc optional (prefix ?)
    protected $options = ['--force', '?--dry-run'];

    public function handle() {
        $source = $this->getArgument('source');
        $limit = $this->getArgument('limit');   // null nếu không truyền
        $force = $this->getOption('--force');

        // Output
        $this->output()->title('Syncing data...');
        $this->output()->info("Source: $source");
        $this->output()->success('Done!');
        $this->output()->error('Failed!');
        $this->output()->warning('Warning!');
        $this->output()->note('Note...');
        $this->output()->table(['ID', 'Name'], [
            [1, 'John'],
            [2, 'Jane'],
        ]);

        // Progress bar
        $progressBar = $this->createProgressBar(100);
        $progressBar->start();
        for ($i = 0; $i < 100; $i++) {
            $progressBar->advance();
        }
        $progressBar->finish();
    }
}
```

### Chạy command

```bash
php cli.php sync:data api_source --force
php cli.php sync:data api_source 50 --dry-run
```

### Built-in commands

```bash
php cli.php list                    # Danh sách commands
php cli.php queue:work              # Chạy queue worker
php cli.php migrate                 # Chạy migration
php cli.php migrate:rollback        # Rollback migration
php cli.php cache:clear             # Xóa cache
```

---

## 21. HTTP Client (Curl)

### Class: `Hola\Core\Curl`

```php
use Hola\Core\Curl;

// GET request
$response = Curl::init()->get('https://api.example.com/users');

// GET với query params
$response = Curl::init()->get('https://api.example.com/users', ['page' => 1, 'limit' => 10]);

// POST request
$response = Curl::init()->post('https://api.example.com/users', [
    'name' => 'John',
    'email' => 'john@mail.com'
]);

// PUT, PATCH, DELETE
$response = Curl::init()->put('https://api.example.com/users/1', $data);
$response = Curl::init()->patch('https://api.example.com/users/1', $data);
$response = Curl::init()->delete('https://api.example.com/users/1');

// JSON request + response
$response = Curl::init()
    ->asJson()
    ->post('https://api.example.com/users', $data);

// Headers
$response = Curl::init()
    ->headers([
        'Content-Type' => 'application/json',
        'Accept' => 'application/json'
    ])
    ->bearer('your-token')
    ->get('https://api.example.com/me');

// Authorization
Curl::init()->authorization('Bearer your-token');
Curl::init()->bearer('your-token');

// Content type
Curl::init()->contentType('application/json');

// Timeout
Curl::init()
    ->timeout(10)           // Request timeout (giây)
    ->connectTimeout(5)     // Connect timeout (giây)
    ->get($url);

// Upload file
Curl::init()
    ->withFile('avatar', '/path/to/file.jpg', 'image/jpeg', 'avatar.jpg')
    ->post('https://api.example.com/upload');

// Download file
Curl::init()->download('https://example.com/file.zip', '/save/path/file.zip');

// Response object (with headers + status)
$response = Curl::init()
    ->responseObject()
    ->get($url);
// $response->content, $response->status, $response->contentType

// Response array
$response = Curl::init()->responseArray()->get($url);

// Retry
$response = Curl::init()
    ->retry(3, 1000)   // 3 lần, delay 1 giây
    ->get($url);

// Retry with condition
$response = Curl::init()
    ->retry(3, 500, function ($info) {
        return $info['httpCode'] >= 500; // Chỉ retry khi 5xx
    })
    ->get($url);

// Proxy
Curl::init()
    ->withProxy('proxy.example.com', '8080', '', 'user', 'pass')
    ->get($url);

// Debug
Curl::init()
    ->enableDebug('/path/to/debug.log')
    ->get($url);

// Follow redirects
Curl::init()->allowRedirect()->get($url);

// Response headers
Curl::init()->responseHeaders()->get($url);
```

---

## 22. Date Utility

### Class: `Hola\Core\Date`

```php
use Hola\Core\Date;

// Thời gian hiện tại
$now = Date::init()->now()->get();                    // "2024-01-15 10:30:00"

// Custom format
$date = Date::init()->now()->format('d/m/Y')->get();  // "15/01/2024"

// Set date
$date = Date::init()->set('2024-01-15')->get();

// Set từ timestamp string
$date = Date::init()->setTimestamp('2024-01-15 10:00:00')->get();

// Get timestamp
$ts = Date::init()->now()->getTimestamp();

// Timezone
$date = Date::init()->setTimezone('America/New_York')->now()->get();

// Tính toán
Date::init()->now()->addDay(7)->get();         // +7 ngày
Date::init()->now()->subDay(3)->get();         // -3 ngày
Date::init()->now()->addWeek(2)->get();        // +2 tuần
Date::init()->now()->subWeek(1)->get();
Date::init()->now()->addMonth(1)->get();       // +1 tháng
Date::init()->now()->subMonth(2)->get();
Date::init()->now()->addYear(1)->get();        // +1 năm
Date::init()->now()->subYear(1)->get();
Date::init()->now()->addHour(3)->get();        // +3 giờ
Date::init()->now()->subHour(2)->get();
Date::init()->now()->addMinute(30)->get();     // +30 phút
Date::init()->now()->subMinute(15)->get();
Date::init()->now()->addSeconds(90)->get();    // +90 giây
Date::init()->now()->subSeconds(30)->get();

// Kết hợp
$date = Date::init()
    ->now()
    ->addDay(7)
    ->addHour(3)
    ->format('Y-m-d H:i:s')
    ->get();
```

---

## 23. File Management

### Class: `Hola\Core\Files`

```php
use Hola\Core\Files;

$files = Files::instance();

// Upload file
$result = $files->upload(
    $request->file('avatar'),    // file object từ Request
    'uploads/avatars',           // đường dẫn lưu
    'my_avatar',                 // tên file (không extension)
    'jpg'                        // extension (mặc định từ file gốc)
);
// Kết quả: ['uploaded' => 1, 'url' => '...', 'filename' => '...', 'msg' => 'Success']

// Upload + resize
$result = $files->uploadAndResizeImage(
    $request->file('avatar'),
    'uploads/avatars',
    'avatar_resized',
    'jpg',
    200,    // width
    200,    // height
    75      // quality
);

// Xóa file
$result = $files->removeFile('uploads/avatars/my_avatar.jpg');

// Tạo thumbnail
$files->make_thumb('source.jpg', 'thumb.jpg', 150);

// Crop ảnh vuông
$files->cropImageToSquare('source.jpg', 'output/', 'cropped', 200, 0, 0, 100);

// Resize ảnh
$files->resizeImage('source.jpg', 'output/', 'resized', 800, 600, 75);

// Copy file
$files->copy('source.txt', 'dest.txt');

// Tạo thumbnail trả về binary (cho response)
$thumbBinary = $files->create_thumb('source.jpg', 150);
```

---

## 24. Helper Functions (Global)

### App & Container

```php
app();                          // Container instance
app(SomeClass::class);         // Make instance
```

### Config

```php
config();                       // ConfigApp instance
config('database.default');     // Lấy config value (dot notation)
config()->get('cache.stores');
config()->set('cache.default', 'redis');
config()->all();
conval('APP_DEBUG', false);     // Lấy env variable với default
const_get('SOME_CONSTANT', 'default');
```

### Response & View

```php
res();                          // ResponseBuilder instance
view_root('test.index');        // Full path tới view file
url('assets/css/style.css');    // Full URL
path_root('public/image.jpg'); // Full server path
```

### Data & Collection

```php
collection($array);             // Tạo Collection
convert_to_array($value);       // Convert sang array
convert_to_object($value);      // Convert sang object
```

### String & Utils

```php
str_slug('Hello World');         // "hello-world" (hỗ trợ tiếng Việt)
startsWith($str, 'prefix');     // bool
endsWith($str, 'suffix');       // bool
concat('-', 'a', 'b', 'c');    // "a-b-c"
uid();                           // UUID v4
generateKey(32);                 // Random string
```

### Validation & Check

```php
isDate('2024-01-15');            // bool
isTwoDimensionalArray($arr);    // bool
isTwoDimensionalObject($obj);   // bool
isXml($string);                  // bool
```

### I18n (Ngôn ngữ)

```php
__('welcome');                            // Dịch key
__('hello', ['name' => 'John']);          // Dịch với placeholder: "Hello {{name}}"
__('welcome', [], 'en');                  // Dịch sang ngôn ngữ cụ thể
translate('welcome');                      // Giống __ nhưng dùng config
lang_has('welcome');                       // Kiểm tra key tồn tại
```

### Share Data

```php
share();                         // ShareData instance
share()->set('key', 'value');
share()->get('key');
share()->create('key', $data);
share()->all();
val('key', 'default');           // Lấy shared data
errors('field_name');            // Lấy validation error
```

### Logging

```php
logs()->write(['message 1', 'message 2']);           // Ghi vào storage/application.log
logs()->write(['message'], 'custom_log');            // Ghi vào storage/custom_log.log
logs()->debug(['debug info']);                        // Ghi vào storage/debug.log
logs()->write_error($exception);                     // Ghi exception
logs()->dump($variable);                             // Debug output (dừng app)
dump($var1, $var2);                                  // Debug nhiều biến (dừng app)
```

### Queue

```php
sendJobs(new SomeJob($data));                        // Gửi job vào queue
sendJobs(new SomeJob($data), 'queue_name', 'redis'); // Với options
```

### Cache

```php
cache()->file()->store('key', $data);
cache()->file()->get('key');
cache()->redis()->store('key', $data, 3600);
```

### Session & Cookie

```php
// Trong request context
$request->session('key');
$request->cookie('key');
```

### CSRF

```php
csrfToken();                     // Echo hidden input trong form
```

### File System

```php
createFolder('path/to/dir');     // Tạo thư mục
getFolder('/path/to/file.php');  // dirname
rglob('path/*.php');             // Recursive glob
```

---

## 25. Config

### Tạo file config

Đặt trong `config/`, tự động load. Ví dụ `config/app.php`:

```php
<?php
return [
    'name' => conval('APP_NAME', 'Hola App'),
    'debug' => conval('APP_DEBUG', false),
    'timezone' => conval('TIMEZONE', 'Asia/Ho_Chi_Minh'),
];
```

### Truy cập config

```php
config('app.name');              // 'Hola App'
config('database.connections.mysql.host');
config()->get('cache.default');
config()->set('app.debug', true);
config()->all();                 // Tất cả config
```

### Environment variables

File `.env`:
```env
APP_NAME="Hola App"
APP_DEBUG=true
PROJECT_KEY=your-secret-key
DB_HOST=127.0.0.1
DB_NAME=mydb
TIMEZONE=Asia/Ho_Chi_Minh
LANGUAGE=vi
```

Truy cập:
```php
conval('APP_NAME', 'default');
conval('APP_DEBUG', false);
```

### Cache config

Config được cache tự động vào `storage/cache/cache_configs.cache`. Xóa file này để reload config.

---

## 26. Share Data

### Class: `Hola\Data\ShareData`

Dùng để chia sẻ data giữa các component (controller → view, middleware → controller):

```php
// Set data
share()->set('user', $userData);
share()->create('key', $value);

// Get data
$user = share()->get('user');
$user = val('user');             // Helper
$user = val('user', 'default'); // Với default

// Errors (từ FormRequest validation)
share()->setErrors('email', 'Email is required');
$error = errors('email');        // Helper

// All data
$all = share()->all();
```

---

## 27. Logging

```php
// Ghi log vào storage/application.log
logs()->write(['Error: something went wrong']);
logs()->write(['Custom message'], 'custom_filename');

// Debug log
logs()->debug(['Variable value: ' . json_encode($data)]);

// Log exception
try {
    // ...
} catch (\Throwable $e) {
    logs()->write_error($e);
}

// Debug output (hiển thị visual và exit)
dump($variable);
dump($var1, $var2, $var3);
logs()->dump($data);
```

### Log format

```
[2024-01-15 10:30:00][500]: Error message in /path/to/file.php on line 42
Stack trace...
```

---

## Tổng kết Quick Reference

| Tác vụ | Code |
|--------|------|
| Tạo route GET | `Router::get('/path', [Controller::class, 'method'])` |
| Route với middleware | `Router::middleware('auth')->get('/path', [...])` |
| Route group | `Router::prefix('/api')->middleware('auth')->group(fn($r) => ...)` |
| Lấy request data | `$request->all()`, `$request->get('key')`, `$request->value('key')` |
| JSON response | `Response::json($data)` hoặc `return $array` |
| View response | `Response::view('view.name', $data)` |
| Redirect | `Response::redirect('/path')` |
| Query tất cả | `Model::get()` |
| Query tìm theo id | `Model::find($id)` |
| Query điều kiện | `Model::where('col', 'val')->get()` |
| Insert | `Model::create($data)` |
| Update | `Model::update($data, $id)` |
| Delete | `Model::delete($id)` |
| Pagination | `Model::paginationWithCount(10, 1)` |
| Eager loading | `Model::with('relation')->get()` |
| Validation | `Validation::create($data, $rules)` |
| Cache | `cache()->file()->store('key', $data)` |
| Send mail | `(new WelcomeMail($user))->send()` |
| Queue job | `sendJobs(new SomeJob($data))` |
| Log | `logs()->write(['message'])` |
| Env variable | `conval('KEY', 'default')` |
| Config | `config('database.default')` |
| DI container | `app(SomeClass::class)` |
| Collection | `collection($data)->filter(fn($i) => ...)->toArray()` |
| Date | `Date::init()->now()->addDay(7)->get()` |
| File upload | `Files::instance()->upload($file, 'path')` |
| HTTP client | `Curl::init()->asJson()->get($url)` |
| CLI command | `php cli.php command:name` |

