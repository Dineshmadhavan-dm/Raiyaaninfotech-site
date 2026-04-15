@php
    $hasSettingAccess =
        auth()->user()->can('setting view') ||
        auth()->user()->can('setting->applogo view') ||
        auth()->user()->can('setting->favicon view') ||
        auth()->user()->can('setting->sitecontrol view') ||
        auth()->user()->can('setting->themecolor view');

@endphp
<div>

    <!doctype html>
    <html lang="en">

    <head>
        <title>@yield('title', 'Kanban board panel')</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- Favicon for most browsers -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favlogo/' . $settings->favlogo) }}">

        {{-- plugin's --}}



        {{-- country code --}}
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css" />

        {{-- bv5 css --}}
        <link href="{{ asset('assets/dist/css/bootstrap.min.css') }}" rel="stylesheet" />

        {{-- site css --}}
        <link href="{{ asset('assets/dist/css/ra.css') }}" rel="stylesheet" />

        {{-- cropper css --}}
        <link href="{{ asset('assets/dist/css/cropper.css') }}" rel="stylesheet" />

        {{-- bv5 icon css --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">



        <link rel="stylesheet" href="{{ asset('assets/dist/css/datatable.css') }}">
        <script src="{{ asset('assets/dist/js/jquery.js') }}"></script>

        <script type="text/javascript" src="{{ asset('assets/dist/js/ui-j.js') }}"></script>




    </head>

    <body>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                function setFavicon(url) {
                    // Remove any existing favicon links
                    document.querySelectorAll("link[rel~='icon']").forEach(link => link.remove());

                    // Create new link
                    const link = document.createElement('link');
                    link.rel = 'icon';
                    link.href = url;
                    document.head.appendChild(link);

                    // For older browsers
                    const shortcut = document.createElement('link');
                    shortcut.rel = 'shortcut icon';
                    shortcut.href = url;
                    document.head.appendChild(shortcut);
                }

                setFavicon("{{ asset('favlogo/' . $settings->favlogo) }}");
            });
        </script>

        <div class="d-flex">



            <div class="main-content-chattask ">

                <nav class="navbar  navbar-expand-lg  sticky-top " style=" padding: 11.5px 20px;">
                    <div class="container-fluid ">






                   <div class="d-flex align-items-center w-100">
    <div class="ms-auto d-flex align-items-center">
<div class="dropdown me-3 me-lg-4">
    <a class="position-relative nav-icon" href="#" role="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell fs-5"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <span id="notificationCount">0</span>
            <span class="visually-hidden">unread notifications</span>
        </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end dropdown-notifications p-0" aria-labelledby="notificationDropdown" style="width: 350px;">
        <li>
            <div class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2">
                <h6 class="mb-0">Task Notifications</h6>
                <button class="btn btn-sm btn-link p-0" id="markAllAsReadBtn" style="font-size: 0.875rem;">
                    Mark all as read
                </button>
            </div>
        </li>
        <li>
            <div id="notificationsList" class="px-3 py-2" style="max-height: 400px; overflow-y: auto;">
                <div class="text-center py-3">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <span class="text-muted ms-2">Loading notifications...</span>
                </div>
            </div>
        </li>
        <li>
            <hr class="dropdown-divider my-0">
        </li>
        <li>
            <a class="dropdown-item text-center py-2" href="{{ route('etaskchat') }}">
                <i class="bi bi-chat-left-text me-1"></i> View All Messages
            </a>
        </li>
    </ul>
</div>






                            <!-- User profile dropdown -->

                            <div class="dropdown">
                                <a href="#"
                                    class="d-flex align-items-center text-decoration-none dropdown-toggle profilename"
                                    id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">



                                    @php
                                        $user = Auth::user();
                                    @endphp

                                    @if ($user->categorie == 2)
                                                                    {{-- Employee --}}
                                                                    @php
                                                                        $employee = $user->employee;
                                                                    @endphp
                                                                    <img src="{{ $employee && $employee->image
                                        ? asset('employee_images/' . $employee->image)
                                        : asset('images/admin_default.jpg') }}"
                                                                        alt="{{ $employee ? $employee->fullname : $user->name }}"
                                                                        class="img-fluid rounded-circle me-2" width="45">
                                    @elseif ($user->categorie == 3)
                                                                    @php
                                                                        $employee = $user->employee;
                                                                    @endphp
                                                                    {{-- Admin --}}
                                                                    <img src="{{ $employee && $employee->image
                                        ? asset('employee_images/' . $employee->image)
                                        : asset('images/admin_default.jpg') }}"
                                                                        alt="{{ $employee ? $employee->fullname : $user->name }}"
                                                                        class="img-fluid rounded-circle me-2" width="45">
                                    @elseif ($user->categorie == 1)
                                        {{-- Super Admin --}}
                                        <img src="{{ $user->image ? asset('admin_images/' . $user->image) : asset('images/admin_default.jpg') }}"
                                            alt="{{ $user->name }}" class="img-fluid rounded-circle me-2" width="45">
                                    @endif







                                    <span
                                        class="d-none d-lg-inline-block fw-bold ">{{ Str::ucfirst(Auth::user()->name) }}
                                    </span>
                                    <small class="d-none d-lg-inline-block" style=" position:absolute;

                                        left:60px;
                                        top:33px;

                                        ">[{{ Auth::user()->getRoleNames()?->implode(', ') }}]</small>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end listnav" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="{{ route('profile') }}"><i
                                                class="bi bi-person me-2"></i>Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('cpwd') }}"><i
                                                class="bi bi-key me-2"></i>Change Password</a></li>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"><i
                                                class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
                                </ul>
                            </div></div>
                        </div>















