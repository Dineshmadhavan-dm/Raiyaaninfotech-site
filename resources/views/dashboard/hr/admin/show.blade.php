<x-layout>


    @section('title', 'Admin Profile View')

    <div class="container-fluid py-4 px-4">
        <!-- Header & Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dhome') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('adminlist') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>

        {{-- content area --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center mb-4 mb-md-0">
                        <img src="{{ $user->image ? asset('admin_images/' . $user->image) : asset('images/admin_default.jpg') }}"
                            alt="{{ $user->name }}" class="img-fluid rounded-circle" width="150px">
                    </div>
                    <div class="col-md-9">
                        <div class="profile-info">
                            <h3 class="mb-3 text-primary">{{ $user->name }}</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <h6 class="text-muted mb-1">Email Address</h6>
                                        <p class="mb-0">
                                            <i class="bi bi-envelope-fill me-2 text-secondary"></i>
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <h6 class="text-muted mb-1">Account Status</h6>
                                        <p class="mb-0">
                                            <i class="bi bi-shield-check me-2 text-success"></i>
                                            Active
                                        </p>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="mt-4">
                                <a href="#" class="btn btn-outline-primary me-2">
                                    <i class="bi bi-key me-1"></i>Change Password
                                </a>

                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <style>
        .profile-info h3 {
            font-weight: 600;
        }

        .info-item {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            background: #f1f8ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .list-group-item {
            padding: 0.75rem 0;
        }

        a {
            text-decoration: none;
        }
    </style>
</x-layout>
