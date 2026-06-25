<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Top5Way · Admin</title>
  <!-- Font Awesome (optional, for icons) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      background: #f1f5f9;
      display: flex;
      min-height: 100vh;
    }

    /* ---------- SIDEBAR (toggle left) ---------- */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 260px;
      background: #0f172a; /* deep slate */
      color: #e2e8f0;
      padding: 1.8rem 1rem 2rem;
      display: flex;
      flex-direction: column;
      transition: transform 0.25s ease, box-shadow 0.2s;
      z-index: 40;
      overflow-y: auto;
      box-shadow: 2px 0 12px rgba(0,0,0,0.08);
      transform: translateX(0);
    }

    .sidebar.closed {
      transform: translateX(-100%);
    }

    .sidebar-header {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 2.2rem;
      padding-left: 0.25rem;
    }

    .sidebar-header i {
      font-size: 1.8rem;
      color: #38bdf8;
    }

    .sidebar-header h2 {
      font-size: 1.4rem;
      font-weight: 600;
      letter-spacing: -0.3px;
      color: white;
      background: linear-gradient(135deg, #38bdf8, #818cf8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .sidebar nav {
      display: flex;
      flex-direction: column;
      gap: 0.2rem;
      flex: 1;
    }

    .sidebar a, .sidebar .nav-label {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.6rem 0.9rem;
      border-radius: 0.5rem;
      color: #cbd5e1;
      text-decoration: none;
      font-size: 0.95rem;
      font-weight: 450;
      transition: background 0.15s, color 0.15s;
    }

    .sidebar a i {
      width: 1.4rem;
      font-size: 1.1rem;
      text-align: center;
      color: #64748b;
      transition: color 0.15s;
    }

    .sidebar a:hover {
      background: #1e293b;
      color: #f1f5f9;
    }

    .sidebar a:hover i {
      color: #94a3b8;
    }

    .sidebar a.active {
      background: #1e293b;
      color: white;
      font-weight: 500;
    }

    .sidebar a.active i {
      color: #38bdf8;
    }

    /* submenu toggle */
    .sidebar-group .sidebar-toggle {
      cursor: pointer;
      justify-content: space-between;
      background: transparent;
      border: none;
      width: 100%;
      text-align: left;
      font-size: 0.95rem;
      padding: 0.6rem 0.9rem;
      border-radius: 0.5rem;
      color: #cbd5e1;
      font-weight: 450;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .sidebar-group .sidebar-toggle:hover {
      background: #1e293b;
      color: #f1f5f9;
    }

    .sidebar-group .sidebar-toggle i:first-child {
      width: 1.4rem;
      font-size: 1.1rem;
      color: #64748b;
    }

    .sidebar-group .sidebar-toggle .arrow {
      margin-left: auto;
      font-size: 0.7rem;
      transition: transform 0.2s;
    }

    .sidebar-group .sidebar-toggle.open .arrow {
      transform: rotate(180deg);
    }

    .sidebar-sub {
      padding-left: 1.8rem;
      display: flex;
      flex-direction: column;
      gap: 0.1rem;
    }

    .sidebar-sub.hidden {
      display: none;
    }

    .sidebar-sub a {
      padding: 0.4rem 0.9rem;
      font-size: 0.85rem;
      color: #94a3b8;
      gap: 0.5rem;
    }

    .sidebar-sub a i {
      font-size: 0.8rem;
      width: 1.2rem;
      color: #475569;
    }

    .sidebar-sub a.active {
      background: #1e293b;
      color: #e2e8f0;
    }

    .sidebar-sub a.active i {
      color: #38bdf8;
    }

    /* logout button */
    .logout-btn {
      margin-top: 1.2rem;
      border-top: 1px solid #1e293b;
      padding-top: 1rem;
    }

    .logout-btn button {
      background: transparent;
      border: 1px solid #334155;
      color: #cbd5e1;
      padding: 0.6rem 0.9rem;
      width: 100%;
      border-radius: 0.5rem;
      font-size: 0.95rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      transition: 0.15s;
    }

    .logout-btn button i {
      font-size: 1.1rem;
      color: #64748b;
    }

    .logout-btn button:hover {
      background: #1e293b;
      color: white;
      border-color: #475569;
    }

    /* ---------- HAMBURGER (toggle) ---------- */
    .hamburger {
      display: none;
      position: fixed;
      top: 1.2rem;
      left: 1.2rem;
      z-index: 50;
      background: #0f172a;
      border: none;
      color: white;
      font-size: 1.6rem;
      padding: 0.4rem 0.8rem;
      border-radius: 0.5rem;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      transition: 0.2s;
    }

    .hamburger:hover {
      background: #1e293b;
    }

    /* overlay */
    .overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.4);
      z-index: 35;
      backdrop-filter: blur(2px);
    }

    .overlay.show {
      display: block;
    }

    /* ---------- MAIN CONTENT ---------- */
    .main {
      margin-left: 260px;
      flex: 1;
      padding: 2rem 2.5rem;
      background: #f1f5f9;
      min-height: 100vh;
      transition: margin-left 0.25s ease;
    }

    .main-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .greeting h1 {
      font-size: 1.8rem;
      font-weight: 600;
      color: #0f172a;
      letter-spacing: -0.3px;
    }

    .greeting p {
      color: #475569;
      margin-top: 0.1rem;
      font-size: 0.95rem;
    }

    .header-actions {
      display: flex;
      gap: 0.8rem;
      align-items: center;
    }

    .header-actions .badge {
      background: #e2e8f0;
      padding: 0.4rem 1rem;
      border-radius: 2rem;
      font-size: 0.8rem;
      font-weight: 500;
      color: #1e293b;
    }

    .header-actions .btn-outline {
      background: white;
      border: 1px solid #cbd5e1;
      padding: 0.4rem 1.2rem;
      border-radius: 2rem;
      font-weight: 500;
      color: #1e293b;
      text-decoration: none;
      font-size: 0.9rem;
      transition: 0.15s;
    }

    .header-actions .btn-outline i {
      margin-right: 0.4rem;
    }

    .header-actions .btn-outline:hover {
      background: #f8fafc;
      border-color: #94a3b8;
    }

    /* dashboard cards */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2.5rem;
    }

    .stat-card {
      background: white;
      padding: 1.5rem 1.2rem;
      border-radius: 1rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
      border: 1px solid #e9edf2;
    }

    .stat-card .label {
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      color: #64748b;
      font-weight: 600;
    }

    .stat-card .value {
      font-size: 2.2rem;
      font-weight: 700;
      color: #0f172a;
      margin-top: 0.3rem;
    }

    .stat-card .sub {
      font-size: 0.85rem;
      color: #475569;
      margin-top: 0.3rem;
    }

    .stat-card .trend {
      color: #16a34a;
      font-weight: 500;
    }

    .action-row {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      margin-top: 1.5rem;
    }

    .action-row .btn {
      background: white;
      border: 1px solid #d1d5db;
      padding: 0.6rem 1.4rem;
      border-radius: 0.75rem;
      font-weight: 500;
      color: #1e293b;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: 0.15s;
    }

    .action-row .btn-primary {
      background: #0f172a;
      border: 1px solid #0f172a;
      color: white;
    }

    .action-row .btn-primary i {
      color: #94a3b8;
    }

    .action-row .btn-primary:hover {
      background: #1e293b;
    }

    .action-row .btn-outline:hover {
      background: #f8fafc;
    }

    /* ---------- MOBILE RESPONSIVE ---------- */
    @media (max-width: 767px) {
      .sidebar {
        transform: translateX(-100%);
        width: 280px;
        padding-top: 1.2rem;
      }

      .sidebar.open {
        transform: translateX(0);
      }

      .hamburger {
        display: block;
      }

      .main {
        margin-left: 0;
        padding: 1.5rem 1.2rem;
        padding-top: 4.5rem;
      }

      .stats-grid {
        grid-template-columns: 1fr 1fr;
        gap: 0.8rem;
      }

      .greeting h1 {
        font-size: 1.4rem;
      }
    }

    @media (max-width: 480px) {
      .stats-grid {
        grid-template-columns: 1fr;
      }
    }

    /* flash / demo */
    .flash {
      background: #d1fae5;
      color: #065f46;
      padding: 0.75rem 1rem;
      border-radius: 0.75rem;
      margin-bottom: 1.5rem;
      font-weight: 500;
    }
  </style>
