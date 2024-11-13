Creating an MVC (Model-View-Controller) structure for your system in PHP will involve breaking the application into three main parts: the model (database interactions), the controller (business logic), and the view (presentation of the data).

### Folder Structure:

```
/your-app/
    /controllers/
        UserController.php
        RoleController.php
        PaymentController.php
        MembershipController.php
    /models/
        User.php
        Role.php
        Permission.php
        Payment.php
        MembershipPlan.php
        Staff.php
        MemberManagement.php
    /views/
        user/
            index.php
            add.php
        role/
            index.php
            add.php
        payment/
            index.php
            add.php
        membership/
            index.php
            add.php
    /config/
        db.php
    /public/
        index.php
    /core/
        Controller.php
        Model.php
```

### 1. **Database Configuration (`db.php`)**

```php
<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'your_database_name';
    private $username = 'your_username';
    private $password = 'your_password';
    public $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
}
?>
```

### 2. **Core Classes**

#### Controller Base Class (`Controller.php`)

```php
<?php
class Controller {
    protected $view;

    public function __construct() {
        $this->view = new View();
    }

    // Method for loading models
    public function loadModel($model) {
        require_once '../models/' . $model . '.php';
        return new $model();
    }
}
?>
```

#### Model Base Class (`Model.php`)

```php
<?php
class Model {
    protected $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Method to execute queries
    public function query($sql, $params = []) {
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
?>
```

### 3. **Models**

#### User Model (`User.php`)

```php
<?php
class User extends Model {
    public function getUsers() {
        $sql = "SELECT * FROM users";
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addUser($data) {
        $sql = "INSERT INTO users (first_name, last_name, contact_info, role_id, status) VALUES (?, ?, ?, ?, ?)";
        $this->query($sql, [$data['first_name'], $data['last_name'], $data['contact_info'], $data['role_id'], $data['status']]);
    }

    public function getUser($id) {
        $sql = "SELECT * FROM users WHERE user_id = ?";
        return $this->query($sql, [$id])->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $data) {
        $sql = "UPDATE users SET first_name = ?, last_name = ?, contact_info = ?, role_id = ?, status = ? WHERE user_id = ?";
        $this->query($sql, [$data['first_name'], $data['last_name'], $data['contact_info'], $data['role_id'], $data['status'], $id]);
    }
}
?>
```

#### Role Model (`Role.php`)

```php
<?php
class Role extends Model {
    public function getRoles() {
        $sql = "SELECT * FROM roles";
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRole($id) {
        $sql = "SELECT * FROM roles WHERE role_id = ?";
        return $this->query($sql, [$id])->fetch(PDO::FETCH_ASSOC);
    }
}
?>
```

#### Membership Plan Model (`MembershipPlan.php`)

```php
<?php
class MembershipPlan extends Model {
    public function getPlans() {
        $sql = "SELECT * FROM membership_plans";
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPlan($id) {
        $sql = "SELECT * FROM membership_plans WHERE plan_id = ?";
        return $this->query($sql, [$id])->fetch(PDO::FETCH_ASSOC);
    }

    public function addPlan($data) {
        $sql = "INSERT INTO membership_plans (plan_name, description, price, duration) VALUES (?, ?, ?, ?)";
        $this->query($sql, [$data['plan_name'], $data['description'], $data['price'], $data['duration']]);
    }
}
?>
```

### 4. **Controllers**

#### User Controller (`UserController.php`)

```php
<?php
class UserController extends Controller {
    public function index() {
        $userModel = $this->loadModel('User');
        $users = $userModel->getUsers();
        $this->view->render('user/index', ['users' => $users]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            $userModel = $this->loadModel('User');
            $userModel->addUser($data);
            header('Location: /user');
        } else {
            $roleModel = $this->loadModel('Role');
            $roles = $roleModel->getRoles();
            $this->view->render('user/add', ['roles' => $roles]);
        }
    }
}
?>
```

#### Payment Controller (`PaymentController.php`)

```php
<?php
class PaymentController extends Controller {
    public function index() {
        $paymentModel = $this->loadModel('Payment');
        $payments = $paymentModel->getPayments();
        $this->view->render('payment/index', ['payments' => $payments]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            $paymentModel = $this->loadModel('Payment');
            $paymentModel->addPayment($data);
            header('Location: /payment');
        } else {
            $userModel = $this->loadModel('User');
            $users = $userModel->getUsers();
            $this->view->render('payment/add', ['users' => $users]);
        }
    }
}
?>
```

### 5. **Views**

#### User Index View (`index.php`)

```php
<h1>Users</h1>
<table>
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Contact</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php foreach ($data['users'] as $user): ?>
    <tr>
        <td><?= $user['first_name'] ?></td>
        <td><?= $user['last_name'] ?></td>
        <td><?= $user['contact_info'] ?></td>
        <td><?= $user['status'] ?></td>
        <td><a href="/user/edit/<?= $user['user_id'] ?>">Edit</a></td>
    </tr>
    <?php endforeach; ?>
</table>
<a href="/user/add">Add New User</a>
```

### 6. **Main Entry (`index.php` in /public)**

```php
<?php
require_once '../core/Controller.php';
require_once '../core/Model.php';
require_once '../config/db.php';

// Front Controller for routing
$request = $_SERVER['REQUEST_URI'];
if ($request == '/') {
    $controller = new UserController();
    $controller->index();
} elseif ($request == '/user/add') {
    $controller = new UserController();
    $controller->add();
} else {
    echo "404 Not Found";
}
?>
```

This structure provides a foundation for your PHP MVC application. Each table (like `users`, `roles`, `payments`, etc.) would have corresponding models, controllers, and views for displaying and managing the data.