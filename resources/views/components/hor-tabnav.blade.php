<div>
    <x-layout>
        <style>
            :root {
                --primary-color: #6366f1;
                --primary-hover: #4f46e5;
                --text-color: #1f2937;
                --bg-color: #f9fafb;
                --sidebar-bg: #ffffff;
                --border-color: #e5e7eb;
                --container-max-width: 1200px;
                --border-radius: 8px;
                --box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }



            .main-container {
                display: flex;
                flex-direction: column;
                background-color: var(--sidebar-bg);
                overflow: hidden;
                height: 100vh;

            }

            /* Main Tabs */
            /* Add to your existing CSS */
            .main-tabs {
                position: relative;
                display: flex;
                align-items: center;
                margin-top: 4em;
                padding-bottom: 30px;
            }

            .spacer {
                flex-grow: 1;
            }


            .brand-header {
                margin-right: 30px;
                font-weight: 700;
                color: var(--primary-);
                font-size: 1.2rem;
            }

            .tab-header {
                padding: 0 16px;
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
                display: flex;
                align-items: center;
                white-space: nowrap;
                height: 100%;
                color: #64748b;
                font-weight: 500;
            }

            .tab-header:hover {
                color: var(--primary-hover);
            }

            .tab-header.active {
                color: var(--primary-color);
                font-weight: 600;
                border-bottom: 3px solid var(--primary-color);
            }

            .tab-header i {
                margin-right: 8px;
                font-size: 0.9rem;
            }


            .profile-img {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                object-fit: cover;
            }

            /* Sub Tabs */
            .sub-tabs {
                display: none;
                background-color: #f8fafc;
                border-bottom: 1px solid var(--border-color);
                height: 50px;
                padding: 0 20px;

            }

            .sub-tabs.active {
                display: flex;
            }

            .sub-menu {
                display: flex;
                height: 100%;
            }

            .sub-menu .tab-header {
                font-size: 0.85rem;
                padding: 0 12px;
            }

            .has-dropdown {
                position: relative;
            }

            .dropdown-icon {
                margin-left: 5px;
                font-size: 0.7rem;
                transition: transform 0.2s;
            }

            .dropdown-menu {
                position: absolute;
                top: 100%;
                left: -60px;
                background-color: white;
                border-radius: var(--border-radius);
                box-shadow: var(--box-shadow);
                min-width: 180px;
                z-index: 100;
                display: none;
                padding: 5px 0;
            }

            .dropdown-menu.show {
                display: block;
            }

            .dropdown-item {
                padding: 8px 16px;
                cursor: pointer;
                font-size: 0.85rem;
                color: var(--text-color);
            }

            .dropdown-item:hover {
                background-color: #f1f5f9;
                color: var(--primary-color);
            }

            /* Tab Content */
            .tab-content {
                flex: 1;
                padding: 20px;
                background-color: var(--bg-color);
                overflow-y: auto;
            }

            .tab-pane {
                display: none;
                animation: fadeIn 0.3s ease;
            }

            .tab-pane.active {
                display: block;
            }

            /* Dashboard Grid */
            .dashboard-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 20px;
                margin-top: 20px;
            }

            .stat-card {
                background: white;
                border-radius: var(--border-radius);
                padding: 20px;
                box-shadow: var(--box-shadow);
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .stat-icon {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.2rem;
            }


            .stat-info h3 {
                font-size: 0.9rem;
                color: #64748b;
                margin-bottom: 5px;
            }

            .stat-info p {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--text-color);
                margin-bottom: 5px;
            }

            .stat-change {
                font-size: 0.75rem;
                display: block;
            }

            .positive {
                color: #10b981;
            }

            .negative {
                color: #ef4444;
            }

            .wide-card {
                grid-column: span 2;
            }

            .chart-placeholder {
                height: 200px;
                background-color: #f1f5f9;
                border-radius: var(--border-radius);
                margin-top: 15px;
            }

            .card {
                background: white;
                border-radius: var(--border-radius);
                padding: 20px;
                box-shadow: var(--box-shadow);
            }

            .card h3 {
                font-size: 1.1rem;
                margin-bottom: 15px;
                color: var(--text-color);
            }

            .leave-requests {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            .request-item {
                display: flex;
                align-items: center;
                gap: 15px;
                padding: 10px 0;
                border-bottom: 1px solid var(--border-color);
            }

            .request-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                object-fit: cover;
            }

            .request-info {
                flex: 1;
            }

            .request-info strong {
                display: block;
                font-size: 0.9rem;
            }

            .request-info span {
                font-size: 0.8rem;
                color: #64748b;
            }

            .request-actions {
                display: flex;
                gap: 10px;
            }



            .holidays-list {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            .holiday-item {
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .holiday-date {
                display: flex;
                flex-direction: column;
                align-items: center;
                background-color: #f1f5f9;
                padding: 8px 12px;
                border-radius: 6px;
                min-width: 50px;
            }

            .holiday-date .day {
                font-size: 1.2rem;
                font-weight: 700;
            }

            .holiday-date .month {
                font-size: 0.7rem;
                text-transform: uppercase;
                color: #64748b;
            }

            .holiday-info strong {
                display: block;
                font-size: 0.9rem;
            }

            .holiday-info span {
                font-size: 0.8rem;
                color: #64748b;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (max-width: 768px) {
                .dashboard-grid {
                    grid-template-columns: 1fr;
                }

                .wide-card {
                    grid-column: span 1;
                }

                .main-tabs {
                    padding: 0 10px;
                }

                .tab-header {
                    padding: 0 10px;
                    font-size: 0.9rem;
                }

                .brand-header {
                    margin-right: 15px;
                }

                .sub-tabs {
                    overflow: auto;
                }
            }
        </style>

        {{ $slot }}

        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Activate first tab by default
                document.querySelector('.main-tabs .tab-header:nth-child(2)').classList.add('active');
                document.getElementById('dashboard-tab').classList.add('active');

                // Hide all sub-tabs initially
                document.querySelectorAll('.sub-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
            });

            function switchMainTab(tabId, event) {
                // Hide all tab panes
                document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

                // Show selected tab pane
                document.getElementById(`${tabId}-tab`).classList.add('active');

                // Update active tab header
                document.querySelectorAll('.main-tabs .tab-header').forEach(header => header.classList.remove('active'));
                event.currentTarget.classList.add('active');

                // Handle sub-tabs visibility
                document.querySelectorAll('.sub-menu').forEach(menu => {
                    menu.style.display = 'none';
                });

                const subTabs = document.getElementById('subTabs');
                if (tabId !== 'dashboard') {
                    subTabs.style.display = 'flex';
                    document.getElementById(`${tabId}-sub`).style.display = 'flex';

                    // Activate first sub-tab
                    const firstSubTab = document.querySelector(`#${tabId}-sub .tab-header:first-child`);
                    if (firstSubTab) {
                        firstSubTab.classList.add('active');
                        loadSubTabContent(tabId, firstSubTab.getAttribute('onclick').match(/'([^']+)'/)[1]);
                    }
                } else {
                    subTabs.style.display = 'none';
                }
            }

            function switchSubTab(parentTab, subTabId) {
                // Update active sub-tab header
                const parentSubMenu = document.getElementById(`${parentTab}-sub`);
                parentSubMenu.querySelectorAll('.tab-header').forEach(header => header.classList.remove('active'));
                event.currentTarget.classList.add('active');

                // Load content for sub-tab
                loadSubTabContent(parentTab, subTabId);
            }

            function loadSubTabContent(parentTab, subTabId) {
                // In a real app, this would load content via AJAX or dynamically generate it
                const contentDiv = document.getElementById(`${parentTab}-content`);
                contentDiv.innerHTML = `<h3>${subTabId.replace('-', ' ').toUpperCase()} Content</h3>
                                        <p>This would display ${subTabId.replace('-', ' ')} information.</p>`;
            }

            function toggleDropdown(dropdownId, event) {
                event.stopPropagation();
                const dropdown = document.getElementById(dropdownId);
                const isShowing = dropdown.classList.contains('show');

                // Close all dropdowns first
                document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));

                // Toggle this dropdown if it wasn't showing
                if (!isShowing) {
                    dropdown.classList.add('show');
                }

                // Rotate chevron icon
                const icon = event.currentTarget.querySelector('.dropdown-icon');
                icon.style.transform = isShowing ? 'rotate(0deg)' : 'rotate(180deg)';
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function() {
                document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
                document.querySelectorAll('.dropdown-icon').forEach(icon => icon.style.transform = 'rotate(0deg)');
            });
        </script>
    </x-layout>
</div>
