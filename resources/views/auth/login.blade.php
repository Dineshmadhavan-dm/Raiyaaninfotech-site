<x-site>
    <div class="modal modal-sheet position-static d-block p-4 py-md-5 mt-5" tabindex="-1" role="dialog" id="modalSignin">
        <div class="modal-dialog modal-compact">
            <x-message />

            <div class="modal-content rounded-4 shadow border-0 overflow-hidden">
                <div class="modal-header p-5 pb-4 border-bottom-0 position-relative bg-primary-subtle">
                    <div class="position-relative d-flex align-items-center">
                        <img src="{{ asset('images/ra3.png') }}" alt="Raiyaan Info Technologies" class="me-3"
                            width="52">
                        <div>
                            <h1 class="fw-bold mb-0 fs-4 text-primary">Raiyaan </h1>
                            <p class="text-primary-emphasis mb-0">Info Tech</p>
                        </div>
                    </div>
                </div>

                <div class="modal-body p-5 pt-4">
                    <form method="post" action="{{ route('loginpost') }}" id="loginForm">
                        @csrf
                        <!-- Email Field -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control rounded-3" id="email" name="email"
                                value="{{ old('email', Cookie::get('email_me') ?? '') }}" placeholder="Enter your email"
                                oninput="validateCredentials()">
                            <label for="email">Email address</label>
                            <div id="emailError" class="text-danger small mt-1"></div>
                        </div>
                        @error('email')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror

                        <!-- Password Field with Toggle -->
                        <div class="form-floating mb-3 position-relative">
                            <input type="password" class="form-control rounded-3 pe-5" name="password"
                                placeholder="Password" id="password" value="{{ Cookie::get('pwd') ?? '' }}"
                                oninput="validateCredentials()">
                            <label for="password">Password</label>
                            <button type="button"
                                class="btn btn-link position-absolute top-50 end-0 translate-middle-y me-2 p-0 text-muted password-toggle"
                                aria-label="Show password">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                            <div id="passwordError" class="text-danger small mt-1"></div>
                        </div>
                        @error('password')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror

                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me"
                                    value="1" {{ Cookie::has('email_me') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted small" for="remember_me">
                                    Remember me
                                </label>
                            </div>
                            <a href="{{ route('fpwd') }}" class="text-decoration-none small text-primary">Forgot
                                password?</a>
                        </div>

                        <!-- Submit Button -->
                        <button class="w-100 mb-3 btn rounded-3 btn-primary shadow-sm" type="submit" id="submitBtn"
                            disabled>
                            <span class="position-relative">
                                <span class="me-2">Login</span>
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .corner {
            position: absolute;
            bottom: 20px;
            right: 40px;
            padding: 30px;
            background-color: #00000007;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            border: 1px solid var(--ra-primary-set);
        }

        .modal-compact {
            max-width: 380px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            document.querySelectorAll('.password-toggle').forEach(function(toggle) {
                toggle.addEventListener('click', function() {
                    const passwordInput = this.parentElement.querySelector('input');
                    const icon = this.querySelector('i');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    }
                });
            });

            // Initialize validation on page load if fields have values
            if (document.getElementById('email').value || document.getElementById('password').value) {
                validateCredentials();
            }
        });

        function validateCredentials() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');
            const submitBtn = document.getElementById('submitBtn');

            const email = emailInput.value.trim();
            const password = passwordInput.value.trim();

            emailError.textContent = '';
            passwordError.textContent = '';
            submitBtn.disabled = false;

            if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                emailError.textContent = 'Please enter a valid email address';
                return;
            }

            if (password && password.length < 8) {
                passwordError.textContent = 'Password must be at least 8 characters';
                return;
            }

            if (!email || !password) return;


        }
    </script>
</x-site>
