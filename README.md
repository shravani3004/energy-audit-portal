# Energy Audit Portal

## Project Description:

Energy Audit Portal is a web-based application developed as an MCA project to help users analyze the energy consumption of commercial buildings. The system calculates daily and monthly electricity usage based on appliance details, estimates energy costs, stores audit records in a MySQL database, and generates an energy audit report. It also provides energy-saving recommendations and sends the report to the user's email.


## Objectives:

- Calculate daily and monthly energy consumption.
- Estimate monthly electricity cost.
- Suggest energy saving tips.
- Generate an energy audit report.
- Store audit details in a MySQL database.
- Send the generated report through email.


## Technology Stack:

**Frontend**
- HTML
- CSS
- JavaScript
- Bootstrap 5
- Chart.js

**Backend**
- PHP
- Laravel 12

**Database**
- MySQL

**Tools**
- Visual Studio Code
- MySQL Workbench
- Git & GitHub
- Composer


## Setup Instructions:

1. Clone the repository From
   
  git clone https://github.com/shravani3004/energy-audit-portal.git

2. Install Laravel dependencies using Composer.

3. Copy `.env.example` to `.env` and update the database and mail configuration.

4. Generate the application key.
```bash
php artisan key:generate
```

5. Run the database migrations.
```bash
php artisan migrate
```

6. Add sample data.
```bash
php artisan db:seed
```

7. Start the Laravel server.
```bash
php artisan serve
```

8. Open `index.html` using VS Code Live Server.


## Screenshots:

### Home Page
(Add Screenshot)

### Energy Audit Form
(Add Screenshot)

### Audit Report
(Add Screenshot)

### Email Report
(Add Screenshot)


## Live Project:

**Frontend:**  
(Add Frontend URL)

**Backend API:**  
(Add Backend URL)


## Author:

**Shravani Parag Naik**  
MCA Student  
MET Institute of Management, Bhujbal Knowledge City, Nashik.
