# CBT Application (Computer Based Test)

## Overview
CBT Application is a modern, feature-rich Computer Based Test system built with **Laravel**, **Filament**, and **Livewire**. It is designed to help educational institutions manage assessments, question banks, and exam sessions efficiently. The application supports various advanced question types, multimedia content, and comprehensive role-based management.

## Features

### 👤 User Management
- **Role-Based Access Control**:
    - **Admin**: Full system control.
    - **Teacher**: Manage question banks, subjects, and exams.
    - **Student**: Access and take exams.
- **Profile Management**: User profiles with avatar support.
- **Activity Logging**: Trails of important user actions for audit.

### 📚 Academic & Question Management
- **Academic Structure**: Management of Academic Years, Grades (Classes), and Subjects.
- **Question Bank**: Centralized repository for questions, organized by subject and teacher.
- **Rich Question Types**:
    - Multiple Choice & Multiple Selection
    - True/False
    - Matching (Jodohkan)
    - Ordering (Urutan)
    - Numerical Input
    - Essay
- **Multimedia Support**: Integration of images, audio, and video in questions and options.
- **Peer Review**: Workflow for checking and approving questions before they are used in exams.

### 📝 Exam System
- **Flexible Scheduling**: Set exam dates, duration, and access windows.
- **Exam Types**: Support for Daily Tests (UH), Mid-terms (UTS), Finals (UAS), etc.
- **Randomization**: Randomize question order to prevent cheating.
- **Auto-Grading**: Instant scoring for objective questions.
- **Session Tracking**: Monitor exam progress, duration, and status in real-time.

## Tech Stack

This project utilizes a modern Laravel stack for performance and developer experience:
- **Backend Framework**: Laravel
- **Admin Panel**: Filament PHP
- **Frontend / Interactivity**: Livewire, Alpine.js
- **UI Components**: Mary UI, Flux
- **Database**: MySQL / MariaDB

**Key Libraries**:
- `spatie/laravel-permission`: Role management.
- `spatie/laravel-medialibrary`: Handling file uploads (images/media).
- `spatie/laravel-activitylog`: Activity tracking.
- `robsontenorio/mary`: Beautiful UI components for Livewire.

## Installation

Follow these steps to set up the project locally:

1.  **Clone the Repository**
    ```bash
    git clone <repository-url>
    cd cbt-app
    ```

2.  **Install PHP Dependencies**
    ```bash
    composer install
    ```

3.  **Install Node.js Dependencies**
    ```bash
    npm install
    ```

4.  **Environment Configuration**
    Copy the example environment file and update your database credentials:
    ```bash
    cp .env.example .env
    # Edit .env to match your database config
    ```
    Generate the application key:
    ```bash
    php artisan key:generate
    ```

5.  **Database Setup**
    Run migrations and seeders to create tables and default data (Types, Roles, etc.):
    ```bash
    php artisan migrate --seed
    ```
    > **Note**: The seeder will create default roles: `admin`, `teacher`, `student`, and `parent`.

6.  **Build Assets**
    ```bash
    npm run build
    ```

7.  **Run the Application**
    Start the local development server:
    ```bash
    php artisan serve
    ```

## Usage

-   **Admin/Teacher Portal**: Access the main dashboard (typically `/admin` or the root URL depending on route configuration) to manage data.
-   **Student Portal**: Students log in to check for active exams and participate in sessions.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
