@php
    $hasSettingAccess =
        auth()->user()->can('setting view') ||
        auth()->user()->can('setting->applogo view') ||
        auth()->user()->can('setting->favicon view') ||
        auth()->user()->can('setting->sitecontrol view') ||
        auth()->user()->can('setting->themecolor view');
    $defaultTitle = auth()->check()
        ? (auth()->user()->categorie == 3 ? 'Admin Panel '
            : (auth()->user()->categorie == 2 ? 'Employee Panel' : ' Super Admin Panel'))
        : 'Admin Panel';
@endphp
<div>

    <!doctype html>
    <html lang="en">

    <head>



        <title>
            @hasSection('title')
                @yield('title') | {{ $defaultTitle }}
            @else
                {{ $defaultTitle }}
            @endif
        </title>


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
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="logo ">

                    @if ($appSettings->weblogo)
                        <img src="{{ asset('weblogo/' . $appSettings->weblogo) }}" alt="Website Logo" class="" id="user"
                            width="55px">
                    @else
                        <img src="{{ asset('images/ra3.png') }}" alt="Website Logo" class="" id="user" width="55px">
                    @endif

                    <div>
                        <p class="ms-2" style="font-size: 13px; color: var(--ra-primary-set);">
                            {{ $appSettings->webname ?? 'Raiyaan Info Tech' }}
                        </p>
                    </div>

                </div>



                <div class="closebtn text-end d-lg-none">
                    <i class="bi bi-x-lg p-1 rounded-2"></i>
                </div>



                <div class="sidebar-menu">
                    <ul class="nav flex-column">

                        @if (auth()->user()->role('Super admin'))


                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dhome') ? 'active' : '' }}"
                                    href="{{ route('dhome') }}" data-tooltip="Dashboard">
                                    <i class="bi bi-speedometer2"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>







                            @if (auth()->user()->can('login'))
                                <li class="nav-item mt-2">

                                    <h6 class="text-uppercase fs-7  gn fw-bold px-3"><span>Management</span>
                                    </h6>

                                </li>
                            @endif




                            @if (auth()->user()->can('admin') || auth()->user()->can('admin->admin view'))
                                <li class="nav-item mt-2">
                                    <a class="nav-link submenu-toggle d-flex justify-content-between align-items-center"
                                        href="javascript:void(0)" data-tooltip="Administartors">
                                        <span1>
                                            <i class="bi bi-fingerprint"></i>
                                            <span>Administartors</span>
                                        </span1>
                                        <i class="bi bi-chevron-down toggle-icon"></i>
                                    </a>
                                    <ul class="submenu list-unstyled ps-4 collapse">
                                        @can('admin->admin view')
                                            <li> <a class="nav-link {{ request()->routeIs('adminlist') ? 'active' : '' }}"
                                                    href="{{ route('adminlist') }}" data-tooltip="Admin add"><i
                                                        class="bi bi-person-plus me-2"></i> Add</a></li>
                                        @endcan
                                    </ul>
                                </li>
                            @endif






                            @if (auth()->user()->can('client') || auth()->user()->can('client->client view'))
                                <li class="nav-item mt-2">
                                    <a class="nav-link submenu-toggle d-flex justify-content-between align-items-center"
                                        href="javascript:void(0)" data-tooltip="client">
                                        <span1>
                                            <i class="bi bi-person-vcard"></i>
                                            <span>Client</span>
                                        </span1>
                                        <i class="bi bi-chevron-down toggle-icon"></i>
                                    </a>
                                    <ul class="submenu list-unstyled ps-4 collapse">
                                        @can('client->client view')
                                            <li><a href="{{ route('clients.index') }}"
                                                    class="nav-link {{ request()->routeIs('clients.index') ? 'active' : '' }}"
                                                    data-tooltip="client add"><i class="bi bi-person-plus me-2"></i> Add</a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endif








                            @if (
                                    auth()->user()->can('hr') ||
                                    auth()->user()->can('hr->employee view') ||
                                    auth()->user()->can('hr->other view') ||
                                    auth()->user()->can('hr->role view') ||
                                    auth()->user()->can('hr->promotion view') ||
                                    auth()->user()->can('hr->termination view') ||
                                    auth()->user()->can('hr->resignation view') ||
                                    auth()->user()->can('hr->handover view') ||
                                    auth()->user()->can('hr->probation view')
                                )
                                <li class="nav-item mt-1">
                                    <a class="nav-link submenu-toggle d-flex justify-content-between align-items-center"
                                        href="javascript:void(0)" data-tooltip="Employment">
                                        <span1>
                                            <i class="bi bi-people"></i>
                                            <span>HR </span>
                                        </span1>
                                        <i class="bi bi-chevron-down toggle-icon"></i>
                                    </a>
                                    <ul class="submenu list-unstyled ps-4 collapse">

                                        @can('hr->employee view')
                                            <li><a href="{{ route('emplist') }}"
                                                    class="nav-link {{ request()->routeIs('emplist') ? 'active' : '' }}"
                                                    data-tooltip="Employees"><i class="bi bi-people-fill me-2"></i>New
                                                    Employees</a>
                                            </li>
                                        @endcan









                                        @can('hr->probation view')
                                            <li>
                                                <a href="{{ route('probationlist') }}"
                                                    class="nav-link {{ request()->routeIs('probationlist') ? 'active' : '' }}"
                                                    data-tooltip="Probation">
                                                    <i class="bi bi-hourglass-split me-2"></i>UnderProbation
                                                </a>
                                            </li>
                                        @endcan

                                        @can('hr->confirmedemp view')
                                            <li>
                                                <a href="{{ route('confirmedemp') }}"
                                                    class="nav-link {{ request()->routeIs('confirmedemp') ? 'active' : '' }}"
                                                    data-tooltip="Probation">
                                                    <i class="bi bi-people-fill me-2"></i>Confirmed Emp
                                                </a>
                                            </li>
                                        @endcan

                                        @can('hr->promotion view')
                                            <li>
                                                <a href="{{ route('prolist') }}"
                                                    class="nav-link {{ request()->routeIs('prolist') ? 'active' : '' }}"
                                                    data-tooltip="Promotion">
                                                    <i class="bi bi-arrow-up-circle me-2"></i>Promotion
                                                </a>
                                            </li>
                                        @endcan

                                        @can('hr->resignation view')
                                            <li>
                                                <a href="{{ route('resignationlist') }}"
                                                    class="nav-link {{ request()->routeIs('resignationlist') ? 'active' : '' }}"
                                                    data-tooltip="Resignation">
                                                    <i class="bi bi-door-open me-2"></i>Resignation
                                                </a>
                                            </li>
                                        @endcan

                                        @can('hr->termination view')
                                            <li>
                                                <a href="{{ route('terminationlist') }}"
                                                    class="nav-link {{ request()->routeIs('terminationlist') ? 'active' : '' }}"
                                                    data-tooltip="Termination">
                                                    <i class="bi bi-person-dash me-2"></i>Termination
                                                </a>
                                            </li>
                                        @endcan




                                        @can('hr->handover view')
                                            <li>
                                                <a href="{{ route('handoverlist') }}"
                                                    class="nav-link {{ request()->routeIs('handoverlist') ? 'active' : '' }}"
                                                    data-tooltip="Handover">
                                                    <i class="bi bi-arrow-left-right me-2"></i>Handover
                                                </a>
                                            </li>
                                        @endcan


                                        @can('hr->role view')
                                            <li class="">
                                                <a class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}"
                                                    href="{{ route('roles.index') }}" title="Roles">
                                                    <i class="bi bi-person-gear"></i>
                                                    <span>Roles</span>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('hr->other view')
                                            <li><a href="{{ route('designation') }}"
                                                    class="nav-link {{ request()->routeIs('designation') || request()->routeIs('department') || request()->routeIs('branch') || request()->routeIs('qualifiaction') ? 'active' : '' }}"
                                                    data-tooltip=" Designation"><i class="bi bi-collection"></i> Other</a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endif



                            @if (
                                    auth()->user()->can('attendance') ||
                                    auth()->user()->can('attendance->shiftplanner view') ||
                                    auth()->user()->can('attendance->attendance view') ||
                                    auth()->user()->can('attendance->addleave view') ||
                                    auth()->user()->can('attendance->assignleave view') ||
                                    auth()->user()->can('attendance->holiday view')
                                )
                                <li class="nav-item mt-1">
                                    <a class="nav-link submenu-toggle d-flex justify-content-between align-items-center"
                                        href="javascript:void(0)" data-tooltip="Attendance">
                                        <span1>
                                            <i class="bi bi-calendar2-week "></i>
                                            <span>Attendance</span>
                                        </span1>
                                        <i class="bi bi-chevron-down toggle-icon"></i>
                                    </a>
                                    <ul class="submenu list-unstyled ps-4 collapse">
                                         @if (auth()->user()->categorie != 2)
                                        @can('attendance->shiftplanner view')
                                            <li><a href="{{ route('shiftindex') }}"
                                                    class="nav-link {{ request()->routeIs('shiftindex') ? 'active' : '' }}"
                                                    data-tooltip=" Shift Planner"><i class="bi bi-calendar2-range me-2"></i>
                                                    Shift Planner</a></li>
                                        @endcan
                                         @endif
                                        @can('attendance->attendance view')
                                            @if (auth()->user()->categorie != 2)
                                                <li><a href="{{ route('attendancelist') }}"
                                                        class="nav-link {{ request()->routeIs('attendancelist') ? 'active' : '' }}"
                                                        data-tooltip="Attendance"><i class="bi bi-calendar2-week me-2"></i>
                                                        Daily Attendance</a></li>
                                            @else
                                                <li><a href="{{ route('empattendancelist') }}"
                                                        class="nav-link {{ request()->routeIs('empattendancelist') ? 'active' : '' }}"
                                                        data-tooltip="Attendance"><i class="bi bi-calendar2-week me-2"></i>
                                                        Mark Attendance</a></li>
                                            @endif
                                        @endcan
                                        @can('attendance->assignleave view')
                                            @if (auth()->user()->categorie != 2)
                                                <li><a href="{{ route('leaveindex') }}"
                                                        class="nav-link {{ request()->routeIs('leaveindex') ? 'active' : '' }}"
                                                        data-tooltip="Leave"><i class="bi bi-door-open me-2"></i>
                                                        Leave</a>
                                                </li>
                                            @else
                                                <li><a href="{{ route('empleaveindex') }}"
                                                        class="nav-link {{ request()->routeIs('empleaveindex') ? 'active' : '' }}"
                                                        data-tooltip="Leave"><i class="bi bi-door-open me-2"></i>
                                                        Apply Leave</a>
                                                </li>
                                            @endif
                                        @endcan
                                        @can('attendance->addleave view')
                                            <li><a href="{{ route('leavetypeindex') }}"
                                                    class="nav-link {{ request()->routeIs('leavetypeindex') ? 'active' : '' }}"
                                                    data-tooltip="Leavetype"><i class="bi bi-calendar-check me-2"></i>

                                                    Add Leave</a>
                                            </li>
                                        @endcan



                                        @can('attendance->holiday view')
                                            @if (auth()->user()->categorie != 2)
                                                <li><a href="{{ route('holidaylist') }}"
                                                        class="nav-link {{ request()->routeIs('holidaylist') ? 'active' : '' }}"
                                                        data-tooltip="Holidays"><i class="bi bi-emoji-sunglasses me-2"></i>
                                                        Holidays</a></li>
                                            @else
                                                <li><a href="{{ route('empholidaylist') }}"
                                                        class="nav-link {{ request()->routeIs('empholidaylist') ? 'active' : '' }}"
                                                        data-tooltip="Holidays"><i class="bi bi-emoji-sunglasses me-2"></i>
                                                        Emp Holidays</a></li>
                                            @endif
                                        @endcan


                                    </ul>
                                </li>
                            @endif



                            @can('inventory->accessories view')
    @if (auth()->user()->categorie == 2)
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('accessories.index') ? 'active' : '' }}"
                href="{{ route('accessories.index') }}" data-tooltip="Accessories">
                <i class="bi bi-box-seam"></i>
                <span>Accessories</span>
            </a>
        </li>
    @endif
