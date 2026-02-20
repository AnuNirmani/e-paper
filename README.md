# E-Paper Admin Dashboard

A modern admin dashboard for managing digital publications, customers, and subscriptions built with Laravel 10 and Tailwind CSS. This system automates the distribution of digital newspapers to customers via WhatsApp with advanced features like watermarking, subscription management, and automated notifications.

## ✨ Features

### Core Features (Main Branch)
- **Customer Management**: 
  - Individual subscription ending notification sending

- **Publication Management**: 
  - Publication-based filtering for customers
  - Publication-specific copy distribution

- **Copy Distribution**: 
  - Queue-based job processing for reliable delivery
  - PDF watermarking with customer name (customizable)
  - File size validation (<20MB)
  - Automatic cleanup of old copies (7+ days)

- **WhatsApp Integration**:
  - **QR Code Login**
  - **WhatsApp Logout**

- **Subscription Notifications**:
  - **Automated Daily Notifications** (scheduled at 9:00 AM):
    - 7 days before expiry
    - 3 days before expiry
    - On expiry day
  - Manual notification sending from customer list
  - Commands: `subscriptions:notify-7days`, `notify-3days`, `notify-today`

- **Automated Tasks**:
  - Daily cleanup of old copies (7+ days) at 2:00 AM
  - Daily subscription notifications at 9:00 AM

## 📦 Quick Start

### Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd e-paper
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup database**
   ```bash
   # Update .env with your database credentials
   php artisan migrate
   php artisan db:seed  # Optional: seed initial data
   ```

5. **Configure WhatsApp API**
   Update `.env` with your UltraMsg credentials:
   ```
   ULTRAMSG_INSTANCE_ID=your_instance_id
   ULTRAMSG_TOKEN=your_token
   ```

6. **Build assets & Run**
   ```bash
   npm run dev
   php artisan serve
   ```

7. **Start queue worker** (for background jobs)
   ```bash
   php artisan queue:work
   ```

8. **Setup scheduled tasks**
   Add to crontab or Windows Task Scheduler:
   ```bash
   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
   ```

## 🌿 Branch Information

### Main Branch
**Status**: Production-ready  
**Latest Features**:
- Complete WhatsApp QR code integration
- Automated subscription notifications (7 days, 3 days, today)
- PDF watermarking with customer names
- Queue-based job processing
- Automatic old copy cleanup
- Manual subscription notification sending

### Security Branch
**Status**: Security Enhancements  
**Additional Security Features**:
- Session management improvements
- Enhanced API rate limiting
- SQL injection prevention verification
- Improved error handling
- Rate limiting on API endpoints
- Enhanced authentication & authorization

**Purpose**: Security-hardened version with additional protection layers.

## 🔧 Manual Commands

You can run these commands manually:

```bash
# Subscription notifications
php artisan subscriptions:notify-7days
php artisan subscriptions:notify-3days
php artisan subscriptions:notify-today

# Cleanup old copies
php artisan copies:delete-old
```

## 📝 Configuration

### UltraMsg Setup

1. Sign up for UltraMsg account at [ultramsg.com](https://ultramsg.com)
2. Create an instance and get your Instance ID
3. Generate an API token
4. Add credentials to `.env` file
5. Use the WhatsApp QR feature in the application to connect your WhatsApp

## 🛠️ Technical Stack

- **Backend**: Laravel 10, PHP 8.2+
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: MySQL
- **Queue**: Laravel Queue (Database/Redis)
- **WhatsApp API**: UltraMsg
- **Watermark API**: ILoveAPI
- **PDF Processing**: Custom watermark service
- **Authentication**: Laravel Breeze
- **Task Scheduling**: Laravel Scheduler

## 📄 License

This project is licensed under the MIT License. See [LICENSE](LICENSE) for details.

## 👥 Contributing

This is a private project for **Wijeya Newspapers Limited**. For internal development inquiries, please contact the development team.

**Contact**: oanuttara@gmail.com

## 🔒 Security Vulnerabilities

If you find a security vulnerability, please report it privately to **oanuttara@gmail.com** instead of opening a public issue. 

**Response Time**: We aim to acknowledge reports within 2 business days.

## 📞 Support & Contact

For technical support or questions:
- **Email**: oanuttara@gmail.com
- **Organization**: Wijeya Newspapers Limited

**© 2026 Wijeya Newspapers Limited. All rights reserved.**
