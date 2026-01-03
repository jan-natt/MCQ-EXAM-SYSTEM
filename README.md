# MCQ Exam & Evaluation System

A complete, production-ready Laravel web application for creating and managing multiple-choice question (MCQ) exams with automatic evaluation and detailed result analysis.

## 📋 Table of Contents
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Database Schema](#database-schema)
- [Business Logic](#business-logic)
- [Testing Credentials](#testing-credentials)
- [Architecture & Design](#architecture--design)
- [Future Enhancements](#future-enhancements)

## ✨ Features

### Admin Features
- **Dashboard**: Overview of all exams, students, and submissions
- **Exam Management**: 
  - Create, edit, delete exams
  - Set exam duration and passing marks
  - Activate/deactivate exams
  - View exam statistics
- **Question Management**:
  - Add multiple-choice questions with dynamic options
  - Support for single-answer and multiple-answer questions
  - Set marks for each question
  - Edit and delete questions
- **Result Analytics**: View student performance and exam statistics

### Student Features
- **Dashboard**: View available exams and recent results
- **Exam Taking**:
  - Start and take exams
  - Real-time timer (if exam has time limit)
  - Visual question navigator
  - Warning before leaving exam page
- **Results**:
  - Instant automatic evaluation
  - Detailed answer review with correct/incorrect indicators
  - Performance statistics
  - Result history

### Authentication
- Email & Password authentication
- Google OAuth login (Laravel Socialite)
- Role-based access control (Admin/Student)

### Evaluation System
- **Automatic Calculation**: Results calculated instantly upon submission
- **Marking Logic**:
  - Single-choice: Full marks if correct answer selected
  - Multiple-choice: Full marks only if ALL correct options selected and NO incorrect options selected
  - No partial marks
  - No negative marking
- **Detailed Results**: Question-by-question breakdown showing correct answers

## 🛠 Technology Stack

- **Framework**: Laravel 10/11
- **PHP**: 8.1+
- **Database**: MySQL
- **Authentication**: Laravel Breeze + Socialite
- **Frontend**: Bootstrap 5 + Blade Templates
- **Architecture**: MVC Pattern

## 📦 Installation

### Prerequisites
```bash
PHP >= 8.1
Composer
MySQL/MariaDB
Node.js & NPM
```

### Step-by-Step Installation

1. **Clone or Create Project**
```bash
composer create-project laravel/laravel mcq-exam-system
cd mcq-exam-system
```

2. **Install Dependencies**
```bash
composer require laravel/socialite
composer require laravel/ui
php artisan ui bootstrap --auth
npm install && npm run build
```

3. **Environment Configuration**

```


# Run migrations
php artisan migrate
```

5. **Copy All Files**
- Copy all models to `app/Models/`
- Copy all controllers to `app/Http/Controllers/`
- Copy all middleware to `app/Http/Middleware/`
- Copy all requests to `app/Http/Requests/`
- Copy all services to `app/Services/`
- Copy all migrations to `database/migrations/`
- Copy all seeders to `database/seeders/`
- Copy all views to `resources/views/`
- Update `routes/web.php` with provided routes

6. **Register Middleware** (Laravel 10)

In `app/Http/Kernel.php`:
```php
protected $middlewareAliases = [
    // ... existing middleware
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'student' => \App\Http\Middleware\StudentMiddleware::class,
];
```

For Laravel 11, register in `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'student' => \App\Http\Middleware\StudentMiddleware::class,
    ]);
})
```

7. **Configure Google OAuth in `config/services.php`**
```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

8. **Seed Database**
```bash
php artisan db:seed
```

9. **Start Server**
```bash
php artisan serve
```

Visit: http://localhost:8000

## ⚙️ Configuration

### Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project
3. Enable "Google+ API"
4. Create OAuth 2.0 credentials:
   - Application type: Web application
   - Authorized redirect URIs: `http://localhost:8000/auth/google/callback`
5. Copy Client ID and Secret to `.env`

For production, update redirect URI to your domain.

## 📖 Usage

### Admin Workflow

1. **Login** as admin:
   - Email: admin@example.com
   - Password: password

2. **Create Exam**:
   - Navigate to "Create New Exam"
   - Fill in title, description, duration, passing marks
   - Click "Create Exam"

3. **Add Questions**:
   - Open the created exam
   - Click "Add Question"
   - Enter question text and marks
   - Select question type (single/multiple choice)
   - Add options (minimum 2)
   - Mark correct answer(s)
   - Click "Add Question"

4. **Manage Exams**:
   - View all exams from "Exams" menu
   - Edit exam details
   - Toggle exam active/inactive status
   - Delete exams if needed
   - View exam statistics

### Student Workflow

1. **Login** as student:
   - Email: student@example.com
   - Password: password
   - Or use Google OAuth

2. **Take Exam**:
   - View available exams on dashboard
   - Click "Start Exam" on any active exam
   - Read instructions and click "Start Exam"
   - Answer all questions
   - Submit exam when done

3. **View Results**:
   - Results appear immediately after submission
   - Review correct and incorrect answers
   - View detailed explanation for each question
   - Check performance statistics

## 🗄 Database Schema

### Key Tables

**users**
- Stores admin and student accounts
- Google OAuth integration support

**exams**
- Exam details (title, description, duration, passing marks)
- Created by admin

**questions**
- Belongs to exam
- Contains question text, marks, type (single/multiple)

**options**
- Multiple options per question
- Marks correct answer(s)

**exam_attempts**
- Tracks student exam attempts
- Stores scores and completion status

**student_answers**
- Records student's selected options
- Marks correctness and marks obtained

## 🎯 Business Logic

### Marking System

**Core Principle**: All-or-nothing marking (no partial marks)

#### Single-Choice Questions
```
IF student selects the ONE correct option:
    Award full marks
ELSE:
    Award 0 marks
```

#### Multiple-Choice Questions
```
IF student selects ALL correct options AND NO incorrect options:
    Award full marks
ELSE:
    Award 0 marks
```

**Example**:
```
Question: Select programming languages (3 marks)
Options: 
  ☑ Python (correct)
  ☑ Java (correct)
  ☐ HTML (incorrect)
  ☐ CSS (incorrect)

Student Answer 1: Python, Java → 3 marks ✓
Student Answer 2: Python, Java, HTML → 0 marks ✗
Student Answer 3: Python only → 0 marks ✗
```

### Result Calculation Process

1. **On Submission**: 
   - Loop through all questions
   - For each question, compare selected options with correct options
   - Award marks only if answer is completely correct
   - Save individual answer records

2. **Calculate Total**:
   - Sum all marks obtained
   - Calculate percentage
   - Determine pass/fail status

3. **Store Results**:
   - Update exam attempt with final score
   - Mark attempt as completed
   - Prevent further modifications

### Security Features

- Role-based middleware protection
- CSRF protection on all forms
- SQL injection prevention via Eloquent ORM
- XSS protection via Blade escaping
- Authorization checks on all sensitive operations

## 🔐 Testing Credentials

After running seeders:

**Admin Account**
```
Email: admin@example.com
Password: password
```

**Student Accounts**
```
Email: student@example.com
Password: password

Email: jane@example.com
Password: password

Email: bob@example.com
Password: password
```

## 🏗 Architecture & Design

### Design Patterns

1. **MVC Pattern**
   - Models: Data and relationships
   - Controllers: Request handling
   - Views: Presentation layer

2. **Service Layer Pattern**
   - `ExamService`: Exam business logic
   - `QuestionService`: Question management
   - `ResultCalculationService`: Evaluation logic

3. **Repository Pattern** (Implicit via Eloquent)
   - Models handle data access
   - Eloquent provides query abstraction

4. **Form Request Validation**
   - Dedicated validation classes
   - Business rule enforcement
   - Clean controller code

### Code Organization

```
app/
├── Http/
│   ├── Controllers/    # Request handlers
│   ├── Middleware/     # Auth & authorization
│   └── Requests/       # Validation logic
├── Models/             # Eloquent models
└── Services/           # Business logic

resources/
└── views/              # Blade templates
    ├── layouts/        # Base layout
    ├── admin/          # Admin views
    └── student/        # Student views

database/
├── migrations/         # Database structure
└── seeders/           # Sample data
```

### Key Design Decisions

1. **Separate Service Classes**: Business logic separated from controllers for testability and reusability

2. **Form Requests**: Validation logic isolated in dedicated classes

3. **Soft Relations**: Cascade deletes handled at database level

4. **All-or-Nothing Marking**: Ensures fairness and clarity

5. **Immediate Results**: No delay in result calculation

## 🚀 Future Enhancements

### High Priority
- [ ] Export results to PDF/Excel
- [ ] Exam categories/subjects
- [ ] Question bank for reusability
- [ ] Random question selection
- [ ] Shuffle options

### Medium Priority
- [ ] Question images support
- [ ] Detailed analytics dashboard
- [ ] Email notifications
- [ ] Exam scheduling (start/end dates)
- [ ] Student groups/classes

### Low Priority
- [ ] API for mobile app
- [ ] Multi-language support
- [ ] Dark mode
- [ ] Advanced question types (drag-drop, fill-in-blanks)
- [ ] Proctoring features

## 📝 Notes & Assumptions

### Assumptions
1. Exams can be retaken (configurable in `ExamService`)
2. Students can see correct answers after submission
3. No time extension once timer starts
4. Admin creates all content (no student contributions)

### Limitations
1. No question randomization
2. No option shuffling
3. No image upload for questions
4. Timer is client-side (can be manipulated)

### Production Considerations
1. Implement server-side timer validation
2. Add rate limiting on exam submission
3. Enable Redis caching for better performance
4. Set up queue workers for heavy operations
5. Implement proper backup strategy
6. Add comprehensive logging
7. Set up monitoring and alerts

## 🤝 Contributing

This is an educational project. Feel free to fork and enhance!

## 📄 License

Open source - MIT License

## 👨‍💻 Author

Built as a complete Laravel interview/portfolio project demonstrating:
- Clean code architecture
- Best practices
- Production-ready features
- Comprehensive documentation

---

**Note**: This is a fully functional application ready for deployment with minor production adjustments (security hardening, caching, queue setup).
