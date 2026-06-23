<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f9f9f9; margin: 0; }
        header { background: #fff; padding: 1rem 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        header nav { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
        header nav a, header nav span { text-decoration: none; color: #333; font-weight: 500; }
        header nav a:hover { color: #4f46e5; }

        .dropdown { position: relative; display: inline-block; }
        .dropdown-toggle { cursor: default; }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
            border-radius: 0.375rem;
            padding: 0.5rem 0;
            min-width: 150px;
            z-index: 1000;
        }
        .dropdown:hover .dropdown-menu { display: block; }
        .dropdown-menu a {
            display: block;
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: #333;
            white-space: nowrap;
        }
        .dropdown-menu a:hover { background: #f3f4f6; }

        .content { padding: 2rem; max-width: 1200px; margin: auto; }

        .hidden { display: none; }

        /* Mobile menu toggle */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }
        @media (max-width: 767px) {
            header { flex-direction: column; align-items: flex-start; }
            header nav { flex-direction: column; width: 100%; gap: 0.5rem; margin-top: 0.5rem; }
            header nav a { display: block; padding: 0.25rem 0; }
            .dropdown-menu { position: static; box-shadow: none; padding-left: 1rem; }
            .content { padding: 1rem; }
            .mobile-menu-btn { display: block; }
            .nav-menu { display: none; width: 100%; }
            .nav-menu.open { display: flex; flex-direction: column; }
        }
        /* ----- searchable-creatable-select component ----- */
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

        /* 👇 UPDATED: now stacks extra fields vertically */
        .ss-create-form {
            display: flex;
            flex-direction: column;       /* column layout for extra fields */
            gap: 0.3rem;
            padding: 0.3rem;
        }
        .ss-create-form.hidden { display: none; }

        /* row for name input + save/cancel */
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

        /* extra selects inside the creation form */
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
        /* ---------- Modern form card ---------- */
        .form-card {
            background: white;
            padding: 2rem;
            border-radius: 0.75rem;
            max-width: 640px;
            margin: 0 auto;               /* centre the card */
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

        /* Two‑column row */
        .form-row {
            display: flex;
            gap: 1rem;
        }
        .form-row .form-group {
            flex: 1;
        }

        /* Buttons */
        .form-card .form-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-top: 2rem;
        }

        .form-card .btn-primary {
            background: #46e54c;
            color: white;
            padding: 0.65rem 1.75rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
        }
        .form-card .btn-primary:hover { background: #4338ca; }

        .form-card .btn-secondary {
            background: #4f46e5;
            color: white;
            border: 1px solid #d1d5db;
            padding: 0.65rem 1.75rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
        }
        .form-card .btn-secondary:hover { background: #46e54c; }

        /* On small screens, two‑column rows stack */
        @media (max-width: 500px) {
            .form-row { flex-direction: column; gap: 0; }
            .form-card { padding: 1.25rem; }
        }
        /* Reset checkbox & radio button widths */
        .form-card input[type="checkbox"],
        .form-card input[type="radio"] {
            width: auto;               /* don't stretch */
            margin-right: 0.5rem;
            vertical-align: middle;
        }

        /* Better label alignment for checkboxes */
        .form-card label input[type="checkbox"] + span,
        .form-card label:has(input[type="checkbox"]) {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <header>
        <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
            <span style="font-weight: bold;">{{ config('app.name') }}</span>
            <button class="mobile-menu-btn" onclick="document.querySelector('.nav-menu').classList.toggle('open')">☰</button>
        </div>
        <nav class="nav-menu">
            <a href="{{ url('/dashboard') }}">Dashboard</a>
            <a href="{{ route('businesses.index') }}" class="{{ request()->routeIs('businesses.*') ? 'active' : '' }}">Businesses</a>
            <a href="{{ route('accounts.index') }}">Accounts</a>
            <a href="{{ route('contacts.index') }}" class="{{ request()->routeIs('contacts.*') ? 'active' : '' }}">Contacts</a>
            <a href="{{ route('activity-logs.index') }}" class="{{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">History</a>
            <a href="{{ route('report.index') }}" class="{{ request()->routeIs('report.*') ? 'active' : '' }}">Report</a>

            <div class="dropdown">
                <span class="dropdown-toggle">Transactions ▾</span>
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
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: #333; font-weight: 500; cursor: pointer;">Logout</button>
            </form>
        </nav>
    </header>
    <div class="content">
        @if (session('success'))
            <div style="background:#d1fae5; color:#065f46; padding: 0.75rem;">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>
    <!--searchbale-creatable-select-->
    <script>
        (function() {
            // Abort controller for the latest creation request
            let createAbortController = null;

            // Close all open dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.searchable-select-wrapper')) {
                    document.querySelectorAll('.searchable-select-wrapper.open').forEach(function(el) {
                        el.classList.remove('open');
                    });
                }
            });

            // Filter options
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

            // Select an option
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

            // Create and select option using Axios (with request cancellation)
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

                // Cancel any previous pending request
                if (createAbortController) {
                    createAbortController.abort();
                }
                createAbortController = new AbortController();
                const signal = createAbortController.signal;

                // Gather extra fields
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

                    const data = response.data;   // { id, name, effect, transfer (optional) }

                    // Add new option to dropdown
                    const newOption = document.createElement('div');
                    newOption.className = 'ss-option';
                    newOption.dataset.value = data.id;
                    newOption.textContent = data.name;
                    newOption.addEventListener('click', function() {
                        selectSearchableOption(newOption);
                    });
                    optionsContainer.appendChild(newOption);

                    // Select the new option
                    selectSearchableOption(newOption);

                    // 🎯 Update the contact‑type mapping if the page provides the hook
                    if (typeof window.updateTransactionTypeContactMap === 'function' && data.effect !== undefined) {
                        window.updateTransactionTypeContactMap(
                            data.id,
                            data.effect,
                            !!data.transfer   // default false if not present
                        );
                    }

                    // Clear and hide the form
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
        })(); // <-- This was missing! It closes the IIFE.
    </script>
</body>
</html>