@endcan


                            @if (auth()->user()->can('login') || auth()->user()->can('login->email view'))
                                <li class="nav-item mt-1">
                                    <a class="nav-link submenu-toggle d-flex justify-content-between align-items-center"
                                        href="javascript:void(0)" data-tooltip="login">
                                        <span1>
                                            <i class="bi bi-box-arrow-in-right"></i>
                                            <span>Login</span>
                                        </span1>
                                        <i class="bi bi-chevron-down toggle-icon"></i>
                                    </a>
                                    <ul class="submenu list-unstyled ps-4 collapse">
                                        @can('login->email view')
                                            <li><a href="{{ route('companyemail.index') }}"
                                                    class="nav-link {{ request()->routeIs('companyemail.index') ? 'active' : '' }}"
                                                    data-tooltip="Company Email"><i class="bi bi-envelope-at me-2"></i>
                                                    Email</a> </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endif




                            @if (
                                    auth()->user()->can('finance') ||
                                    auth()->user()->can('finance->tax view') ||
                                    auth()->user()->can('finance->salary view') ||
                                    auth()->user()->can('finance->billing view') ||
                                    auth()->user()->can('finance->pf view')
                                )
                                <li class="nav-item mt-1">
                                    <a class="nav-link submenu-toggle d-flex justify-content-between align-items-center"
                                        href="javascript:void(0)" data-tooltip="Finance">
                                        <span1>
                                            <i class="bi bi-cash-stack"></i>
                                            <span>Finance</span>
                                        </span1>
                                        <i class="bi bi-chevron-down toggle-icon"></i>
                                    </a>
                                    <ul class="submenu list-unstyled ps-4 collapse">
                                        @can('finance->tax view')
                                            <li><a href="" class="nav-link" data-tooltip="Tax"><i class="bi bi-receipt me-2"></i>
                                                    Tax</a></li>
                                        @endcan
                                        @can('finance->salary view')
                                            <li><a href="" class="nav-link" data-tooltip="Salary"><i
                                                        class="bi bi-currency-dollar me-2"></i> Salary</a></li>
                                        @endcan
                                        @can('finance->pf view')
                                            <li><a href="" class="nav-link" data-tooltip="PF"><i class="bi bi-piggy-bank me-2"></i>
                                                    PF</a></li>
                                        @endcan
                                        @can('finance->billing view')
                                            <li><a href="" class="nav-link" data-tooltip="Billing"><i
                                                        class="bi bi-file-earmark-text me-2"></i> Billing</a></li>
                                        @endcan

                                    </ul>
                                </li>
                            @endif





                            @if (auth()->user()->categorie != 2)

                                @if (
                                        auth()->user()->can('task') ||
                                        auth()->user()->can('task->project view') ||
                                        auth()->user()->can('task->project edit') ||
                                         auth()->user()->can('task->project create') ||
                                           auth()->user()->can('task->project delete') ||

                                               auth()->user()->can('task->modulo view') ||
                                        auth()->user()->can('task->modulo edit') ||
                                         auth()->user()->can('task->modulo create') ||
                                           auth()->user()->can('task->modulo delete') ||


                                               auth()->user()->can('task->task view') ||
                                        auth()->user()->can('task->task edit') ||
                                         auth()->user()->can('task->task create') ||
                                           auth()->user()->can('task->task delete') ||


                                               auth()->user()->can('task->subtask view') ||
                                        auth()->user()->can('task->subtask edit') ||
                                         auth()->user()->can('task->subtask create') ||
                                           auth()->user()->can('task->subtask delete')

                                    )
                                    <li class="nav-item mt-2">
                                        <a class="nav-link {{ request()->routeIs('kanbandashboard') ? 'active' : '' }}  d-flex justify-content-between align-items-center"
                                            href="{{ route('kanbandashboard') }}" data-tooltip="Kanban Dashboard">
                                            <span1>
                                                <i class="bi bi-kanban"></i>
                                                <span>Kanban Board</span>
                                            </span1>
                                        </a>
                                    </li>

                                @endif
                            @else
                                <li class="nav-item mt-2 ">
                                    <a class="nav-link {{ request()->routeIs('empkanbanboard') ? 'active' : '' }}  d-flex justify-content-between align-items-center"
                                        href="{{ route('empkanbanboard') }}" data-tooltip="empkanbanboard">
                                        <span1>
                                            <i class="bi bi-kanban"></i>
                                            <span>Project</span>
                                        </span1>
                                    </a>
                                </li>
                                <li class="nav-item mt-2 ">
                                    <a class="nav-link {{ request()->routeIs('etaskchat') ? 'active' : '' }}  d-flex justify-content-between align-items-center"
                                        href="{{ route('etaskchat') }}" data-tooltip="etaskchat">
                                        <span1>
                                            <i class="bi bi-chat-left-text"></i>
                                            <span>Task Conversation</span>
                                        </span1>
                                    </a>
                                </li>
                            @endif




