<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <style>
        /* ----- RESET & BASE ----- */
        * { box-sizing: border-box; }
        body {
            font-family: system-ui, sans-serif;
            background: #f9f9f9;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }

        /* ----- SIDEBAR (desktop/tablet) ----- */
        .sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
            padding: 1rem 0.75rem;
            flex-shrink: 0;
            transition: transform 0.25s ease, margin 0.25s ease;
            z-index: 100;
            display: flex;
            flex-direction: column;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        /* sidebar toggle button (desktop) */
        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            margin-bottom: 1rem;
            align-self: flex-start;
            color: #1f2937;
            border-radius: 0.375rem;
        }
        .sidebar-toggle:hover {
            background: #f3f4f6;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            flex: 1;
        }
        .sidebar-nav a,
        .sidebar-nav .dropdown-toggle {
            text-decoration: none;
            color: #374151;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-weight: 500;
            display: block;
            transition: background 0.15s;
            cursor: pointer;
        }
        .sidebar-nav a:hover,
        .sidebar-nav .dropdown-toggle:hover {
            background: #f3f4f6;
            color: #4f46e5;
        }
        .sidebar-nav .active {
            background: #e0e7ff;
            color: #3730a3;
        }

        /* dropdown inside sidebar */
        .sidebar-nav .dropdown {
            position: relative;
        }
        .sidebar-nav .dropdown-menu {
            display: none;
            padding-left: 1.25rem;
            flex-direction: column;
            gap: 0.15rem;
        }
        .sidebar-nav .dropdown.open .dropdown-menu {
            display: flex;
        }
        .sidebar-nav .dropdown-toggle {
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .sidebar-nav .dropdown-toggle::after {
            content: "▾";
            font-size: 0.75rem;
            opacity: 0.6;
            margin-left: 0.5rem;
        }
        .sidebar-nav .dropdown.open .dropdown-toggle::after {
            content: "▴";
        }
        .sidebar-nav .dropdown-menu a {
            padding: 0.4rem 0.75rem;
            font-size: 0.95rem;
        }

        /* logout button in sidebar */
        .sidebar-logout {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        .sidebar-logout form {
            display: inline;
            width: 100%;
        }
        .sidebar-logout button {
            background: none;
            border: none;
            color: #374151;
            font-weight: 500;
            cursor: pointer;
            padding: 0.5rem 0.75rem;
            width: 100%;
            text-align: left;
            border-radius: 0.375rem;
            font-size: 1rem;
            transition: background 0.15s;
        }
        .sidebar-logout button:hover {
            background: #f3f4f6;
            color: #4f46e5;
        }

        /* main content */
        .main-content {
            flex: 1;
            padding: 1.5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* ----- SIDEBAR COLLAPSED (desktop/tablet) ----- */
        .sidebar.collapsed {
            transform: translateX(-100%);
            margin-right: -260px;
        }

        /* When sidebar is collapsed, show a floating toggle button */
        .sidebar-floating-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 110;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.4rem 0.7rem;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }
        .sidebar.collapsed ~ .sidebar-floating-toggle {
            display: block;
        }

        /* ----- MOBILE (under 768px): keep original top navbar, no sidebar ----- */
        @media (max-width: 767px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                display: none !important;
            }
            .sidebar-floating-toggle {
                display: none !important;
            }
            .main-content {
                padding: 1rem;
            }

            /* original header styles (mobile) */
            header {
                background: #fff;
                padding: 1rem;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
            }
            header .header-row {
                display: flex;
                justify-content: space-between;
                width: 100%;
                align-items: center;
            }
            header .brand {
                font-weight: bold;
                font-size: 1.1rem;
            }
            .mobile-menu-btn {
                background: none;
                border: none;
                font-size: 1.5rem;
                cursor: pointer;
                padding: 0.2rem 0.5rem;
            }
            .nav-menu {
                display: none;
                flex-direction: column;
                width: 100%;
                gap: 0.25rem;
                margin-top: 0.5rem;
            }
            .nav-menu.open {
                display: flex;
            }
            .nav-menu a,
            .nav-menu .dropdown-toggle {
                display: block;
                padding: 0.4rem 0;
                text-decoration: none;
                color: #333;
                font-weight: 500;
                cursor: pointer;
            }
            .nav-menu .dropdown-menu {
                display: none;
                padding-left: 1rem;
                flex-direction: column;
            }
            .nav-menu .dropdown.open .dropdown-menu {
                display: flex;
            }
            .nav-menu .dropdown-toggle {
                cursor: pointer;
            }
            .nav-menu .dropdown-toggle::after {
                content: " ▾";
                font-size: 0.7rem;
            }
            .nav-menu .dropdown.open .dropdown-toggle::after {
                content: " ▴";
            }
            .sidebar-logout { display: none; }
            header .logout-mobile {
                display: block;
                margin-top: 0.25rem;
            }
            header .logout-mobile button {
                background: none;
                border: none;
                color: #333;
                font-weight: 500;
                cursor: pointer;
                padding: 0.4rem 0;
                font-size: 1rem;
            }
        }

        /* desktop/tablet: hide header, show sidebar toggle */
        @media (min-width: 768px) {
            header {
                display: none !important;
            }
            .sidebar-floating-toggle {
                display: none;
            }
            .sidebar.collapsed ~ .sidebar-floating-toggle {
                display: block;
            }
        }

        /* ----- SEARCHABLE-CREATABLE SELECT (unchanged) ----- */
        .searchable-select-wrapper {
            position: relative;
            width: 100%;
            user-select: none;
        }
        .ss-trigger {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background: white;
            cursor: pointer;
            font-size: 1rem;
        }
        .ss-arrow {
            font-size: 0.8rem;
            margin-left: 0.5rem;
            transition: transform 0.2s;
        }
        .searchable-select-wrapper.open .ss-arrow {
            transform: rotate(-180deg);
        }
        .ss-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 100;
            margin-top: 0.25rem;
            max-height: 260px;
            display: flex;
            flex-direction: column;
        }
        .ss-dropdown.hidden { display: none; }
        .searchable-select-wrapper.open .ss-dropdown { display: flex; }
        .ss-top-row {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding: 0.5rem;
            gap: 0.25rem;
        }
        .ss-top-row .ss-search {
            flex: 1;
            border: none;
            outline: none;
            padding: 0.5rem;
            font-size: 0.95rem;
        }
        .ss-create-btn {
            background: none;
            border: none;
            color: #4f46e5;
            font-weight: 500;
            cursor: pointer;
            white-space: nowrap;
            padding: 0.5rem;
        }
        .ss-create-btn:hover { text-decoration: underline; }
        .ss-create-link {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
            white-space: nowrap;
            padding: 0.5rem;
        }
        .ss-create-link:hover { text-decoration: underline; }
        .ss-create-area {
            padding: 0 0.5rem 0.5rem;
        }
        .ss-create-form {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            padding: 0.3rem;
        }
        .ss-create-form.hidden { display: none; }
        .ss-create-row {
            display: flex;
            gap: 0.25rem;
            align-items: center;
        }
        .ss-create-input {
            flex: 1;
            padding: 0.4rem;
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
            font-size: 0.95rem;
        }
        .ss-create-save {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 0.4rem 0.6rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-weight: 500;
        }
        .ss-create-save:hover { background: #4338ca; }
        .ss-create-cancel {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0 0.3rem;
        }
        .ss-create-form select {
            font-size: 0.85rem;
            padding: 0.2rem 0.3rem;
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
            width: auto;
        }
        .ss-options {
            overflow-y: auto;
            flex: 1;
        }
        .ss-option {
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
        }
        .ss-option:hover,
        .ss-option.selected {
            background: #e0e7ff;
            color: #3730a3;
        }
        .ss-option.hidden-by-search { display: none; }
        .hidden { display: none; }

        /* form card */
        .form-card {
            background: white;
            padding: 2rem;
            border-radius: 0.75rem;
            max-width: 640px;
            margin: 0 auto;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        .form-card .form-group {
            margin-bottom: 1.25rem;
        }
        .form-card label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.35rem;
            color: #374151;
            font-size: 0.9rem;
        }
        .form-card input,
        .form-card select,
        .form-card textarea {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.2s;
            background: #fff;
        }
        .form-card input:focus,
        .form-card select:focus,
        .form-card textarea:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            outline: none;
        }
        .form-row {
            display: flex;
            gap: 1rem;
        }
        .form-row .form-group {
            flex: 1;
        }
        .form-card .form-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-top: 2rem;
        }
        .form-card .btn-primary {
            background: #4f46e5;
            color: white;
            padding: 0.65rem 1.75rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
        }
        .form-card .btn-primary:hover { background: #4338ca; }
        .form-card .btn-secondary {
            background: #e5e7eb;
            color: #1f2937;
            border: 1px solid #d1d5db;
            padding: 0.65rem 1.75rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
        }
        .form-card .btn-secondary:hover { background: #d1d5db; }
        @media (max-width: 500px) {
            .form-row { flex-direction: column; gap: 0; }
            .form-card { padding: 1.25rem; }
        }
        .form-card input[type="checkbox"],
        .form-card input[type="radio"] {
            width: auto;
            margin-right: 0.5rem;
            vertical-align: middle;
        }
        .form-card label:has(input[type="checkbox"]) {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        .content .success {
            background: #d1fae5;
            color: #065f46;
            padding: 0.75rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>

    <!-- ===== SIDEBAR (desktop/tablet) ===== -->
    <aside class="sidebar" id="sidebar">
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">☰</button>
        <div class="sidebar-nav">
            <a href="{{ url('/dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('businesses.index') }}" class="{{ request()->routeIs('businesses.*') ? 'active' : '' }}">Businesses</a>
            <a href="{{ route('accounts.index') }}" class="{{ request()->routeIs('accounts.*') ? 'active' : '' }}">Accounts</a>
            <a href="{{ route('contacts.index') }}" class="{{ request()->routeIs('contacts.*') ? 'active' : '' }}">Contacts</a>
            <a href="{{ route('activity-logs.index') }}" class="{{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">History</a>
            <a href="{{ route('report.index') }}" class="{{ request()->routeIs('report.*') ? 'active' : '' }}">Report</a>

            <div class="dropdown" id="sidebarTransactionsDropdown">
                <span class="dropdown-toggle" id="sidebarTransactionsToggle">Transactions</span>
                <div class="dropdown-menu">
                    <a href="{{ route('transactions.index') }}" class="{{ request()->routeIs('transactions.index') ? 'active' : '' }}">All Transactions</a>
                    <a href="{{ route('transactions.create') }}" class="{{ request()->routeIs('transactions.create') ? 'active' : '' }}">Create Transaction</a>
                    <a href="{{ route('transaction-types.index') }}" class="{{ request()->routeIs('transaction-types.*') ? 'active' : '' }}">Types</a>
                    <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">Categories</a>
                    <a href="{{ route('currency-rates.index') }}" class="{{ request()->routeIs('currency-rates.*') ? 'active' : '' }}">Currency Rates</a>
                    <a href="{{ route('dues.index') }}" class="{{ request()->routeIs('dues.*') ? 'active' : '' }}">Dues</a>
                    <a href="{{ route('fixed-costs.index') }}" class="{{ request()->routeIs('fixed-costs.*') ? 'active' : '' }}">Fixed Costs</a>
                    <a href="{{ route('salary-report.index') }}" class="{{ request()->routeIs('salary-report.*') ? 'active' : '' }}">Salary Tracker</a>
                    <a href="{{ route('loans.index') }}" class="{{ request()->routeIs('loans.*') ? 'active' : '' }}">Loans</a>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">Profile</a>
        </div>
        <div class="sidebar-logout">
            <form method="POST" action="{{ route('logout') }}" id="sidebarLogoutForm">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </aside>

    <!-- floating toggle button (visible when sidebar is collapsed) -->
    <button class="sidebar-floating-toggle" id="floatingToggle" aria-label="Open sidebar">☰</button>

    <!-- ===== MOBILE HEADER (hidden on desktop/tablet) ===== -->
    <header>
        <div class="header-row">
            <span class="brand">{{ config('app.name') }}</span>
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>
        </div>
        <nav class="nav-menu" id="mobileNavMenu">
            <a href="{{ url('/dashboard') }}">Dashboard</a>
            <a href="{{ route('businesses.index') }}" class="{{ request()->routeIs('businesses.*') ? 'active' : '' }}">Businesses</a>
            <a href="{{ route('accounts.index') }}">Accounts</a>
            <a href="{{ route('contacts.index') }}" class="{{ request()->routeIs('contacts.*') ? 'active' : '' }}">Contacts</a>
            <a href="{{ route('activity-logs.index') }}" class="{{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">History</a>
            <a href="{{ route('report.index') }}" class="{{ request()->routeIs('report.*') ? 'active' : '' }}">Report</a>

            <div class="dropdown" id="mobileTransactionsDropdown">
                <span class="dropdown-toggle" id="mobileTransactionsToggle">Transactions ▾</span>
                <div class="dropdown-menu">
                    <a href="{{ route('transactions.index') }}">All Transactions</a>
                    <a href="{{ route('transactions.create') }}" class="{{ request()->routeIs('transactions.create') ? 'active' : '' }}">Create Transaction</a>
                    <a href="{{ route('transaction-types.index') }}">Types</a>
                    <a href="{{ route('categories.index') }}">Categories</a>
                    <a href="{{ route('currency-rates.index') }}">Currency Rates</a>
                    <a href="{{ route('dues.index') }}" class="{{ request()->routeIs('dues.*') ? 'active' : '' }}">Dues</a>
                    <a href="{{ route('fixed-costs.index') }}" class="{{ request()->routeIs('fixed-costs.*') ? 'active' : '' }}">Fixed Costs</a>
                    <a href="{{ route('salary-report.index') }}" class="{{ request()->routeIs('salary-report.*') ? 'active' : '' }}">Salary Tracker</a>
                    <a href="{{ route('loans.index') }}" class="{{ request()->routeIs('loans.*') ? 'active' : '' }}">Loans</a>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}">Profile</a>
            <div class="logout-mobile">
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </nav>
    </header>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="main-content" id="mainContent">
        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>

    <!-- ===== JAVASCRIPT ===== -->
    <script>
        (function() {
            // ===== SIDEBAR TOGGLE =====
            const sidebar = document.getElementById('sidebar');
            const floatingToggle = document.getElementById('floatingToggle');
            const sidebarToggle = document.getElementById('sidebarToggle');

            // check if sidebar is collapsed from previous session
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
            }

            function toggleSidebar() {
                sidebar.classList.toggle('collapsed');
                const nowCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', nowCollapsed ? 'true' : 'false');
            }

            sidebarToggle.addEventListener('click', toggleSidebar);
            floatingToggle.addEventListener('click', function() {
                sidebar.classList.remove('collapsed');
                localStorage.setItem('sidebarCollapsed', 'false');
            });

            // ===== SIDEBAR DROPDOWN TOGGLE =====
            const sidebarDropdownToggle = document.getElementById('sidebarTransactionsToggle');
            const sidebarDropdown = document.getElementById('sidebarTransactionsDropdown');

            if (sidebarDropdownToggle && sidebarDropdown) {
                sidebarDropdownToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    sidebarDropdown.classList.toggle('open');
                });
            }

            // ===== MOBILE DROPDOWN TOGGLE =====
            const mobileDropdownToggle = document.getElementById('mobileTransactionsToggle');
            const mobileDropdown = document.getElementById('mobileTransactionsDropdown');

            if (mobileDropdownToggle && mobileDropdown) {
                mobileDropdownToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    mobileDropdown.classList.toggle('open');
                });
            }

            // ===== MOBILE MENU TOGGLE =====
            window.toggleMobileMenu = function() {
                const menu = document.getElementById('mobileNavMenu');
                if (menu) {
                    menu.classList.toggle('open');
                }
            };

            // ===== SIDEBAR LOGOUT =====
            const logoutForm = document.getElementById('sidebarLogoutForm');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    // Allow normal form submission - no need to prevent default
                    // Just let it submit naturally
                });
            }

            // ===== CLOSE DROPDOWNS WHEN CLICKING OUTSIDE =====
            document.addEventListener('click', function(e) {
                // Close sidebar dropdown if clicking outside
                if (sidebarDropdown && !sidebarDropdown.contains(e.target)) {
                    sidebarDropdown.classList.remove('open');
                }
                // Close mobile dropdown if clicking outside
                if (mobileDropdown && !mobileDropdown.contains(e.target)) {
                    mobileDropdown.classList.remove('open');
                }
            });

        })();
    </script>

    <!-- ===== SEARCHABLE-CREATABLE SELECT ===== -->
    <script>
        (function() {
            let createAbortController = null;

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.searchable-select-wrapper')) {
                    document.querySelectorAll('.searchable-select-wrapper.open').forEach(function(el) {
                        el.classList.remove('open');
                    });
                }
            });

            window.filterSearchableSelect = function(input) {
                const dropdown = input.closest('.ss-dropdown');
                const options = dropdown.querySelectorAll('.ss-option');
                const filter = input.value.toLowerCase();
                options.forEach(function(opt) {
                    if (opt.textContent.toLowerCase().includes(filter)) {
                        opt.classList.remove('hidden-by-search');
                    } else {
                        opt.classList.add('hidden-by-search');
                    }
                });
            };

            window.selectSearchableOption = function(optionDiv) {
                const wrapper = optionDiv.closest('.searchable-select-wrapper');
                const hidden = wrapper.querySelector('input[type="hidden"]');
                const selectedText = wrapper.querySelector('.ss-selected-text');
                const dropdown = wrapper.querySelector('.ss-dropdown');

                hidden.value = optionDiv.dataset.value;
                selectedText.textContent = optionDiv.textContent.trim();

                wrapper.querySelectorAll('.ss-option.selected').forEach(function(el) {
                    el.classList.remove('selected');
                });
                optionDiv.classList.add('selected');

                wrapper.classList.remove('open');
                hidden.dispatchEvent(new Event('change', { bubbles: true }));
            };

            window.createAndSelectOption = async function(btn) {
                const form = btn.closest('.ss-create-form');
                const wrapper = form.closest('.searchable-select-wrapper');
                const input = form.querySelector('.ss-create-input');
                const name = input.value.trim();
                if (!name) return;

                const storeRoute = wrapper.dataset.store;
                const csrf = wrapper.dataset.csrf;
                const hidden = wrapper.querySelector('input[type="hidden"]');
                const optionsContainer = wrapper.querySelector('.ss-options');
                const selectedText = wrapper.querySelector('.ss-selected-text');

                if (createAbortController) {
                    createAbortController.abort();
                }
                createAbortController = new AbortController();
                const signal = createAbortController.signal;

                const extraData = {};
                form.querySelectorAll('input[type="hidden"]').forEach(function(el) {
                    extraData[el.name] = el.value;
                });
                form.querySelectorAll('select').forEach(function(el) {
                    extraData[el.name] = el.value;
                });

                try {
                    const response = await axios.post(storeRoute, {
                        name: name,
                        ...extraData
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        },
                        signal: signal
                    });

                    const data = response.data;

                    const newOption = document.createElement('div');
                    newOption.className = 'ss-option';
                    newOption.dataset.value = data.id;
                    newOption.textContent = data.name;
                    newOption.addEventListener('click', function() {
                        selectSearchableOption(newOption);
                    });
                    optionsContainer.appendChild(newOption);

                    selectSearchableOption(newOption);

                    if (typeof window.updateTransactionTypeContactMap === 'function' && data.effect !== undefined) {
                        window.updateTransactionTypeContactMap(
                            data.id,
                            data.effect,
                            !!data.transfer
                        );
                    }

                    input.value = '';
                    form.classList.add('hidden');

                } catch (error) {
                    if (axios.isCancel(error)) {
                        console.log('Request cancelled:', error.message);
                    } else {
                        alert('Could not create. Please try again.');
                        console.error('Axios error:', error);
                    }
                } finally {
                    if (createAbortController && createAbortController.signal === signal) {
                        createAbortController = null;
                    }
                }
            };
        })();
    </script>
</body>
</html>