# Hotelier — Hotel Management System

**Hotelier** is a professional Hotel Management System designed to streamline the experience for guests, staff, and administrators. This system provides an elegant landing page, digital booking processes, live room management, and comprehensive reporting.

---

## 1. System Setup

This section is for developers or evaluators setting up the project locally.

1. **Environment Requirements:**
   - A local server environment with PHP and MySQL (e.g., XAMPP, WAMP, or MAMP).
2. **Database Initialization:**
   - Create a database in MySQL for the application (e.g., `hotelier_db`).
   - Import the provided SQL scripts into your database in the following order (if applicable):
     1. `hotelier.sql` (Base Schema)
     2. `hotelier_rooms.sql`
     3. `hotelier_facilities.sql`
     4. `hotelier_seed.sql` (To populate initial demo data)
3. **Application Deployment:**
   - Place the project folder into your local server's document root (e.g., `htdocs` or `www`).
   - Start the Apache and MySQL services.
   - Open your browser and navigate to `http://localhost/Hotelier/`.

---

## 2. Operating the System

The application offers dedicated features depending on the access level.

### A. Guest Operations

Guests can browse the offerings and book stays. 
- **Landing Page Navigation:** Scroll through the main page to explore room types, features, general hotel layout, and our world-class facilities.
- **Making a Booking:**
  1. Click **Book a Room** on the hero banner or directly on a room card.
  2. If not logged in, you will be prompted to either **Register/Sign in** or select **Continue as Guest**.
  3. Fill out the booking form: select dates (check-out must be after check-in), number of guests, and room type.
  4. The system calculates your total price instantly.
  5. Click **Confirm Booking**.
- **Booking Facilities:** 
  - Enhance your stay by navigating to the amenities section and selecting **Book Facilities**.
  - Choose any extra facilities (like Luxury Spa or Airport Pickup) which will be added to your final bill.
- **Viewing the Invoice:**
  - Guests with an active session can click the **🧾 Billing** link in the top navigation bar to view their final aggregated invoice showing room nights, rate, and additional facilities.

### B. Staff (Receptionist) Operations

Staff members handle the daily operations of the hotel.
- **Logging In:** Click **Sign In** and select **Staff (Receptionist)** from the "Login As" dropdown (Any credentials work in demo mode).
- **Room Status Workspace (🗝 Room Status):**
  - **Live Room Grid:** A visual map of rooms colored by their status: Available (Green), Occupied (Red), Maintenance (Orange).
  - **Check-In / Check-Out:** Process arriving or departing guests. Fill in the guest name, room number, dates, room rate, and select whether you are performing a check-in or a check-out action. Notes can be added as well.
- **Shift Schedule (📅 Shift Schedule):**
  - Open this module to view the work roster for the current week.
- **Billing (🧾 Billing):**
  - Staff can access invoices for processing guest payments and printing folios.

### C. Administrator Operations

Administrators have access to everything staff members do, plus high-level analytics.
- **Logging In:** Click **Sign In** and select **Admin** from the "Login As" dropdown.
- **Full Staff Access:** Admins process check-ins, view room statuses, check shift schedules, and manage billing.
- **Reports & Analytics (📊 Reports):**
  - This executive dashboard presents vital statistics at a glance.
  - View bar charts visualizing **Monthly Room Occupancy Rates** and **Monthly Revenue**.
  - Check key metrics like Average Occupancy, Year-to-Date Revenue, average Guest Rating, and Total Bookings.
  - A detailed data table breaks down the statistics further across previous months.

---

## 3. General Usability Notes

- **Modals:** The application heavily utilizes modal overlays. You can close them by clicking the `X` button, clicking anywhere outside the modal box, or pressing the `Escape` key.
- **Session Handling:** After a successful login, the top navigation replaces the hero 'Sign In' and floating CTA buttons. Your role dictates what links appear in the navigation bar.
- **Logout:** Use the **Log Out** button on the top right to end your session. This resets the workspace to the default guest-facing landing page.

---

## 4. Deploying to Vercel

Hotelier is configured for zero-configuration deployment to [Vercel](https://vercel.com) using the community `vercel-php@0.9.0` serverless runtime.

### 1. Database Setup
1. Create a MySQL database (e.g. free cloud database on [TiDB Cloud](https://tidbcloud.com) or any remote MySQL provider).
2. Import the unified migration script:
   ```bash
   mysql -u <USER> -h <HOST> -P <PORT> -p --ssl-mode=VERIFY_IDENTITY < database_setup.sql
   ```
   *Note: This script seeds 100 rooms, 6 facilities, and default demo accounts (`guest@hotelier.com` / `guest123`, `staff@hotelier.com` / `staff123`, `admin@hotelier.com` / `admin123`).*

### 2. Vercel Configuration
1. Import the repository in Vercel.
2. Select branch `vercel-deploy`.
3. Set **Framework Preset** to **`Other`** (no build command, no output directory override).
4. In **Project Settings → Environment Variables**, add:
   - `DB_HOST`: Database hostname (e.g., `gateway01.ap-southeast-1.prod.aws.tidbcloud.com`)
   - `DB_PORT`: Database port (e.g., `4000` or `3306`)
   - `DB_NAME`: Database name
   - `DB_USER`: Database username
   - `DB_PASSWORD`: Database password
   - `DB_SSL`: `true` (enables SSL encryption for cloud MySQL)
5. Deploy!

### 3. Demo Mode Fallback
If deployed without environment variables or while the database is unreachable, the application automatically operates in **Demo Mode**:
- Quick-access buttons for Guest, Staff, and Admin roles.
- Interactive 100-room live grid, room status updates, bookings, invoices, and analytics dashboards all work client-side.

