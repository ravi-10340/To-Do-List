<?php
    session_start();
    if (!isset($_SESSION['userid'])) {
        header("Location:login.php");
        exit();
    }
    $userid =  $_SESSION['userid'];
    $username = $_SESSION['username'];
    $con = mysqli_connect("localhost","root","","mypdb");
    $date_today = date('Y-m-d');
    $check_query = "SELECT * FROM userlogins WHERE user_id = '$userid' AND DATE(login_time) = '$date_today'";
    $check_result = mysqli_query($con, $check_query);
    if (mysqli_num_rows($check_result) == 0) {
        mysqli_query($con, "INSERT INTO userlogins (user_id) VALUES ('$userid')");
    }
    $current_streak = 0;
    $previous_date = date('Y-m-d');
    $query = "SELECT DISTINCT DATE(login_time) as login_day FROM userlogins WHERE user_id = '$userid' ORDER BY login_day DESC";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $login_date = $row['login_day'];
        if ($login_date == $previous_date) {
            $current_streak++;
            $previous_date = date('Y-m-d', strtotime('-1 day', strtotime($previous_date)));
        } else {
            break; 
        }
    }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>To-Do-List</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="image.png" type="image/png">
    <link rel="stylesheet" href="b.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="outer">
      <div class="header d-flex align-items-center px-4 justify-content-between">
        <div class="d-flex align-items-center" style="gap :20px;margin-left:2%;">
          <div class="logo"></div>
          <div class="input-group" style="max-width: 70%;">
            <input type="text" class="form-control" placeholder="Search" style="border-radius: 30px;">
          </div>
        </div>
        <div class="d-flex align-items-center ms-auto" style="gap: 30px;margin-right:2%;">
          <div class="m"><a href="#" onclick="location.reload()">Home</a></div>
          <div class="m"><a href="#taskSection">About</a></div>
          <div class="m"><a href="#footerSection">Contact</a></div>
          <div class="m profile-menu position-relative">
            <i class="bi bi-person-circle" style="font-size: 30px; cursor: pointer;"></i>
            <div class="info">
            <a href="#" onclick="openProfile(); return false;">View Profile</a>
            <a href="settings.php">Settings</a><hr style = "margin-bottom:0px;margin-top:0px; ">
            <a href="login.php" style = "color:red">Log Out</a>
            </div>
          </div>
        </div>
      </div>
      <!-- Main Content -->
      <h2 style = "padding-left:10%;font-size:50px">Welcome , <?php echo $username;?></h2>
      <div class="center mt-4">
        <div class="l p-4 bg-light rounded shadow-sm">
          <h3 class="mb-3 text-dark fw-bold"><i class="bi bi-check2-square"></i> My Tasks</h3>
          <div class="input-group mb-3">
            <input type="text" id="taskInput" class="form-control me-2" placeholder="Enter a task">
            <input type="datetime-local" id="taskTime" class="form-control me-2" style="max-width: 220px;">
            <button class="btn btn-primary" onclick="addTask()">Add</button>
          </div>
          <ul id="taskList" class="list-group">
            <!-- Tasks will be dynamically inserted here -->
          </ul>
        </div>
        <div class="r p-3">
          <!--Streak Card -->
          <div class="card mb-4 shadow-sm">
            <div class="card-body text-center">
              <h5 class="card-title">🔥 Current Streak</h5>
              <p class="display-6 fw-bold text-primary"><?php echo "<p><strong>$current_streak</strong> day.</p>";?></p>
              <p class="card-text text-muted">Keep going! You're on a roll!</p>
            </div>
          </div>
          <!--Calendar Card -->
          <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center fw-bold" id="calendarMonthYear">Month Year</div>
            <div class="calendar-days d-grid text-center border-bottom border-2 p-2" style="grid-template-columns: repeat(7, 1fr);">
              <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
            </div>
            <div class="calendar-dates d-grid text-center p-3" id="calendarDates" style="grid-template-columns: repeat(7, 1fr); gap: 5px;">
              <!-- Dates injected here -->
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <div class="footer mt-5 text-white pt-4 pb-3 text-center" style="background-color: black;" id="footerSection">
        <!-- Social Icons Centered -->
        <div class="mb-3">
          <a href="#" class="text-white mx-3"><i class="bi bi-github"></i></a>
          <a href="https://www.linkedin.com/in/ravi-ab8b1a28a/" class="text-white mx-3"><i class="bi bi-linkedin"></i></a>
          <a href="#" class="text-white mx-3"><i class="bi bi-instagram"></i></a>
        </div>
        <!-- Email Below Icons -->
        <div class="mb-2" style="font-size: 15px;">
          <i class="bi bi-envelope-fill me-2" style="color: white;"></i>
          <a href="mailto:ravinarwal3327@gmail.com" class="text-white" style="text-decoration: none;">
            ravinarwal3327@gmail.com
          </a>
        </div>
        <!-- Copyright -->
        <div class="text-muted" style="font-size: 14px;">
          <p style = "color:white;">© <?php echo date("Y"); ?>MyProductiveApp. All rights reserved.</p>
        </div>
      </div>
      <script>//calendar ar streak
        function generateCalendar(month, year) {
          const date = new Date(year, month);
          const firstDay = new Date(year, month, 1).getDay();
          const lastDate = new Date(year, month + 1, 0).getDate();
          const today = new Date();
          const calendar = document.getElementById("calendarDates");
          const header = document.getElementById("calendarMonthYear");
          header.innerText = date.toLocaleString('default', { month: 'long', year: 'numeric' });
          calendar.innerHTML = "";
          for (let i = 0; i < firstDay; i++) {
            calendar.innerHTML += "<div></div>";
          }
          for (let d = 1; d <= lastDate; d++) {
            const isToday = today.getDate() === d && today.getMonth() === month && today.getFullYear() === year;
            calendar.innerHTML += `<div class="${isToday ? 'today' : ''}">${d}</div>`;
          }
        }
        // current month
        const now = new Date();
        generateCalendar(now.getMonth(), now.getFullYear());

        // JavaScript code: fetchTasks, addTask, deleteTask, toggleTask
        function fetchTasks() {
          fetch('task.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=fetch'
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === 'success') {
              const list = document.getElementById('taskList');
              list.innerHTML = '';
              data.tasks.forEach(task => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center task-animate';
                // Left side: checkbox + task + time
                const left = document.createElement('div');
                left.className = 'd-flex align-items-start flex-column';
                const row = document.createElement('div');
                row.className = 'd-flex align-items-center';
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.className = 'form-check-input me-2';
                checkbox.checked = task.is_done == 1;
                checkbox.onclick = () => toggleTask(task.id);
                const span = document.createElement('span');
                span.textContent = task.task;
                span.className = 'task-text' + (task.is_done ? ' text-decoration-line-through text-muted' : '');
                row.appendChild(checkbox);
                row.appendChild(span);
                left.appendChild(row);
                // Due time display
                if (task.due_time) {
                  const time = document.createElement('small');
                  time.className = 'text-muted mt-1';
                  const readableTime = new Date(task.due_time).toLocaleString();
                  time.textContent = `Due: ${readableTime}`;
                  left.appendChild(time);
                }
                // Delete button
                const delBtn = document.createElement('button');
                delBtn.innerHTML = '<i class="bi bi-trash-fill"></i>';
                delBtn.className = 'btn btn-sm btn-danger';
                delBtn.onclick = () => deleteTask(task.id);
                // Append to li
                li.appendChild(left);
                li.appendChild(delBtn);
                list.appendChild(li);
              });
            }
          });
        }
        function addTask() {
          const input = document.getElementById("taskInput");
          const timeInput = document.getElementById("taskTime");
          const taskText = input.value.trim();
          const dueTime = timeInput.value;
          if (taskText === '') return;
          fetch('task.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=add&task=${encodeURIComponent(taskText)}&due_time=${encodeURIComponent(dueTime)}`
          })
          .then(() => {
            input.value = '';
            timeInput.value = '';
            fetchTasks();
          });
        }
        function deleteTask(id) {
          fetch('task.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=delete&id=${id}`
          }).then(() => fetchTasks());
        }
        function toggleTask(id) {
          fetch('task.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=toggle&id=${id}`
          }).then(() => fetchTasks());
        }
        document.addEventListener('DOMContentLoaded', fetchTasks);
        function openProfile() {
          document.getElementById("profileBox").style.display = "block";
        }
        function closeProfile() {
          document.getElementById("profileBox").style.display = "none";
        }
    </script>
    <div id="profileBox" class="profile-box shadow p-4 bg-white rounded" style="display: none;">
      <h5 class="mb-3 border-bottom pb-2">👤 Profile Details</h5>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($username); ?></p>
      <p><strong>User ID:</strong> <?php echo htmlspecialchars($userid); ?></p>
      <p><strong>Phone:</strong> 
        <?php 
          $res = mysqli_query($con, "SELECT mobile FROM login WHERE id = '$userid'");
          $row = mysqli_fetch_assoc($res);
          echo htmlspecialchars($row['phone'] ?? 'Not Available');
        ?>
      </p>
      <div class="text-end">
        <button class="btn btn-sm btn-danger" onclick="closeProfile()">Close</button>
      </div>
    </div>
  </body>
</html>
