# Task & Time Tracker

A lightweight PHP-based task and time tracking system for managing tasks, tracking time spent, and viewing productivity statistics. Built with a simple PHP structure (models, services, views) and JSON-based storage—no database required.

---

## 📌 Features
- User login system  
- Create, edit, and list tasks  
- Start & stop timers  
- Track total time spent per task  
- Productivity statistics dashboard  
- JSON file data storage  
- Developer & User models  
- Clean, organized architecture  

---

## 📁 Project Structure
```
index.php

App/
├── Constants/
│   └── AppConstants.php
├── Helpers/
│   └── TaskStatistics.php
├── Interfaces/
│   └── TrackableInterface.php
├── Models/
│   ├── Developer.php
│   ├── Task.php
│   └── User.php
└── Services/
    └── TaskManager.php

data/
└── tasks_hamza.json

views/
├── create_task.php
├── edit_task.php
├── list_task.php
├── login.php
├── menu.php
├── start_task.php
├── statistics.php
└── stop_task.php
```

---

## 🛠 Requirements
- PHP 8.0+  
- Apache/Nginx or PHP built‑in server  
- Write access to the `/data` directory  

---

## 🚀 Installation & Setup

### 1. Extract the project  
### 2. Start a PHP server:
```
php -S localhost:8000
```

### 3. Open in browser:
```
http://localhost:8000
```

### 4. Allow write access:
```
chmod 777 data/
```

---

## 🧪 Example JSON Task Entry
```
{
  "id": 1,
  "title": "Fix login bug",
  "description": "Resolve redirect issue",
  "developer": "Hamza",
  "start_time": "2025-11-17 10:00:00",
  "end_time": "2025-11-17 11:15:00",
  "elapsed": 4500
}
```

---

## 🤝 Contributing
1. Fork the project  
2. Create a new branch  
3. Submit a pull request  

---

## 📄 License
Open-source — free to modify and use.