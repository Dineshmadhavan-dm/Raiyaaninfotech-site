{{-- @php
    $hasSettingAccess =
        auth()->user()->can('setting view') ||
        auth()->user()->can('setting->applogo view') ||
        auth()->user()->can('setting->favicon view') ||
        auth()->user()->can('setting->sitecontrol view') ||
        auth()->user()->can('setting->themecolor view');

@endphp --}}
<x-tabnav>
    @section('title', 'Web Logo')
    <div class="main-container ">
        <!-- Vertical Tabs Navigation -->
        <div class="vertical-tabs ">
            {{-- @if ($hasSettingAccess) --}}
            <h6 class="fw-bold text-center mb-3 head">Setting</h6>
            @if (auth()->user()->hasRole('Super admin'))
                {{-- @can('setting->applogo view') --}}
                <a href="{{ route('applogo') }}" onclick="switchTab('tab1', event)">
                    <div class="tab-header {{ request()->routeIs('applogo') ? 'active' : '' }}">
                        <h1>Web App Logo</h1>
                    </div>
                </a>
                {{-- @endcan --}}
                {{-- @can('setting->favicon view') --}}
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
            {{-- @endcan --}}

            <a href="{{ route('cookies') }}" onclick="switchTab('tab5', event)">
                <div class="tab-header {{ request()->routeIs('cookies') ? 'active' : '' }}">
                    <h3>Cookies & Sessions</h3>
                </div>
            </a>
            {{-- @endif --}}
        </div>

        <!-- Tab Content Areas -->
        <div class="tab-content">
            <!-- Cookies & Sessions Tab -->

            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card shadow-lg border-0 rounded-lg p-5">
                            <div class="card-header ">
                                <h3 class="mb-0">
                                    <i class="bi bi-shield-lock me-2"></i>Cookies & Sessions Management
                                </h3>
                            </div>

                            <div class="card-body ">
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <strong>Warning:</strong> These actions will affect all users and may log people out
                                    of the system.
                                </div>

                                <div class="row g-4 mt-3">
                                    <!-- Clear Cookies Card -->
                                    <div class="col-md-6">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-3">
                                                        <i class="bi bi-cookie text-danger fs-4"></i>
                                                    </div>
                                                    <h4 class="card-title mb-0">Clear All Cookies</h4>
                                                </div>
                                                <p class="text-muted">
                                                    This will remove all browser cookies set by this website, including
                                                    authentication tokens.
                                                </p>
                                                <button id="clearCookiesBtn" class="btn btn-outline-danger w-100">
                                                    <i class="bi bi-trash3 me-2"></i> Clear Cookies
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Clear Sessions Card -->
                                    <div class="col-md-6">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                                                        <i class="bi bi-people text-info fs-4"></i>
                                                    </div>
                                                    <h4 class="card-title mb-0">Clear All Sessions</h4>
                                                </div>
                                                <p class="text-muted">
                                                    This will terminate all active sessions, logging out all users
                                                    immediately.
                                                </p>
                                                <button id="clearSessionsBtn" class="btn btn-outline-danger w-100">
                                                    <i class="bi bi-arrow-repeat me-2"></i> Clear Sessions
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Advanced Options -->
                                {{-- <div class="card border-0 shadow-sm mt-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">
                                            <i class="bi bi-gear me-2"></i>Advanced Options
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="preserveAuth">
                                            <label class="form-check-label" for="preserveAuth">
                                                Preserve authentication cookies (stay logged in)
                                            </label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="preservePreferences">
                                            <label class="form-check-label" for="preservePreferences">
                                                Preserve UI preferences (theme, language, etc.)
                                            </label>
                                        </div>
                                        <button id="clearAllBtn" class="btn btn-danger w-100">
                                            <i class="bi bi-exclamation-octagon me-2"></i>
                                            Clear Cookies & Sessions
                                        </button>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirmation Modal -->
            <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title" id="modalTitle">Confirm Action</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="modalBody">
                            Are you sure you want to proceed?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmAction">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>


            <script>
                $(document).ready(function() {
                    const modal = new bootstrap.Modal('#confirmModal');
                    let currentAction = '';

                    // Set up button handlers
                    $('#clearCookiesBtn').click(function() {
                        currentAction = 'cookies';
                        $('#modalTitle').text('Clear All Cookies');
                        $('#modalBody').html(`
            <p>This will remove all cookies from your browser for this website.</p>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                You may be logged out and preferences may be reset.
            </div>
        `);
                        modal.show();
                    });

                    $('#clearSessionsBtn').click(function() {
                        currentAction = 'sessions';
                        $('#modalTitle').text('Clear All Sessions');
                        $('#modalBody').html(`
            <p>This will terminate all active user sessions.</p>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-octagon me-2"></i>
                All users will be immediately logged out!
            </div>
        `);
                        modal.show();
                    });

                    $('#clearAllBtn').click(function() {
                        currentAction = 'all';
                        $('#modalTitle').text('Clear Cookies & Sessions');
                        $('#modalBody').html(`
            <p>This will perform both actions:</p>
            <ul>
                <li>Remove all browser cookies</li>
                <li>Terminate all active sessions</li>
            </ul>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-octagon me-2"></i>
                This will log out all users and reset all preferences!
            </div>
        `);
                        modal.show();
                    });

                    // Handle confirmation
                    $('#confirmAction').click(function() {
                        const $btn = $(this);
                        $btn.prop('disabled', true).html(
                            '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...'
                        );

                        $.ajax({
                            url: '{{ route('clear.cookies') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                action: currentAction,
                                preserveAuth: $('#preserveAuth').is(':checked'),
                                preservePreferences: $('#preservePreferences').is(':checked')
                            },
                            success: function(response) {
                                modal.hide();
                                if (response.success) {
                                    showToast('Success', response.message, 'success');
                                    if (response.reload) {
                                        setTimeout(() => location.reload(), 1500);
                                    }
                                } else {
                                    showToast('Error', response.message || 'Action failed', 'danger');
                                }
                            },
                            error: function(xhr) {
                                let message = 'An error occurred';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    message = xhr.responseJSON.message;
                                }
                                showToast('Error', message, 'danger');
                            },
                            complete: function() {
                                $btn.prop('disabled', false).text('Confirm');
                            }
                        });
                    });

                    function showToast(title, message, type) {
                        const toast = $(`
            <div class="toast align-items-center text-white bg-${type} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}:</strong> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `);

                        $('#toastContainer').append(toast);
                        setTimeout(() => toast.remove(), 5000);
                    }
                });
            </script>

            <div id="toastContainer" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11"></div>



            <style>
                .card {
                    transition: transform 0.2s;
                }

                .card:hover {
                    transform: translateY(-5px);
                }

                .form-switch .form-check-input {
                    width: 2.5em;
                    height: 1.4em;
                }


                .toast {
                    max-width: 350px;
                }
            </style>


        </div>
    </div>
</x-tabnav>
