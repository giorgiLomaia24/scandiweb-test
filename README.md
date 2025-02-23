# Full Stack eCommerce Project

## Overview
This project is a simple eCommerce website built as part of a **Junior Full Stack Developer test task**. It consists of a PHP-based backend and a React-based frontend, implementing product listing, cart functionality, and GraphQL-based data handling.

## Tech Stack
### Backend:
- **PHP 8+** (No frameworks, but third-party libraries allowed)
- **MySQL 5.6+**
- **GraphQL (webonyx/graphql-php)**
- **PDO (PHP Data Objects) for Database Interaction**
- **Composer for Dependency Management**

### Frontend:
- **React (Vite + Redux)**
- **Class-based Components**
- **GraphQL for Data Fetching**
- **CSS (SCSS or Tailwind allowed)**

---

## Installation & Setup
### Backend Setup:
1. Clone the repository:
   ```sh
   git clone <repo-url>
   cd backend
   ```
2. Install dependencies:
   ```sh
   composer install
   ```
3. Set up the `.env` file with your database credentials:
   ```env
   DB_HOST=your_db_host
   DB_NAME=your_db_name
   DB_USER=your_db_user
   DB_PASS=your_db_password
   DB_PORT=3306
   ```
4. Run the database migrations and seed data:
   ```sh
   php src/Database/scripts/seeder.php
   ```
5. Start the backend server:
   ```sh
   php -S localhost:8000 -t public
   ```

### Frontend Setup:
1. Navigate to the frontend directory:
   ```sh
   cd client
   ```
2. Install dependencies:
   ```sh
   npm install
   ```
3. Start the development server:
   ```sh
   npm run dev
   ```
4. Open the application in the browser at `http://localhost:5173`

---

## Backend Implementation
### Directory Structure:
```
backend/
│── public/
│   ├── index.php  # Entry point
│── src/
│   ├── Database/  # Handles DB connection (Singleton pattern)
│   ├── GraphQL/   # GraphQL Resolvers & Schemas
│   ├── Models/    # Product & Category Models (OOP Polymorphism)
│   ├── Repositories/    # Database Abstraction
│── vendor/        # Dependencies (Composer)
│── .env           # Configuration file
│── composer.json  # PHP Dependencies
```
### GraphQL API:
- **Queries:** Fetch categories, products, product details
- **Mutations:** Place an order
- **GraphQL Implementation:** Uses `webonyx/graphql-php`

### Database Handling:
- Uses **PDO** for secure interactions
- Implements **singleton pattern** in `Database.php`
- Models use **OOP principles (polymorphism & inheritance)**

---

## Frontend Implementation
### Directory Structure:
```
client/
│── src/
│   ├── components/   # UI Components
│   ├── pages/        # Pages (Product Listing, Cart, Product Details)
│   ├── redux/        # Redux Store & Reducers
│   ├── graphql/      # Queries & Mutations
│── public/
│── package.json     # Dependencies
```
### Features:
- **Product Listing Page** (Shows categories & products, filters)
- **Product Details Page** (Configurable product options)
- **Cart Overlay** (Session-based state, updates dynamically)
- **Order Placement** (GraphQL mutation for order creation)
- **Redux for Global State Management**

---

## Key Functionalities
- **Dynamic Product Rendering** (GraphQL data fetching)
- **Cart State Management** (Redux-based cart handling)
- **GraphQL API Integration** (Uses Apollo Client to fetch data)
- **Responsive UI** (Styled using CSS/SCSS)
- **Local Storage for Cart Persistence**

## Deployment
- Deploy backend using **PHP hosting (000webhost, etc.)**
- Deploy frontend using **Netlify/Vercel**
- Ensure **GraphQL API is publicly accessible** for the frontend

---

## Testing
- Use [Auto QA Tool](http://165.227.98.170/) to validate implementation
- Ensure all required `data-testid` attributes exist for testing
- Run the app and check for UI/functional correctness

---

## Conclusion
This project showcases a full-stack eCommerce application with a **GraphQL-powered PHP backend** and a **React-based frontend** using Redux. It follows OOP principles, PSR standards, and adheres to the test task requirements.

