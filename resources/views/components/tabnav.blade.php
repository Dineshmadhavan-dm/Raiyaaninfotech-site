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
                --border-radius: 12px;
            }



            .main-container {
                display: flex;

                background-color: var(--sidebar-bg);

                overflow: hidden;
                height: 90vh;

            }

            @media (max-width: 600px) {

                .main-container {
                    display: grid;

                    background-color: var(--sidebar-bg);

                    overflow: hidden;
                    height: 90vh;

                }

                .vertical-tabs {

                    width: auto;


                    display: flex;
                    flex-direction: column;
                    padding: 20px 0;
                }


            }

            .vertical-tabs .head {
                font-size: 20px;
                color: #595c5f;
            }

            /* Vertical Tabs - Modern Style */
            .vertical-tabs {


                width: auto;
                background-color: var(--sidebar-bg);
                border-right: 1px solid var(--border-color);
                display: flex;
                flex-direction: column;
                padding: 20px 0;
            }

            .tab-header {
                padding: 14px 28px;
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
                display: flex;
                align-items: center;
                margin: 0 10px;
                /* border-radius: 8px; */
            }

            .tab-header:hover {
                color: var(--ra-primary-set);
            }

            .tab-header.active {
                background-color: var(--ra-primary-set);
                color: #eef2ff;

            }

            .tab-header.active::before {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 3px;
                background-color: #3f476449;
                /* background-color: var(--primary-color); */
                border-radius: 0 3px 3px 0;
            }

            .tab-header h1,
            .tab-header h2,
            .tab-header h3 {
                margin: 0;
                font-size: 0.95rem;
                font-weight: 500;
            }

            .tab-header h1 {
                font-size: 1rem;
                font-weight: 500;
            }

            /* Tab Content - Modern Style */
            .tab-content {
                flex: 1;
                padding: 40px;
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

            .tab-pane h1,
            .tab-pane h2,
            .tab-pane h3 {
                color: var(--ra-primary-set);
                margin-top: 0;
            }

            .tab-pane h1 {
                font-size: 2rem;
                margin-bottom: 1.5rem;
                font-weight: 700;
            }

            .tab-pane h2 {
                font-size: 1.5rem;
                margin-bottom: 1.25rem;
                font-weight: 600;
            }

            .tab-pane h3 {
                font-size: 1.25rem;
                margin-bottom: 1rem;
                font-weight: 500;
            }

            .tab-pane p {
                line-height: 1.7;
                color: #4b5563;
                margin-bottom: 1.5rem;
                max-width: 800px;
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

            a {
                text-decoration: none;
                color: #4b5563;
            }
        </style>

        {{ $slot }}



        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Check if any tab is already active
                const hasActiveTab = document.querySelector('.tab-header.active');

                // If no tab is active, make the first tab active by default
                if (!hasActiveTab && document.querySelector('.tab-header')) {
                    const firstTab = document.querySelector('.tab-header');
                    firstTab.classList.add('active');

                    // Also activate the corresponding tab pane if exists
                    const firstTabPane = document.querySelector('.tab-pane');
                    if (firstTabPane) {
                        firstTabPane.classList.add('active');
                    }
                }
            });

            function switchTab(tabId, event) {
                // Hide all tab panes
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('active');
                });

                // Show selected tab pane
                document.getElementById(tabId).classList.add('active');

                // Update active tab header
                document.querySelectorAll('.tab-header').forEach(header => {
                    header.classList.remove('active');
                });

                // Add active class to clicked tab header
                event.currentTarget.classList.add('active');
            }
        </script>

    </x-layout>
</div>