</head>
<body>

  <!-- HAMBURGER (toggle) -->
  <button class="hamburger" id="hamburgerBtn" aria-label="Toggle sidebar">
    <i class="fas fa-bars"></i>
  </button>

  <!-- OVERLAY -->
  <div class="overlay" id="overlay"></div>

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <i class="fas fa-chart-pie"></i>
      <h2>Top5Way</h2>
    </div>

    {{-- <nav>
      <!-- Dashboard -->
      <a href="#" class="active"><i class="fas fa-th-large"></i> Dashboard</a>

      <!-- Transactions group -->
      <div class="sidebar-group">
        <button class="sidebar-toggle open" onclick="toggleSubmenu(this)">
          <i class="fas fa-exchange-alt"></i> <span>Transactions</span>
          <span class="arrow">▾</span>
        </button>
        <div class="sidebar-sub">
          <a href="#"><i class="fas fa-list-ul"></i> All Transactions</a>
          <a href="#"><i class="fas fa-plus-circle"></i> Create Transaction</a>
          <a href="#"><i class="fas fa-tags"></i> Types</a>
          <a href="#"><i class="fas fa-folder"></i> Categories</a>
          <a href="#"><i class="fas fa-coins"></i> Currency Rates</a>
          <a href="#"><i class="fas fa-hand-holding-usd"></i> Dues</a>
          <a href="#"><i class="fas fa-calculator"></i> Fixed Costs</a>
          <a href="#"><i class="fas fa-user-clock"></i> Salary Tracker</a>
          <a href="#"><i class="fas fa-hand-holding-heart"></i> Loans</a>
        </div>
      </div>

      <!-- Businesses -->
      <div class="sidebar-group">
        <button class="sidebar-toggle" onclick="toggleSubmenu(this)">
          <i class="fas fa-store"></i> <span>Businesses</span>
          <span class="arrow">▾</span>
        </button>
        <div class="sidebar-sub hidden">
          <a href="#"><i class="fas fa-building"></i> All Businesses</a>
          <a href="#"><i class="fas fa-plus"></i> Create Business</a>
        </div>
      </div>

      <!-- Accounts -->
      <a href="#"><i class="fas fa-wallet"></i> Accounts</a>

      <!-- Contacts -->
      <div class="sidebar-group">
        <button class="sidebar-toggle" onclick="toggleSubmenu(this)">
          <i class="fas fa-address-book"></i> <span>Contacts</span>
          <span class="arrow">▾</span>
        </button>
        <div class="sidebar-sub hidden">
          <a href="#"><i class="fas fa-users"></i> All Contacts</a>
          <a href="#"><i class="fas fa-user-plus"></i> Create Contact</a>
        </div>
      </div>

      <a href="#"><i class="fas fa-history"></i> History</a>
      <a href="#"><i class="fas fa-chart-bar"></i> Report</a>
      <a href="#"><i class="fas fa-user-circle"></i> Profile</a>

      <!-- logout -->
      <div class="logout-btn">
        <button onclick="alert('Logout clicked')">
          <i class="fas fa-sign-out-alt"></i> Logout
        </button>
      </div>
    </nav> --}}
  </aside>

  <!-- ===== MAIN ===== -->
  <main class="main">
    <!-- flash demo -->
    <div class="flash">
      <i class="fas fa-check-circle" style="margin-right: 0.4rem;"></i> You're logged in, Mohammad Hamed Hasan!
    </div>

    <!-- header -->
    <div class="main-header">
      <div class="greeting">
        <h1>Dashboard</h1>
        <p>Welcome back, Mohammad Hamed Hasan</p>
      </div>
      <div class="header-actions">
        <span class="badge"><i class="far fa-calendar-alt"></i> Today</span>
        <a href="#" class="btn-outline"><i class="fas fa-pen"></i> Edit Profile</a>
      </div>
    </div>

    <!-- stats -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="label">Total Balance</div>
        <div class="value">5,245.00 ₺</div>
        <div class="sub"><span class="trend">↑ 2.1%</span> vs last month</div>
      </div>
      <div class="stat-card">
        <div class="label">Income</div>
        <div class="value">3,210.00 ₺</div>
        <div class="sub">+12 transactions</div>
      </div>
      <div class="stat-card">
        <div class="label">Expenses</div>
        <div class="value">1,890.00 ₺</div>
        <div class="sub">-8 transactions</div>
      </div>
      <div class="stat-card">
        <div class="label">Pending Dues</div>
        <div class="value">450.00 ₺</div>
        <div class="sub">3 items</div>
      </div>
    </div>

    <!-- quick actions -->
    <div class="action-row">
      <a href="#" class="btn btn-primary"><i class="fas fa-plus-circle"></i> New Transaction</a>
      <a href="#" class="btn"><i class="fas fa-upload"></i> Transfer</a>
      <a href="#" class="btn"><i class="fas fa-file-invoice"></i> Report</a>
      <a href="#" class="btn"><i class="fas fa-cog"></i> Settings</a>
    </div>

    <!-- extra placeholder (copy from original dashboard) -->
    <div style="margin-top: 2.5rem; background: white; border-radius: 1rem; padding: 1.8rem; border: 1px solid #e9edf2;">
      <h3 style="font-weight: 500; color: #0f172a; margin-bottom: 0.75rem;"><i class="fas fa-clock" style="color: #64748b; margin-right: 0.5rem;"></i> Recent Activity</h3>
      <p style="color: #475569; font-size: 0.95rem;">No recent transactions. Start by creating a new transaction.</p>
    </div>
  </main>

  <!-- ===== SCRIPTS ===== -->
  <script>
    (function() {
      // toggle sidebar on mobile
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('overlay');
      const hamburger = document.getElementById('hamburgerBtn');

      function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
      }

      function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('show');
      }

      hamburger.addEventListener('click', function(e) {
        e.stopPropagation();
        if (sidebar.classList.contains('open')) {
          closeSidebar();
        } else {
          openSidebar();
        }
      });

      overlay.addEventListener('click', closeSidebar);

      // close sidebar when clicking a link inside (on mobile)
      sidebar.querySelectorAll('a, button').forEach(el => {
        el.addEventListener('click', function() {
          if (window.innerWidth <= 767) {
            // but don't close if it's a toggle button (submenu)
            if (!this.classList.contains('sidebar-toggle')) {
              closeSidebar();
            }
          }
        });
      });

      // handle window resize: if desktop, remove open class
      window.addEventListener('resize', function() {
        if (window.innerWidth > 767) {
          closeSidebar();
        }
      });

    })();

    // toggle submenu (used inline)
    function toggleSubmenu(btn) {
      btn.classList.toggle('open');
      const sub = btn.nextElementSibling;
      if (sub) {
        sub.classList.toggle('hidden');
      }
    }

    // (optional) keep submenus open if active – we set Transactions open by default
    document.addEventListener('DOMContentLoaded', function() {
      // ensure the first submenu is open (Transactions)
      const firstToggle = document.querySelector('.sidebar-group .sidebar-toggle');
      if (firstToggle) {
        // already has 'open' class from HTML, but ensure sub is visible
        const sub = firstToggle.nextElementSibling;
        if (sub) sub.classList.remove('hidden');
      }
    });
  </script>
</body>
</html>