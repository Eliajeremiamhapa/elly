<?php
session_start();
$host = "sql204.hstn.me";
$user = "mseet_38780191";
$pass = "EliaB2005";
$db = "mseet_38780191_voting1";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$reg = "";
$loginError = "";
$successMessage = "";
if (!isset($_SESSION['login_attempts'])) $_SESSION['login_attempts'] = 0;

// LOGOUT
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// LOGIN
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $reg = trim($_POST['reg']);
    $pwd = trim($_POST['pwd']);

    if ($reg == "123456789" && $pwd == "123") {
        $_SESSION['role'] = "committee_add";
    } elseif ($reg == "12345678" && $pwd == "123") {
        $_SESSION['role'] = "committee_result";
    } elseif (preg_match("/^231005333500\d+$/", $reg) && $pwd == "123") {
        $stmt = $conn->prepare("SELECT * FROM votes WHERE reg_number = ?");
        $stmt->bind_param("s", $reg);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $loginError = "❌ You have already voted!";
        } else {
            $_SESSION['reg'] = $reg;
            $_SESSION['role'] = "student";
        }
    } else {
        $_SESSION['login_attempts']++;
        $loginError = "❌ Invalid registration number or password!";
        if ($_SESSION['login_attempts'] >= 5) {
            $loginError .= "<br>ℹ️ Try reg: 2310053335001, pass: 123";
        }
    }
}

// ADD LEADER
if (isset($_POST['add_leader']) && $_SESSION['role'] == 'committee_add') {
    $name = $_POST['name'];
    $desc = $_POST['desc'];
    $img = $_POST['img'];
    $stmt = $conn->prepare("INSERT INTO leaders (name, description, image_url) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $desc, $img);
    $stmt->execute();
    $successMessage = "✅ Leader added successfully!";
}

// SUBMIT VOTE
if (isset($_POST['vote_submit']) && $_SESSION['role'] == 'student') {
    $reg = $_SESSION['reg'];
    if (!empty($_POST['leaders'])) {
        foreach ($_POST['leaders'] as $lid) {
            $stmt = $conn->prepare("INSERT INTO votes (reg_number, leader_id) VALUES (?, ?)");
            $stmt->bind_param("si", $reg, $lid);
            $stmt->execute();
        }
    }
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MUST Voting System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {box-sizing: border-box; font-family: 'Segoe UI', sans-serif;}
    body {
      margin: 0; padding: 0;
      background: linear-gradient(to right, #004d40, #00796b);
      color: #fff; min-height: 100vh;
      display: flex; justify-content: center; align-items: center;
      padding: 20px;
    }
    .container {
      background: #fff; color: #000;
      padding: 30px; border-radius: 16px;
      box-shadow: 0 0 30px rgba(0,0,0,0.3);
      width: 100%; max-width: 960px;
    }
    h2 {text-align: center; color: #004d40;}
    form {margin-top: 20px;}
    input, textarea {
      width: 100%; padding: 12px; margin-bottom: 15px;
      border-radius: 6px; border: 1px solid #ccc;
    }
    button {
      padding: 12px; background: #00796b; color: white;
      border: none; border-radius: 6px; cursor: pointer;
      width: 100%;
    }
    .error {color: red; text-align: center;}
    .success {color: green; text-align: center;}
    .leader-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
    }
    .leader-card {
      background: #f1f1f1;
      border-radius: 12px;
      padding: 16px; text-align: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .leader-card img {
      width: 100%; height: 200px; object-fit: cover;
      border-radius: 8px;
    }
    .leader-card h3 {margin: 10px 0 5px; color: #004d40;}
    .leader-card p {font-size: 14px;}
  </style>
  <script>
    // Limit checkbox selection to 2
    document.addEventListener("DOMContentLoaded", function () {
      const form = document.querySelector("form");
      const checkboxes = document.querySelectorAll("input[type='checkbox'][name='leaders[]']");
      if (form && checkboxes.length) {
        form.addEventListener("submit", function (e) {
          const selected = Array.from(checkboxes).filter(cb => cb.checked);
          if (selected.length > 2) {
            alert("❌ You can only vote for TWO leaders.");
            e.preventDefault();
          }
        });
      }
    });
  </script>
</head>
<body>
<div class="container">
  <?php if (isset($_GET['success'])): ?>
    <h2 class="success">✅ Vote submitted successfully! Please log in again.</h2>
    <a href="<?= $_SERVER['PHP_SELF'] ?>"><button>Back to Login</button></a>

  <?php elseif (!isset($_SESSION['role'])): ?>
    <h2>Login</h2>
    <?php if ($loginError): ?><p class="error"><?= $loginError ?></p><?php endif; ?>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
      <input type="text" name="reg" placeholder="Registration Number" required>
      <input type="password" name="pwd" placeholder="Password" required>
      <button type="submit" name="login">Login</button>
    </form>

  <?php elseif ($_SESSION['role'] == 'student'): ?>
    <h2>Vote for Your Leaders</h2>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
      <div class="leader-grid">
        <?php
        $leaders = $conn->query("SELECT * FROM leaders");
        while ($l = $leaders->fetch_assoc()): ?>
          <div class="leader-card">
            <img src="<?= $l['image_url'] ?>" alt="">
            <h3><?= $l['name'] ?></h3>
            <p><?= $l['description'] ?></p>
            <label><input type="checkbox" name="leaders[]" value="<?= $l['id'] ?>"> ✔ Vote</label>
          </div>
        <?php endwhile; ?>
      </div>
      <button type="submit" name="vote_submit">Submit Vote</button>
    </form>

  <?php elseif ($_SESSION['role'] == 'committee_add'): ?>
    <h2>Add Leader</h2>
    <?php if ($successMessage): ?><p class="success"><?= $successMessage ?></p><?php endif; ?>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
      <input type="text" name="name" placeholder="Leader Name" required>
      <textarea name="desc" placeholder="Leader Description" required></textarea>
      <input type="text" name="img" placeholder="Image URL" required>
      <button type="submit" name="add_leader">Add Leader</button>
    </form>
    <a href="<?= $_SERVER['PHP_SELF'] ?>?logout=1"><button>Logout</button></a>

  <?php elseif ($_SESSION['role'] == 'committee_result'): ?>
    <h2>Voting Results</h2>
    <div class="leader-grid">
      <?php
      $res = $conn->query("SELECT leaders.*, COUNT(votes.id) AS total 
                           FROM leaders LEFT JOIN votes ON leaders.id = votes.leader_id 
                           GROUP BY leaders.id ORDER BY total DESC");
      while ($l = $res->fetch_assoc()): ?>
        <div class="leader-card">
          <img src="<?= $l['image_url'] ?>" alt="">
          <h3><?= $l['name'] ?></h3>
          <p><?= $l['description'] ?></p>
          <strong>Votes: <?= $l['total'] ?></strong>
        </div>
      <?php endwhile; ?>
    </div>
    <a href="<?= $_SERVER['PHP_SELF'] ?>?logout=1"><button>Logout</button></a>
  <?php endif; ?>
</div>
</body>
</html>
