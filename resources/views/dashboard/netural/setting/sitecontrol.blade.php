{{-- @php
    $hasSettingAccess =
        auth()->user()->can('setting view') ||
        auth()->user()->can('setting->applogo view') ||
        auth()->user()->can('setting->favicon view') ||
        auth()->user()->can('setting->sitecontrol view') ||
        auth()->user()->can('setting->themecolor view');

@endphp --}}
<x-tabnav>
    @section('title', 'Site Control')
    <div class="main-container ">
        <!-- Vertical Tabs Navigation -->
        <div class="vertical-tabs">
            {{-- @if ($hasSettingAccess) --}}
            <h6 class=" fw-bold text-center mb-3 head">Setting</h6>
            {{-- @can('setting->applogo view') --}}
            @if (auth()->user()->hasRole('Super admin'))
                <a href="{{ route('applogo') }}" onclick="switchTab('tab1', event)">
                    <div class="tab-header {{ request()->routeIs('applogo') ? 'active' : '' }}">
                        <h1>Web App Logo</h1>
                    </div>
                </a>
                {{-- @endcan
                @can('setting->favicon view') --}}
                <a href="{{ route('favicon') }}" onclick="switchTab('tab2', event)">
                    <div class="tab-header {{ request()->routeIs('favicon') ? 'active' : '' }}">
                        <h2>Favicon</h2>
                    </div>
                </a>
                {{-- @endcan
                @can('setting->sitecontrol view') --}}
                <a href="{{ route('sitecontrol') }}" onclick="switchTab('tab3', event)">
                    <div class="tab-header {{ request()->routeIs('sitecontrol') ? 'active' : '' }}">
                        <h3>Maintenance</h3>
                    </div>
                </a>
            @endif
            {{-- @endcan
                @can('setting->themecolor view') --}}
            <a href="{{ route('theme') }}" onclick="switchTab('tab4', event)">
                <div class="tab-header {{ request()->routeIs('theme') ? 'active' : '' }}">
                    <h3>Theme Color</h3>
                </div>
            </a>
            {{-- @endcan --}} <a href="{{ route('cookies') }}" onclick="switchTab('tab5', event)">
                <div class="tab-header {{ request()->routeIs('cookies') ? 'active' : '' }}">
                    <h3>Cookies & Sessions</h3>
                </div>
            </a>
            {{-- @endif --}}
        </div>

        <!-- Tab Content Areas -->
        <div class="tab-content">




            <div id="tab3" class="tab-pane {{ request()->routeIs('sitecontrol') ? 'active' : '' }}">
                <div class="settings-content">
                    <div class="content-card {{ request()->routeIs('sitecontrol') ? 'active' : '' }}">
                        <div class="card-header">
                            <h2>Site Maintenance</h2>
                            <p class="subtitle">Control your website's availability and maintenance mode</p>
                        </div>

                        <div class="card-body">
                            <div class="maintenance-control">
                                <div class="control-header">
                                    <h3>Site Status</h3>
                                    <p>Toggle to enable/disable maintenance mode</p>
                                </div>

                                <div class="toggle-wrapper">
                                    <div
                                        class="status-indicator {{ isset($site) && $site->status == 1 ? 'online' : 'offline' }}">
                                        <span class="status-badge"></span>
                                        <span class="status-text">
                                            {{ isset($site) && $site->status == 1 ? 'Site is Live' : 'Under Maintenance' }}
                                        </span>
                                    </div>

                                    <label class="modern-switch">
                                        <input type="checkbox" id="toggleSwitch"
                                            {{ isset($site) && $site->status == 1 ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </div>

                                <div class="maintenance-note">
                                    <i class="icon-info"></i>
                                    <p>When maintenance mode is enabled, visitors will see a maintenance page.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>



    </div>
    </div>









</x-tabnav>
<script>
    $(document).ready(function() {
        $("#toggleSwitch").change(function() {
            $.ajax({
                url: "{{ route('toggle.site.status') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    const statusElement = $(".status-indicator");
                    const statusText = $(".status-text");

                    if (response.status) {
                        statusElement.removeClass("offline").addClass("online");
                        statusText.text("Site is Live");
                    } else {
                        statusElement.removeClass("online").addClass("offline");
                        statusText.text("Under Maintenance");
                    }
                    location.reload();

                    // Add animation
                    statusElement.addClass("pulse");
                    setTimeout(() => statusElement.removeClass("pulse"), 500);
                }
            });
        });
    });
</script>






<style>
    /* Content Area Styles */
    .settings-content {
        flex: 1;
        padding: 2rem;
    }

    .content-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .card-header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .subtitle {
        color: #64748b;
        margin: 0.25rem 0 0;
        font-size: 0.875rem;
    }

    .card-body {
        padding: 2rem;
    }

    /* Maintenance Control Styles */
    .maintenance-control {
        max-width: 600px;
    }

    .control-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 0.5rem;
    }

    .control-header p {
        color: #64748b;
        margin: 0 0 1.5rem;
        font-size: 0.875rem;
    }

    .toggle-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .status-indicator {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .status-badge {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .status-indicator.online .status-badge {
        background: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }

    .status-indicator.offline .status-badge {
        background: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
    }

    .status-text {
        font-weight: 500;
        font-size: 1rem;
    }

    .status-indicator.online .status-text {
        color: #10b981;
    }

    .status-indicator.offline .status-text {
        color: #ef4444;
    }

    /* Modern Switch */
    .modern-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .modern-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #e2e8f0;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: var(--ra-primary-set);
    }

    input:checked+.slider:before {
        transform: translateX(26px);
    }

    /* Maintenance Note */
    .maintenance-note {
        display: flex;
        gap: 0.75rem;
        background: #f8fafc;
        padding: 1rem;
        border-radius: 0.5rem;
        color: #64748b;
        font-size: 0.875rem;
    }

    .maintenance-note i {
        color: #94a3b8;
        font-size: 1.25rem;
    }

    /* Animation */
    .pulse {
        animation: pulse 0.5s ease;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.02);
        }

        100% {
            transform: scale(1);
        }
    }
</style>