@if (
    auth()->user()->can('inventory') ||
    auth()->user()->can('inventory->items view') ||
    auth()->user()->can('inventory->categories view') ||
    auth()->user()->can('inventory->assignments view') ||
    auth()->user()->can('inventory->maintenance view') ||
    auth()->user()->can('inventory->history view') ||
    auth()->user()->can('inventory->reports view')
)

<li class="nav-item mt-1">

    <a class="nav-link submenu-toggle d-flex justify-content-between align-items-center"
       href="javascript:void(0)" data-tooltip="Inventory">

        <span>
            <i class="bi bi-boxes"></i>
            <span>Inventory</span>
        </span>

        <i class="bi bi-chevron-down toggle-icon"></i>
    </a>

    <ul class="submenu list-unstyled ps-4 collapse">

        @can('inventory->items view')
        <li>
            <a href="{{ route('inventory.index') }}" class="nav-link">
                <i class="bi bi-box-seam me-2"></i> Items
            </a>
        </li>
        @endcan

        @can('inventory->categories view')
        <li>
            <a href="{{ route('inventory.categories.index') }}" class="nav-link">
                <i class="bi bi-tags me-2"></i> Categories
            </a>
        </li>
        @endcan

        @can('inventory->assignments view')
        <li>
            <a href="{{ route('inventory.assignments.index') }}" class="nav-link">
                <i class="bi bi-person-check me-2"></i> Assignments
            </a>
        </li>
        @endcan

        @can('inventory->maintenance view')
        <li>
            <a href="{{ route('inventory.maintenance.index') }}" class="nav-link">
                <i class="bi bi-tools me-2"></i> Maintenance
            </a>
        </li>
        @endcan

        @can('inventory->history view')
        <li>
            <a href="{{ route('inventory.history.index') }}" class="nav-link">
                <i class="bi bi-clock-history me-2"></i> History
            </a>
        </li>
        @endcan

        {{-- NEW: REPORTS MENU ITEM --}}
        @can('inventory->reports view')
        <li>
            <a href="{{ route('reports.index') }}" class="nav-link">
                <i class="bi bi-file-text me-2"></i> Reports
            </a>
        </li>
        @endcan

    </ul>

