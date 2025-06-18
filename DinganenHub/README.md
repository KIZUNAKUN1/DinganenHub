# Blood Donation Management System

A frontend-only blood donation website built with HTML, CSS, and JavaScript with user authentication and data persistence.

## Features

1. **User Authentication**
   - Sign up with username and password
   - Login with existing credentials
   - Secure storage of user data in localStorage
   - Logout functionality
   - Session persistence using localStorage

2. **Donor Registration** (accessible after login)
   - Register donors with name, blood type, and contact information
   - Input validation ensures data integrity
   - Donors are associated with the logged-in user

3. **Donor List**
   - View all registered donors in a tabular format
   - Filter donors by blood type
   - Each user sees only their own donors

4. **Appointment Booking**
   - Book appointments with date and time
   - Add optional notes for each appointment
   - Appointments are linked to the logged-in user

5. **Appointment List**
   - View all booked appointments with date, time, and notes
   - Appointments are sorted by date and time
   - Each user sees only their own appointments

## Technical Details

- **Frontend Only**: All data is stored in localStorage for persistence
- **Responsive Design**: Works on desktop and mobile devices
- **No Dependencies**: Built with vanilla JavaScript, HTML, and CSS
- **User-specific Data**: Each user has their own isolated data

## How to Use

1. Open `index.html` in any modern web browser
2. Sign up for a new account or login with existing credentials
3. Register donors using the donor registration form
4. Filter donors by blood type using the dropdown
5. Book appointments using the appointment form
6. View all your appointments in the appointments list
7. Logout when finished (your data will be saved)

## Implementation Notes

- Data is stored in localStorage and persists between sessions
- The application uses client-side validation for all forms
- User passwords are stored in plain text (for demo purposes only)
- The design is responsive and works on various screen sizes
