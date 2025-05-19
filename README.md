# MUST Voting System

## 👩‍💻 Developed By
- Christina
- Elia

## 🏫 Institution
Mbeya University of Science and Technology (MUST)  
Bachelor of Science in Computer Engineering

---

## 📘 Project Description
The MUST Voting System is a responsive, web-based application designed to allow students at Mbeya University of Science and Technology to securely vote for their preferred leaders. It also includes administrative access for managing candidates and viewing real-time vote results.

---

## ⚙️ Technologies Used
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL

---

## 📁 Project Files
- `voting_system.php` – Single file containing all logic (login, voting, admin, logout)
- `database.sql` – SQL file to initialize the database and tables
- `MUST_Voting_System_Database_Design.docx` – Documentation explaining the database structure
- `README.md` – This file

---

## 🔐 User Roles & Access

| Role                | Registration Number | Password | Access Description                 |
|---------------------|---------------------|----------|------------------------------------|
| Student             | 231005333500X       | 123      | Vote for **up to 2** leaders       |
| Committee (Add)     | 123456789           | 123      | Add new leaders                    |
| Committee (Results) | 12345678            | 123      | View voting results                |

> Students are allowed to vote **only once**, and for a maximum of **two leaders**. Repeat voting is prevented by the system.

---

## 🧪 Sample Test Accounts

| reg_number         | password |
|--------------------|----------|
| 2310053335001      | 123      |
| 2310053335002      | 123      |
| 123456789          | 123      |
| 12345678           | 123      |

---

## 🛠️ How to Use the System

1. Import `database.sql` into your MySQL server (e.g., via phpMyAdmin).
2. Copy `voting_system.php` into your web server folder (e.g., `htdocs` for XAMPP).
3. Start your local server (Apache and MySQL).
4. Open your browser and go to:  
   `http://localhost/voting_system.php`
5. Log in with a valid registration number and password to access your role-based panel.

---

## ✅ Key Features

- 📋 Student login validation
- ✔️ One-time voting (max 2 leaders per student)
- 🧍 Admin login to add new leader profiles
- 📊 Committee dashboard to view live voting results
- 📱 Fully responsive layout for mobile, tablet, and desktop
- 🧼 Session and logout handling with redirection
- 💡 Front-end validation with JavaScript for vote limits

---

## 📌 Notes

- Passwords are stored in plain text for simplicity in learning projects.
- Frontend enforces the 2-vote limit with JavaScript validation.
- Redirection and login handling are session-based.
- Students **must not reuse registration numbers** after voting.

---

## 🧠 Credits

This project was built as part of a course assignment at Mbeya University of Science and Technology.  
Designed and implemented by:
- **Christina**
- **Elia**

