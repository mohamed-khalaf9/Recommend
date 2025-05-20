# Recommend

Recommend is a collaborative platform that enables the creation of specialized communities (circles) where users can share, organize, and vote on recommendations.

## Project Overview

Recommend is a web application designed to connect knowledge through curated recommendations in specialized communities. The platform allows users to:

- Create and join topic-focused circles
- Share recommendations with links and descriptions
- Vote on recommendations through likes
- Manage circle membership through a request-approval system

## System Architecture

Recommend follows a client-server architecture with a clear separation between frontend and backend components:

### Frontend

The frontend consists of HTML, CSS, and JavaScript files that provide the user interface:

- **Landing Page**: Introduction for new users with signup/login options
- **Website Home Page**: Dashboard showing user's circles and pending requests
- **Circle Home Page**: Interface for viewing and sharing recommendations
- **Admin Dashboard**: Administrative interface for managing circle members and requests

### Backend

The backend is built with PHP and follows a controller-based architecture:

- **Router**: Routes API requests to appropriate controllers
- **Controllers**: Handle business logic for users, circles, recommendations, etc.
- **PDO Classes**: Provide database access layer
- **JWT Authentication**: Secures API endpoints

### Database

The application uses a MySQL database with six primary tables:

- `users`: Store user accounts
- `circles`: Store community information
- `members`: Map users to circles with roles
- `recommendations`: Store shared content
- `likes`: Track user votes
- `requests`: Manage join requests

## Key Features

### User Authentication System

- User registration with profile details
- Secure login with JWT token authentication
- Session management

### Circle Management

- Create new circles with names and descriptions
- Join circles through request-approval process
- View circle recommendations
- Admin dashboard for circle management

### Recommendation System

- Create recommendations with titles, descriptions, and links
- View recommendations within circles
- Like recommendations to indicate quality

### Request and Approval System

- Send requests to join circles
- Approve or reject join requests (for admins)
- Manage circle membership

## Installation and Setup

1. Clone the repository
2. Import the database schema from `recommend.sql`
3. Configure your web server to point to the project directory
4. Update database connection settings in `backend/db.php`

## Database Configuration

The application requires a MySQL database named `recommend`. You can import the schema using the provided SQL file:

```bash
mysql -u username -p recommend < recommend.sql
```

## Usage

1. Access the landing page at `index.html`
2. Register a new account or login
3. Create or join circles
4. Share and interact with recommendations

## Project Structure

```
recommend/
├── backend/
│   ├── controllers/      # Business logic
│   ├── pdos/             # Database access objects
│   ├── db.php            # Database connection
│   └── router.php        # Request routing
├── frontend/
│   ├── adminDashboard.html    # Admin interface
│   ├── circleHomePage.html    # Circle interface
│   ├── index.html             # Landing page
│   ├── webSiteHomePage.html   # User dashboard
│   └── *.css                  # Stylesheets
└── recommend.sql         # Database schema
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request


```