<div id="bottomNotificationToast" class="notifity-bottom d-none">
    <div class="bottom-toast-content">
        <div class="d-flex align-items-start">
            <div class="flex-shrink-0">
                <i class="bi bi-bell-fill fs-5 text-primary me-2"></i>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-center mb-1">
                    <img id="bottomNotifSenderImage" src="" alt="Sender" class="rounded-circle me-2" width="30" height="30" style="object-fit: cover;">
                    <strong id="bottomNotifSenderName" class="text-primary"></strong>
                    <small id="bottomNotifTime" class="text-muted ms-auto"></small>
                </div>
                <p id="bottomNotifMessage" class="mb-1 small"></p>
                <div class="d-flex align-items-center">
                    <span id="bottomNotifTaskName" class="badge bg-primary bg-opacity-10 text-primary me-2"></span>
                    <span id="bottomNotifSubtaskName" class="badge bg-success bg-opacity-10 text-success"></span>
                </div>
            </div>
            <button type="button" class="btn-close ms-2" onclick="hideBottomNotification()"></button>
        </div>
    </div>
</div>


      {{--notifi  --}}










<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<script>
$(document).ready(function() {
    let notificationCheckInterval;
    let soundEnabled = true;
    const notificationSound = new Audio('{{ asset("sounds/notification.mp3") }}');
    let bottomNotificationTimeout;
    let isOnChatPage = window.location.pathname.includes('etaskchat');

    function showBottomNotification(notificationData) {
        if (isOnChatPage) {
            return;
        }

        const toastContainer = $('#bottomNotificationToast');

        $('#bottomNotifSenderImage').attr('src', notificationData.sender_image);
        $('#bottomNotifSenderName').text(notificationData.sender_name);
        $('#bottomNotifTime').text(notificationData.time);
   $('#bottomNotifMessage').text(notificationData.plain_message );

        if (notificationData.task_title) {
            $('#bottomNotifTaskName').text('Task: ' + notificationData.task_title).show();
        } else {
            $('#bottomNotifTaskName').hide();
        }

        if (notificationData.subtask_title) {
            $('#bottomNotifSubtaskName').text('Subtask: ' + notificationData.subtask_title).show();
        } else {
            $('#bottomNotifSubtaskName').hide();
        }

        toastContainer.removeClass('d-none').addClass('d-block').fadeIn(300);

        if (bottomNotificationTimeout) {
            clearTimeout(bottomNotificationTimeout);
        }

        bottomNotificationTimeout = setTimeout(hideBottomNotification, 30000);

        toastContainer.off('click').on('click', function(e) {
            if (!$(e.target).hasClass('btn-close')) {
                hideBottomNotification();
                $('#notificationDropdown').dropdown('show');
            }
        });
    }

    function hideBottomNotification() {
        if (bottomNotificationTimeout) {
            clearTimeout(bottomNotificationTimeout);
        }
        $('#bottomNotificationToast').fadeOut(300, function() {
            $(this).removeClass('d-block').addClass('d-none');
        });
    }

    function loadNotifications() {
        $.ajax({
            url: '{{ route("etaskchat.notifications") }}',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    updateNotificationCount(response.count);
                    renderNotifications(response.notifications);

                    if (response.count > 0 && soundEnabled && !$('#notificationDropdown').hasClass('show')) {
                        notificationSound.play().catch(e => console.log('Audio play failed:', e));

                        const latestNotif = response.notifications[0];
                        if (latestNotif && !isOnChatPage) {
                            showBottomNotification(latestNotif);
                        }
                    }
                }
            },
            error: function(xhr) {
                console.error('Failed to load notifications');
            }
        });
    }

    function updateNotificationCount(count) {
        $('#notificationCount').text(count);
        const badge = $('#notificationCount').closest('.badge');

        if (count > 0) {
            badge.removeClass('bg-danger-subtle').addClass('bg-danger');
            badge.find('span').removeClass('text-dark');
        } else {
            badge.removeClass('bg-danger').addClass('bg-danger-subtle');
            badge.find('span').addClass('text-dark');
        }
    }

    function renderNotifications(notifications) {
        const container = $('#notificationsList');

        if (notifications.length === 0) {
            container.html(`
                <div class="text-center py-4">
                    <i class="bi bi-bell-slash text-muted" style="font-size: 2rem;"></i>
                    <p class="text-muted mt-2 mb-0">No new notifications</p>
                </div>
            `);
            return;
        }

        let html = '';
        notifications.forEach(notif => {
            const messagePreview = notif.message ?
                (notif.message.length > 50 ? notif.message.substring(0, 50) + '...' : notif.message) :
                'Sent an attachment';

            let redirectUrl = '';
            let itemType = '';
            let itemId = '';

            if (notif.type === 'task' && notif.task_id) {
                redirectUrl = `/dashboard/employees/etaskchat`;
                itemType = 'task';
                itemId = notif.task_id;
            } else if (notif.type === 'subtask' && notif.subtask_id) {
                redirectUrl = `/dashboard/employees/etaskchat`;
                itemType = 'subtask';
                itemId = notif.subtask_id;
            }

            html += `
                <div class="notification-item mb-2 p-2 rounded border"
                     data-id="${notif.id}"
                     data-type="${notif.type}"
                     data-task-id="${notif.task_id || ''}"
                     data-subtask-id="${notif.subtask_id || ''}"
                     data-parent-task-id="${notif.parent_task_id || ''}"
                     style="cursor: pointer;">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <img src="${notif.sender_image}"
                                 alt="${notif.sender_name}"
                                 class="rounded-circle"
                                 width="40"
                                 height="40"
                                 style="object-fit: cover;">
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <strong class="text-primary" style="font-size: 0.9rem;">${notif.sender_name}</strong>
                                <small class="text-muted">${notif.time}</small>
                            </div>
                            <p class="mb-0" style="font-size: 0.85rem;">
                                ${messagePreview}
                            </p>
                            ${notif.has_files ? '<small class="text-muted d-block mt-1">📎 File attached</small>' : ''}
                            ${notif.type === 'task' && notif.task_title ?
                                `<small class="text-muted d-block mt-1">Task: ${notif.task_title}</small>` : ''}
                            ${notif.type === 'subtask' && notif.subtask_title ?
                                `<small class="text-muted d-block mt-1">Subtask: ${notif.subtask_title}</small>` : ''}
                        </div>
                    </div>
                </div>
            `;
        });

        container.html(html);

        $('.notification-item').off('click').on('click', function() {
            const notificationId = $(this).data('id');
            const notificationType = $(this).data('type');
            const taskId = $(this).data('task-id');
            const subtaskId = $(this).data('subtask-id');

            markAsRead(notificationId);

            if (notificationType === 'task' && taskId) {
                window.location.href = `/dashboard/employees/etaskchat?focus=task-${taskId}`;
            } else if (notificationType === 'subtask' && subtaskId) {
                window.location.href = `/dashboard/employees/etaskchat?focus=subtask-${subtaskId}`;
            }
        });
    }

    function markAsRead(notificationId) {
        $.ajax({
            url: `{{ url('etaskchat/notifications') }}/${notificationId}/read`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    loadNotifications();
                }
            }
        });
    }

    $('#markAllAsReadBtn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        $.ajax({
            url: '{{ route("etaskchat.notifications.read-all") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    loadNotifications();
                    hideBottomNotification();
                }
            }
        });
    });

    function startNotificationPolling() {
        notificationCheckInterval = setInterval(loadNotifications, 30000);
    }

    function stopNotificationPolling() {
        if (notificationCheckInterval) {
            clearInterval(notificationCheckInterval);
        }
    }

    $('#notificationDropdown').on('show.bs.dropdown', function() {
        loadNotifications();
        stopNotificationPolling();
        hideBottomNotification();
    });

    $('#notificationDropdown').on('hide.bs.dropdown', function() {
        startNotificationPolling();
    });

    startNotificationPolling();
    loadNotifications();

    if (isOnChatPage) {
        hideBottomNotification();
    }

    Echo.private(`task-notifications.${userId}`)
        .listen('NewTaskMessage', (e) => {
            loadNotifications();

            if (soundEnabled && !isOnChatPage) {
                notificationSound.play().catch(e => console.log('Audio play failed:', e));
            }

            if (!isOnChatPage) {
                const notificationData = {
                    sender_image: e.notification.sender_image || '{{ asset("images/admin_default.jpg") }}',
                    sender_name: e.notification.sender_name || 'Unknown User',
                    time: 'Just now',
                    message: e.notification.message || 'New message received',
                    task_title: e.notification.task_title || '',
                    subtask_title: e.notification.subtask_title || ''
                };
                showBottomNotification(notificationData);
            }
        });
});
</script>