</li>
@endif



                            @if (
                                    auth()->user()->can('email') ||
                                    auth()->user()->can('email->template view') ||
                                    auth()->user()->can('email->configure view')
                                )
                                <li class="nav-item mt-1">
                                    <a class="nav-link submenu-toggle d-flex justify-content-between align-items-center"
                                        href="javascript:void(0)" data-tooltip="Email">
                                        <span1>
                                            <i class="bi bi-envelope"></i>
                                            <span>Email</span>
                                        </span1>
                                        <i class="bi bi-chevron-down toggle-icon"></i>
                                    </a>
                                    <ul class="submenu list-unstyled ps-4 collapse">
                                        @can('email->template view')
                                            <li><a href="" class="nav-link" data-tooltip="Template"><i
                                                        class="bi bi-file-earmark-text me-2"></i> Template</a></li>
                                        @endcan
                                        @can('email->configure view')
                                            <li><a href="" class="nav-link" data-tooltip="Configure"><i class="bi bi-gear me-2"></i>
                                                    Configure</a></li>
                                        @endcan
                                    </ul>
                                </li>
                            @endif




                            @if (
                                    auth()->user()->can('site') ||
                                    auth()->user()->can('site->setting view') ||
                                    auth()->user()->can('site->permission view') ||
                                    auth()->user()->can('site->activitylogs view')
                                )
                                <li class="nav-item mt-1">
                                    <a class="nav-link submenu-toggle d-flex justify-content-between align-items-center"
                                        href="javascript:void(0)" data-tooltip="site">
                                        <span1>
                                            <i class="bi bi-globe"></i>
                                            <span>Site</span>
                                        </span1>
                                        <i class="bi bi-chevron-down toggle-icon"></i>
                                    </a>
                                    <ul class="submenu list-unstyled ps-4 collapse">
                                        {{-- @if (!auth()->user()->hasRole('Super admin')) --}}
                                        @can('site->setting view')
                                            {{-- Settings (Visible for users except Super Admin) --}}
                                            <li>
                                                <a class="nav-link {{ request()->routeIs('cookies') || request()->routeIs('theme') ? 'active' : '' }}"
                                                    href="{{ route('theme') }}" data-tooltip="Setting">
                                                    <i class="bi bi-gear me-2"></i> Setting
                                                </a>
                                            </li>
                                        @endcan
                                        {{-- @endif --}}


                                        {{-- Activity Logs - Only for Super Admin --}}
                                        @if (auth()->user()->hasRole('Super admin'))
                                            {{-- <li>
                                                <a class="nav-link {{ request()->routeIs('setting') || request()->routeIs('applogo') || request()->routeIs('favicon') || request()->routeIs('sitecontrol') || request()->routeIs('theme') ? 'active' : '' }}"
                                                    href="{{ route('setting') }}" data-tooltip="Setting">
                                                    <i class="bi bi-gear me-2"></i> Setting
                                                </a>
                                            </li> --}}


                                            <li>
                                                <a href="{{ route('activity.logs') }}"
                                                    class="nav-link {{ request()->routeIs('activity.logs') ? 'active' : '' }}"
                                                    data-tooltip="Activity Logs">
                                                    <i class="bi bi-clock-history me-2"></i> Activity Logs
                                                </a>
                                            </li>

                                            {{-- Permissions - Only for Super Admin --}}

                                            <li>
                                                <a class="nav-link {{ request()->routeIs('permissions.index') ? 'active' : '' }}"
                                                    href="{{ route('permissions.index') }}" title="Permissions">
                                                    <i class="bi bi-shield-lock"></i>
                                                    <span>Permissions</span>
                                                </a>
                                            </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif






                        @endif


                    </ul>
                </div>
            </div>


            <div class="main-content ">

                <nav class="navbar  navbar-expand-lg  sticky-top " style=" padding: 11.5px 20px;">
                    <div class="container-fluid ">
                        <button class="btn me-3 d-none d-lg-block" id="sidebarCollapseToggle" type="button"
                            title="Toggle Sidebar">
                            <i class="bi bi-shuffle shuffle-icon   fs-5"></i>
                        </button>

                        <button class="btn sidebar-toggle d-lg-none me-3" type="button">
                            <i class="bi bi-list"></i>
                        </button>


                        <div class="d-flex align-items-center bulkprofile">
                            <!-- Notification icon with badge -->
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
        $('#bottomNotifMessage').text(notificationData.plain_message);


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
                            </div>
                        </div>
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
        </>



        <!-- Offcanvas -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header ">
                <h5 class="  fw-bold mt-3 ms-4 text-primary " id="offcanvasRightLabel"><i
                        class="bi bi-palette me-2 "></i>
                    Theme Setting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <style>
                :root {
                    --p-color: {
                            {
                            $settings->p_color ?? '#4477b6'
                        }
                    }

                    ;

                    --nh-color: {
                            {
                            $settings->nh_color ?? '#ffffff'
                        }
                    }

                    ;

                    --h-color: {
                            {
                            $settings->h_color ?? '#ffffff'
                        }
                    }

                    ;

                    --s-color: {
                            {
                            $settings->s_color ?? '#ffffff'
                        }
                    }

                    ;
                }

                /* Modern Glass Morphism Effect */
                .glass-morphism-effect {
                    background: rgba(255, 255, 255, 0.85);
                    backdrop-filter: blur(12px);
                    -webkit-backdrop-filter: blur(12px);
                    border: 1px solid rgba(255, 255, 255, 0.18);
                }

                /* Enhanced Color Picker Cards */
                .color-picker-card {
                    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
                    background-color: #fff;
                    position: relative;
                    overflow: hidden;
                }

                .color-picker-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 4px;
                    background: linear-gradient(90deg, var(--p-color), var(--s-color));
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }

                .color-picker-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
                }

                .color-picker-card:hover::before {
                    opacity: 1;
                }

                /* Modern Color Picker */
                .color-picker-wrapper {
                    position: relative;
                    width: 50px;
                    height: 50px;
                }

                .modern-color-picker {
                    width: 100%;
                    height: 100%;
                    border-radius: 12px;
                    padding: 2px;
                    border: 2px solid #e9ecef;
                    cursor: pointer;
                    opacity: 0;
                    position: relative;
                    z-index: 2;
                }

                .color-picker-preview {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    border-radius: 10px;
                    border: 2px solid #e9ecef;
                    z-index: 1;
                    transition: all 0.3s ease;
                    background-color: var(--picker-color, #4477b6);
                }

                .modern-color-picker:hover+.color-picker-preview {
                    transform: scale(1.05);
                    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
                }

                /* Enhanced Palette Cards */
                .palette-card {
                    background: white;
                    border-radius: 16px;
                    padding: 10px;
                    border: 1px solid rgba(0, 0, 0, 0.05);
                    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
                    display: flex;
                    flex-direction: column;
                    height: 100%;
                    position: relative;
                    overflow: hidden;
                }

                .palette-card::after {
                    content: '';
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    width: 100%;
                    height: 4px;
                    background: linear-gradient(90deg, var(--p-color), var(--s-color));
                    transform: scaleX(0);
                    transform-origin: left;
                    transition: transform 0.3s ease;
                }

                .palette-card:hover {
                    transform: translateY(-8px) scale(1.02);
                    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
                }

                .palette-card:hover::after {
                    transform: scaleX(1);
                }

                .palette-colors {
                    display: flex;
                    height: 55px;
                    width: 270px;
                    border-radius: 12px;
                    overflow: hidden;
                    margin-bottom: 15px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                }

                .palette-colors span {
                    flex: 1;
                    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
                    position: relative;
                }

                .palette-colors span:hover {
                    transform: scale(1.1);
                    z-index: 2;
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                }

                .palette-colors span::after {
                    content: ;
                    position: absolute;
                    bottom: -25px;
                    left: 0;
                    width: 100%;
                    text-align: center;
                    font-size: 0.7rem;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }

                .palette-colors span:hover::after {
                    opacity: 1;
                }

                .palette-info h6 {
                    margin-bottom: 4px;
                    font-weight: 600;
                    font-size: 1.1rem;
                }

                .palette-info small {
                    color: #6c757d;
                    font-size: 0.85rem;
                }

                .palette-card button {
                    margin-top: auto;
                    align-self: flex-start;
                    position: relative;
                    overflow: hidden;
                }

                .palette-card button::after {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 5px;
                    height: 5px;
                    background: rgba(255, 255, 255, 0.5);
                    opacity: 0;
                    border-radius: 100%;
                    transform: scale(1, 1) translate(-50%);
                    transform-origin: 50% 50%;
                }

                .palette-card button:focus:not(:active)::after {
                    animation: ripple 0.6s ease-out;
                }

                /* Button Loaders */
                .btn-loader {
                    display: inline-block;
                    vertical-align: middle;
                }

                /* Hex Code Display */
                .hex-code-container {
                    display: flex;
                    align-items: center;
                }

                .hex-code {
                    font-family: 'Fira Code', monospace;
                    background: #f8f9fa;
                    padding: 4px 8px;
                    border-radius: 6px;
                    border: 1px solid #dee2e6;
                    min-width: 80px;
                    text-align: center;
                    display: inline-block;
                    transition: all 0.2s ease;
                }

                .btn-copy-hex {
                    background: none;
                    border: none;
                    padding: 0;
                    color: #6c757d;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    width: 28px;
                    height: 28px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                }

                .btn-copy-hex:hover {
                    color: #0d6efd;
                    background: rgba(13, 110, 253, 0.1);
                }

                .btn-copy-hex:active {
                    transform: scale(0.95);
                }

                /* Animations */
                @keyframes ripple {
                    0% {
                        transform: scale(0, 0);
                        opacity: 0.5;
                    }

                    100% {
                        transform: scale(25, 25);
                        opacity: 0;
                    }
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

                /* Staggered animations for palette cards */
                .palette-carousel .col-lg-4:nth-child(1) .palette-card {
                    animation: fadeIn 0.5s ease-out 0.1s both;
                }

                .palette-carousel .col-lg-4:nth-child(2) .palette-card {
                    animation: fadeIn 0.5s ease-out 0.2s both;
                }

                .palette-carousel .col-lg-4:nth-child(3) .palette-card {
                    animation: fadeIn 0.5s ease-out 0.3s both;
                }




                /* Submit button animation */
                .submit-btn {
                    position: relative;
                    overflow: hidden;
                }

                .submit-btn::after {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
                    transform: translateX(-100%);
                    transition: transform 0.6s ease;
                }

                .submit-btn:hover::after {
                    transform: translateX(100%);
                }
            </style>





        </div>
  <!-- Offcanvas Right -->

<script>
function hideBottomNotification() {
    $('#bottomNotificationToast').fadeOut(300, function() {
        $(this).removeClass('d-block').addClass('d-none');
    });
}
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    const focus = params.get('focus');

    if (!focus) return;

    if (focus.startsWith('task-')) {
        const taskId = focus.replace('task-', '');
        const taskEl = document.querySelector(`[data-task-id="${taskId}"]`);

        if (taskEl) {
            taskEl.click();
            document.getElementById(`task-${taskId}`)?.classList.add('active');
        }
    }

    if (focus.startsWith('subtask-')) {
        const subtaskId = focus.replace('subtask-', '');
        const subtaskEl = document.querySelector(`[data-subtask-id="${subtaskId}"]`);

        if (subtaskEl) {
            const parentTask = subtaskEl.closest('.subtask-list');
            if (parentTask && parentTask.style.display === 'none') {
                parentTask.style.display = 'block';
                const taskId = parentTask.id.replace('subtasks-', '');
                document.getElementById(`icon-${taskId}`)?.classList.replace('bi-chevron-down', 'bi-chevron-up');
            }

            subtaskEl.click();
            document.getElementById(`subtask-${subtaskId}`)?.classList.add('active');
        }
    }
});

</script>
    </body>

    </html>


</div>
