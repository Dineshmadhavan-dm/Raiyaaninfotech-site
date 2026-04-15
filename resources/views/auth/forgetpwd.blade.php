<x-site>
    <div class="min-vh-100 d-flex align-items-center bg-light">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <!-- Modern Card Design -->
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <!-- Gradient Header -->
                        <div class="card-header  py-4">
                            <div class="text-center">
                                <img src="{{ asset('images/ra3.png') }}" alt="Logo" width="60" class="mb-3">
                                <h2 class=" mb-1">Reset Your Password</h2>
                                <p class=" mb-0">Enter your email to receive a reset link</p>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-5">
                            <form method="POST" action="">
                                @csrf

                                <!-- Email Input with Floating Label -->
                                <div class="form-floating mb-4">
                                    <input type="email" id="email" name="email"
                                        class="form-control  rounded-3 @error('email') is-invalid @enderror"
                                        placeholder="name@example.com" required autofocus>
                                    <label for="email">Email Address</label>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary btn-sm  w-100 rounded-3 py-3 shadow-sm">
                                    <span class="d-flex align-items-center justify-content-center">
                                        <span class="me-2">Send Reset Link</span>
                                        <i class="bi bi-send-fill"></i>
                                    </span>
                                </button>

                                <!-- Back to Login Link -->
                                <div class="text-center mt-4">
                                    <a href="{{ route('loginget') }}" class="text-decoration-none text-muted">
                                        <i class="bi bi-arrow-left me-2"></i>Return to Login
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>

    </style>
</x-site>
