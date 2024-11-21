
# Project Setup Instructions

This document contains instructions for setting up **MySQL**, **PHP** using **XAMPP**, and running the project with **Composer**.

## Prerequisites

1. **XAMPP** installed on your machine
2. **Composer** installed on your machine
3. **Git** installed (if needed for project cloning)
4. **Node and NPM** installed

## Step 1: Install XAMPP

1. Download and install [XAMPP](https://www.apachefriends.org/download.html) for your platform (Windows).
2. Once installed, open **XAMPP Control Panel**.
3. Start the **Apache** and **MySQL** services by clicking the **Start** button next to each.

    - **Apache**: This is the web server that will serve your PHP application.
    - **MySQL**: This will manage your project’s database.

## Step 2: Configure MySQL

### Verify MySQL is Running

1. Open **XAMPP Control Panel**.
2. Ensure **MySQL** is running (green indicator next to MySQL).
3. If MySQL is not starting, check the **MySQL error log** in the XAMPP Control Panel for any issues related to the service.

### Create a Database (if required)

1. Open **phpMyAdmin** in your browser: `http://localhost/phpmyadmin`.
2. Create a new database for your project (e.g., `your_project_db`).

    - If your project requires a specific database, make sure to create it and note the database name, username, and password (usually, it's `root` for username with no password in XAMPP by default).

## Step 3: Install PHP via XAMPP

1. **PHP** is included in **XAMPP**, so you don't need to install it separately.
2. Ensure that **Apache** and **PHP** are correctly configured. If needed, edit the **php.ini** file for custom settings:
    - Open **XAMPP Control Panel** and click **Config** next to **Apache** → **php.ini**.
    - Modify the required PHP settings (e.g., enable `extensions` like `mysqli`, `pdo_mysql`, etc.).

## Step 4: Install Composer

1. Download and install **Composer** from [Composer's official website](https://getcomposer.org/download/).
2. During installation, make sure to allow Composer to be globally available in your system PATH.
    - If installed correctly, you can verify by running:
      ```bash
      composer --version
      ```
      This should output the installed Composer version.

## Step 4.1: Install Node and NPM

1. Download and install **Node.js** from [Node.js official website](https://nodejs.org/en/download/).
2. During installation, make sure to allow Node.js to be globally available in your system PATH.
    - If installed correctly, you can verify by running:
      ```bash
      node --version
      ```
      This should output the installed Node.js version.
    - If installed correctly, you can verify by running:
      ```bash
      npm --version
      ```
      This should output the installed NPM version.

## Step 5: Clone or Download the Project

1. Clone the project from your Git repository or download it as a ZIP file:
   ```bash
   git clone https://github.com/lukakuder/spletni_nakupovalni_seznam.git
   ```

2. Navigate into the project directory:
   ```bash
   cd speltni-nakupovalni-seznam
   ```

## Step 6: Install Dependencies with Composer

1. Install project dependencies by running the following command in the project root directory (where `composer.json` is located):
   ```bash
   composer install
   ```
   This will install all the necessary dependencies defined in the `composer.json` file and create a `vendor/` directory.

2. If you need to update the dependencies later, run:
   ```bash
   composer update
   ```


## Step 6.1: Install Dependencies with NPM

1. Install project dependencies by running the following command in the project root directory (where `package.json` is located):
   ```bash
   npm install
   ```
   This will install all the necessary dependencies defined in the `package.json` file and create a `node_modules/` directory.

## Step 7: Configure `.env` File

For projects like **Laravel**, configure the `.env` file with your database credentials and other environment variables:

1. Copy the `.env.example` file to `.env`:
   ```bash
   cp .env.example .env
   ```

2. Edit the `.env` file to match your **MySQL** database settings:

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_project_db   # Use the database name you created earlier
   DB_USERNAME=root              # Default MySQL user in XAMPP
   DB_PASSWORD=                  # Default password in XAMPP is empty
   ```

---

## Step 8: Run the Project

### For **Laravel Projects**

1. If the project is a **Laravel** application, you can start the local development server using the built-in **Artisan** command:
   ```bash
   php artisan serve
   ```

2. This will start the application at `http://localhost:8000`.

### For Other PHP Projects

1. If you're not using **Laravel**, place your PHP project files in the `htdocs` directory (located in the XAMPP installation folder, e.g., `C:/xampp/htdocs`).

2. For example, if your project is in `C:/xampp/htdocs/your-app/`, open your browser and navigate to:
   ```plaintext
   http://localhost/your-app/
   ```

---

## Step 9: Troubleshooting

### MySQL Errors:
- If MySQL isn't starting, check the **MySQL error log** in XAMPP for any issues related to port conflicts or file permissions.
- If you're getting a **MySQL error** when connecting to the database, make sure the database credentials in the `.env` file are correct.

### Apache Errors:
- If Apache isn’t starting, make sure port 80 is not being used by another service (e.g., IIS, Skype). Change Apache's port in the **httpd.conf** file if necessary.
- If you're getting an **Apache error**, check the **Apache error log** in XAMPP for more details.

### Composer Issues:
- If Composer isn’t working, make sure it is installed globally and that the `composer.json` file is correct.

---

## Additional Notes

- Make sure you’re running **XAMPP Control Panel** as **Administrator** for any permission issues.
- If you have **firewall or antivirus** software, make sure they aren't blocking Apache or MySQL from running.

---

## Conclusion

This setup should get your **MySQL**, **PHP**, and **Composer** running correctly with your project. Let me know if you need further clarification or assistance!

## Additional

Contact us for configuration details and troubleshooting. 

### Happy Coding! 🚀
