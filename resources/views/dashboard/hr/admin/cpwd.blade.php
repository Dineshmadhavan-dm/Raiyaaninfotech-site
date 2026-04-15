<x-layout>

    @section('title', 'Change Password')

    <div class="container-fluid p-4">
        <!-- Header & Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 mt-2">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dhome') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Change Password</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('adminlist') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>

        <x-message />
        {{-- content area --}}
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form id="passwordForm" action="{{ route('cpwdpost') }}" method="post">
                            @csrf

                            <div class="form-floating mb-4">
                                <input type="password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    id="currentPassword" name="current_password" placeholder="Current Password"
                                    value="{{ old('current_password') }}">
                                <label for="currentPassword">Current Password</label>
                                @error('current_password')
                                    <div class="invalid-feedback fade-out">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="password-toggle">
                                    <i class="bi bi-eye-slash toggle-password" data-target="currentPassword"></i>
                                </div>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="newPassword" name="password" placeholder="New Password"
                                    value="{{ old('password') }}">
                                <label for="newPassword">New Password</label>
                                @error('password')
                                    <div class="invalid-feedback fade-out">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="password-toggle">
                                    <i class="bi bi-eye-slash toggle-password" data-target="newPassword"></i>
                                </div>
                                <div class="password-strength mt-2">
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar" role="progressbar"></div>
                                    </div>
                                    <small class="text-muted strength-text"></small>
                                </div>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    id="confirmPassword" name="password_confirmation" placeholder="Confirm Password"
                                    value="{{ old('password_confirmation') }}">
                                <label for="confirmPassword">Confirm Password</label>
                                @error('password_confirmation')
                                    <div class="invalid-feedback fade-out">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="password-toggle">
                                    <i class="bi bi-eye-slash toggle-password" data-target="confirmPassword"></i>
                                </div>
                                <div id="passwordMatch" class="mt-2 small"></div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="reset" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-key me-2"></i>Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 12px;
        }

        .form-floating {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 5;
            color: #6c757d;
        }

        .password-toggle:hover {
            color: #0d6efd;
        }

        .password-strength {
            display: none;
        }

        .progress {
            background-color: #e9ecef;
        }

        .progress-bar {
            transition: width 0.3s ease;
        }

        #passwordMatch {
            height: 18px;
        }

        .match {
            color: #198754;
        }

        .mismatch {
            color: #dc3545;
        }

        .fade-out {
            animation: fadeOut 3s ease-in-out forwards;
            opacity: 1;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle visibility
            document.querySelectorAll('.toggle-password').forEach(function(toggle) {
                toggle.addEventListener('click', function() {
                    const target = document.getElementById(this.getAttribute('data-target'));
                    const icon = this;

                    if (target.type === 'password') {
                        target.type = 'text';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    } else {
                        target.type = 'password';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    }
                });
            });

            // Password strength indicator
            const newPassword = document.getElementById('newPassword');
            const strengthBar = document.querySelector('.progress-bar');
            const strengthText = document.querySelector('.strength-text');
            const strengthContainer = document.querySelector('.password-strength');

            if (newPassword) {
                newPassword.addEventListener('input', function() {
                    const password = this.value;
                    strengthContainer.style.display = 'block';

                    // Strength calculation
                    let strength = 0;
                    if (password.length > 0) strength += 1;
                    if (password.length >= 8) strength += 1;
                    if (/[A-Z]/.test(password)) strength += 1;
                    if (/[0-9]/.test(password)) strength += 1;
                    if (/[^A-Za-z0-9]/.test(password)) strength += 1;

                    // Update UI
                    const width = (strength / 5) * 100;
                    strengthBar.style.width = width + '%';

                    if (strength <= 2) {
                        strengthBar.className = 'progress-bar bg-danger';
                        strengthText.textContent = 'Weak';
                    } else if (strength <= 4) {
                        strengthBar.className = 'progress-bar bg-warning';
                        strengthText.textContent = 'Moderate';
                    } else {
                        strengthBar.className = 'progress-bar bg-success';
                        strengthText.textContent = 'Strong';
                    }
                });
            }

            // Password confirmation check
            const confirmPassword = document.getElementById('confirmPassword');
            const passwordMatch = document.getElementById('passwordMatch');

            if (confirmPassword && newPassword) {
                confirmPassword.addEventListener('input', function() {
                    if (this.value && newPassword.value) {
                        if (this.value === newPassword.value) {
                            passwordMatch.innerHTML =
                                '<i class="bi bi-check-circle-fill match me-1"></i>Passwords match';
                        } else {
                            passwordMatch.innerHTML =
                                '<i class="bi bi-exclamation-circle-fill mismatch me-1"></i>Passwords do not match';
                        }
                    } else {
                        passwordMatch.textContent = '';
                    }
                });
            }

            // Function to fade out error elements
            function fadeOutError(inputElement, errorElement) {
                // Fade out error message
                errorElement.style.opacity = '0';
                errorElement.style.height = '0';
                errorElement.style.padding = '0';
                errorElement.style.margin = '0';
                errorElement.style.transition = 'all 0.5s ease';

                // Remove error border from input
                inputElement.classList.remove('is-invalid');
                inputElement.style.transition = 'border-color 0.5s ease';

                // Remove elements after transition
                setTimeout(function() {
                    errorElement.remove();
                }, 500);
            }

            // Auto-fade error messages and borders after 3 seconds
            setTimeout(function() {
                document.querySelectorAll('.fade-out').forEach(function(errorElement) {
                    const inputElement = errorElement.closest('.form-floating')?.querySelector(
                        'input');
                    if (inputElement) {
                        fadeOutError(inputElement, errorElement);
                    }
                });
            }, 3000);

            // Form validation

        });
    </script>
</x-layout>
