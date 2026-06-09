Name: Yusuph Asheri Mnyota
Reg. No. 14325009/T.24
programme: BSc MICT (EDU) 2


Student Performance Assessment System (SPAS)

This is a web-based system developed to manage student registration, manage academic subjects, calculate grades, and automatically rank students based on their average scores.


Features

1. Student Management
a) Register new students with their personal details.
b) View the complete list of all registered students.
c) Edit and update student information.

 2. Subject Management
 a) Add new academic subjects to the system.
 b) View and modify subject details.

3. Marks & Reports Management
a) Input and record student marks for each subject.
b) Automatically calculate the average score for each student.
c) Rank students from first to last based on performance.
d) View comprehensive student academic reports.



 System Requirements
 i.   PHP 7.4 or higher
 ii.  MySQL Database
 iii. XAMPP Server



Installation Guide

1. Copy the project folder (`SPAS_Project`) and paste it into your XAMPP directory:
   `C:\xampp\htdocs\`

2. Open phpMyAdmin (`http://localhost/phpmyadmin/`) and create a new database named `spas_db`.

3. Import the database file located inside the     project folder: `database/spas_db.sql`.

4. Login Credentials:
    Username: 
    Password: 



 Project File Structure

text
SPAS_Project/
├── index.php             - System landing page
├── login.php             - Authentication page
├── dashboard.php         - Main panel after login
├── logout.php            - Session termination handler
├── config/
│   └── connection.php    - Database connection file
├── css/                  - System styling (Stylesheets)
├── database/
│   └── spas_db.sql       - Database schema export file
├── students/             - Files for managing student data
├── subjects/             - Files for managing subjects
├── marks/                - Files for inputting and viewing scores
└── reports/              - Files for performance report and analysis



How it Works

 1. Student Ranking Algorithm
The system processes and calculates student positions through the following steps:
1.1. It sums up all marks for a student and divides the total by the number of subjects to determine the (Average Score).
1.2. It sorts the students from the highest average score to the lowest using `ORDER BY average_score DESC`.
1.3. It assigns Rank 1 to the student with the highest average score.



2. Database Table Structures
i) users: Stores administrator credentials for system access.
ii) students: Stores student names, gender, and class information.
iii) subjects: Stores the names of the academic subjects.
iv) marks: Stores student scores linked via `student_id` and `subject_id`.
v) reports: Stores calculated averages and the generated student ranks.


Future Enhancements
- Add a feature to download performance reports as PDF files.
- Implement visual charts and graphs to track performance trends.
- Create different user roles (e.g., separate logins for Teachers and Parents).

License: Educational Purpose Only.