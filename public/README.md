
# 🚀 TadreebLMS

TadreebLMS is a modern, open-source **Learning Management System (LMS)** built to support educational institutions, training organizations, and professional development programs. It enables seamless delivery of digital learning through structured courses, assessments, progress tracking, and certification.

Our organization is dedicated to developing future leaders through innovative and impactful education. We aim to equip students, professionals, and leaders with essential knowledge, skills, and strategic thinking to excel in a dynamic global environment.

---

## 🌍 About TadreebLMS

TadreebLMS is designed to be **flexible, scalable, and customizable**, making it suitable for a wide range of learning use cases such as:

- Academic learning
- Corporate and professional training
- Skill development programs
- Online and blended learning environments

As an **open-source platform**, TadreebLMS allows organizations to fully control their learning infrastructure, adapt workflows, and extend functionality as needed.

---

## 📚 Key Features

### 👥 User & Role Management
- Student registration and enrollment
- Instructor onboarding and management
- Admin-level platform control
- Role-based access permissions

### 🎓 Course Management
- Create, organize, and manage courses
- Categorize courses by subject or skill area
- Assign courses to users or groups
- Track course completion percentage

### 📝 Assessments & Evaluation
- Quizzes and evaluations linked to courses
- Manual and automatic grading
- Learner performance tracking

### 📊 Progress & Reporting
- Individual learner dashboards
- Course progress and completion insights
- Instructor and admin-level reports

### 🏅 Certificates
- Automatic certificate generation upon course completion
- Downloadable and shareable certificates
- Customizable certificate templates
- Optional certificate verification support

### 📚 Learning Resources
- Resource libraries for supplementary materials
- Upload and manage documents and learning assets

### 🌐 Platform Capabilities
- Multi-language support (English, Arabic)
- Responsive and mobile-friendly design
- Secure authentication system
- Cloud or on-premise deployment

---

## 🛠️ Technology Stack

- **Backend:** PHP / Laravel
- **Frontend:** HTML, CSS, JavaScript
- **Database:** MySQL / PostgreSQL
- **Authentication:** Role-based access control
- **Deployment:** Cloud | On-Premise

---

## 👤 User Roles

| Role | Description |
|-----|------------|
| Admin | Full platform management |
| Instructor | Course creation and learner evaluation |
| Learner | Course participation and certification |

---

## 🤝 Contributing

Community contributions are welcome.

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Submit a pull request

---

## 📄 License

TadreebLMS is licensed under the **GNU Affero General Public License (AGPL)**.

This license ensures that:
- Any modifications must remain open source
- Network deployments must provide access to source code
- Community benefits from shared improvements

See the `LICENSE` file for full details.

---

## ⭐ Support TadreebLMS

If you find TadreebLMS useful, please give the project a ⭐ on GitHub and consider contributing.

---

# 📦 Installation Guide

> **Server Requirement:** Ubuntu 20.04 / 22.04  
> **Web Server:** Apache  
> **PHP Version:** 8.2+  
> **Node / Redis:** Not required

---

## 1️⃣ Server Update

```bash
sudo apt update && sudo apt upgrade -y
```

---

## 2️⃣ Install Apache

```bash
sudo apt install apache2 -y
sudo systemctl enable apache2
sudo systemctl start apache2
```

---

## 3️⃣ Install PHP & Required Extensions

```bash
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
```

```bash
sudo apt install php8.2 php8.2-cli php8.2-common php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-mysql php8.2-bcmath php8.2-gd -y
```

---

## 4️⃣ Install Composer

```bash
cd /tmp
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --version=2.7.8
sudo mv composer.phar /usr/local/bin/composer
```

```bash
composer --version
```

---

## 5️⃣ Clone TadreebLMS Repository

```bash
cd /var/www
sudo git clone https://github.com/Tadreeb-LMS/tadreeblms.git
```

```bash
cd tadreeblms
sudo composer install
```

---

## 6️⃣ Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

> ✅ **Database will be created from the UI during admin onboarding (similar to Perfex CRM)**

Update database credentials in `.env` if required.

---

## 7️⃣ Set Permissions

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo mkdir -p storage/framework/sessions
sudo chmod 775 storage/framework/sessions
```

---

## 8️⃣ Apache Virtual Host Configuration

```bash
sudo nano /etc/apache2/sites-available/tadreeblms.conf
```

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/tadreeblms/public

    <Directory /var/www/tadreeblms>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/tadreeblms_error.log
    CustomLog ${APACHE_LOG_DIR}/tadreeblms_access.log combined
</VirtualHost>
```

```bash
sudo a2ensite tadreeblms
sudo a2enmod rewrite
sudo systemctl restart apache2
```

---

## 9️⃣ Access Application

Open your browser:

```
http://your-domain.com
```

Complete **admin onboarding** via UI.

---

✅ TadreebLMS is now installed and ready to use!