<style>
.notification-item {
    transition: all 0.2s ease;
    cursor: pointer;
    border-left: 3px solid #4477b6 !important;
}

.notification-item:hover {
    background-color: #f8f9fa;
    transform: translateX(-2px);
}

.notification-image-preview img {
    max-width: 100%;
    height: auto;
    border: 1px solid #dee2e6;
}

.dropdown-notifications {
    max-height: 500px;
    overflow-y: auto;
}

#notificationsList::-webkit-scrollbar {
    width: 6px;
}

#notificationsList::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

#notificationsList::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

#notificationsList::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>

<style>
.notifity-bottom {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 380px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    border: 1px solid #dee2e6;
    border-left: 4px solid #4477b6;
    z-index: 1060;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.notifity-bottom:hover {
    box-shadow: 0 12px 40px rgba(0,0,0,0.2);
    transform: translateY(-3px);
}

.bottom-toast-content {
    width: 100%;
}

.notifity-bottom .bi-bell-fill {
    color: #4477b6;
    margin-top: 3px;
}

.notifity-bottom .badge {
    font-size: 0.7rem;
    padding: 3px 8px;
    border-radius: 4px;
}

.notifity-bottom small {
    font-size: 0.75rem;
}

.notifity-bottom p {
    font-size: 0.85rem;
    line-height: 1.4;
}

@keyframes slideInUp {
    from {
        transform: translateY(100px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.notifity-bottom {
    animation: slideInUp 0.3s ease;
}

.notification-item {
    transition: all 0.2s ease;
    cursor: pointer;
    border-left: 3px solid #4477b6 !important;
}

.notification-item:hover {
    background-color: #f8f9fa;
    transform: translateX(-2px);
}

.dropdown-notifications {
    max-height: 500px;
    overflow-y: auto;
}

#notificationsList::-webkit-scrollbar {
    width: 6px;
}

#notificationsList::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

#notificationsList::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

#notificationsList::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>



            {{--notifi  --}}






                    </div>
                </nav>

                {{ $slot }}


            </div>
        </div>




        {{-- country code javascript --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>



        {{-- sweetalert javascript --}}
        <script src="{{ asset('assets/dist/js/sweetalert.js') }}"></script>



        {{-- bv5 javascript --}}
        <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/dist/js/ra.js') }}"></script>




        {{-- cropper javascript --}}
        <script src="{{ asset('assets/dist/js/cropper.js') }}"></script>





        {{-- datatable jquery javascript --}}
        <script src="{{ asset('assets/dist/js/datatable/jqdatatable.js') }}"></script>

        <script src="{{ asset('assets/dist/js/datatable/bsdatatable.js') }}"></script>




        {{-- datatable button javascript --}}
        <script src="{{ asset('assets/dist/js/datatable/btndatatable.js') }}"></script>
        <script src="{{ asset('assets/dist/js/datatable/html5.js') }}"></script>
        <script src="{{ asset('assets/dist/js/datatable/print.js') }}"></script>
        <script src="{{ asset('assets/dist/js/datatable/columnvision.js') }}"></script>
        <script src="{{ asset('assets/dist/js/datatable/jszip.js') }}"></script>
        <script src="{{ asset('assets/dist/js/datatable/pdf.js') }}"></script>
        <script src="{{ asset('assets/dist/js/datatable/font.js') }}"></script>








        <script>
            // Add this JavaScript
            document.addEventListener('DOMContentLoaded', function () {
                const navLinks = document.querySelectorAll('.sidebar-menu .nav-link:not(.submenu-toggle)');

                navLinks.forEach(link => {
                    link.addEventListener('click', function (e) {
                        // Remove active class from all links
                        navLinks.forEach(l => l.classList.remove('active'));

                        // Add active class only to clicked link
                        this.classList.add('active');


                    });
                });
            });

            document.addEventListener('DOMContentLoaded', function () {
                $.extend(true, $.fn.dataTable.defaults, {
                    pageLength: 5,
                    lengthMenu: [5, 10, 25, 50, 100], // Optional: customize dropdown
                    responsive: true,
                    paging: true
                });
            });
        </script>


        <style>
            :root {
                --h-color:
                    {{ $settings->h_color ?? '#ffffff' }}
                ;
                --ra-primary-set:
                    {{ $settings->p_color ?? '#4477b6' }}
                ;
                --s-color:
                    {{ $settings->s_color ?? '#ffffff' }}
                ;
                --nh-color:
                    {{ $settings->nh_color ?? '#ffffff' }}
                ;
            }

            .logo {
                background-color: var(--h-color) !important;
            }

            .sidebar {
                background-color: var(--s-color) !important;
            }

            .navbar {
                background-color: var(--nh-color) !important;
            }

            /* Add other elements that use these colors */
            table.dataTable>thead .sorting:before,
            table.dataTable>thead .sorting_asc:before,
            table.dataTable>thead .sorting_desc:before,
            table.dataTable>thead .sorting_asc_disabled:before,
            table.dataTable>thead .sorting_desc_disabled:before {

                right: 1em;
                content: "" !important;
            }

            table.dataTable>thead .sorting:after,
            table.dataTable>thead .sorting_asc:after,
            table.dataTable>thead .sorting_desc:after,
            table.dataTable>thead .sorting_asc_disabled:after,
            table.dataTable>thead .sorting_desc_disabled:after {
                right: 0.5em;
                content: "" !important;
            }
        </style>





<script>
function hideBottomNotification() {
    $('#bottomNotificationToast').fadeOut(300, function() {
        $(this).removeClass('d-block').addClass('d-none');
    });
}
</script>

    </body>

    </html>


</div>
