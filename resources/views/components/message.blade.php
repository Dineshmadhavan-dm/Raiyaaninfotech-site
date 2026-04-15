<div>
    <!-- Toastify CSS (you can also install this via npm if preferred) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">




    <!-- Toastify JS -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    @if (session('successdhome'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Toastify({
                    node: true,
                    escapeMarkup: false,
                    text: `

                                            <div style="font-weight: 600;">{{ session('successdhome') }}</div>
                                            <div style="font-size: 12px; opacity: 0.9; width:210px;">Logged in as: {{ session('user_email') ?? '' }}</div>

                                    `,
                    duration: 2000,
                    close: false,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#10B981",
                    offset: {
                        y: 65

                    },
                    style: {
                        boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                        borderRadius: "8px",
                        fontFamily: "'Inter', sans-serif",
                        fontSize: "14px",
                        // width: "500px"
                    },
                    stopOnFocus: true,
                }).showToast();
            });
        </script>
    @endif
    @if (session('successempdhome'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Toastify({
                    node: true,
                    escapeMarkup: false,
                    text: `

                                            <div style="font-weight: 600;">{{ session('successempdhome') }}</div>
                                            <div style="font-size: 12px; opacity: 0.9; width:210px;">Logged in as: {{ session('user_email') ?? '' }}</div>

                                    `,
                    duration: 2000,
                    close: false,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#19b7bf",
                    offset: {
                        y: 65

                    },
                    style: {
                        boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                        borderRadius: "8px",
                        fontFamily: "'Inter', sans-serif",
                        fontSize: "14px",
                        // width: "500px"
                    },
                    stopOnFocus: true,
                }).showToast();
            });
        </script>
    @endif
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Toastify({
                    text: "{{ session('success') }}",
                    duration: 4000,
                    close: false,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#10B981", // Modern green
                    offset: {
                        y: 65

                    },
                    style: {
                        boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                        borderRadius: "8px",
                        fontFamily: "'Inter', sans-serif",
                        fontSize: "14px",
                        width: "250px"
                    },
                    stopOnFocus: true,
                }).showToast();
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Toastify({
                    text: "{{ session('error') }}",
                    duration: 4000,
                    close: false,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#EF4444", // Modern red
                    // offset: {
                    //     y: 65

                    // },
                    style: {
                        boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                        borderRadius: "8px",
                        fontFamily: "'Inter', sans-serif",
                        fontSize: "14px",
                        width: "250px"
                    },
                    stopOnFocus: true,
                }).showToast();
            });
        </script>
    @endif

    @if (session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Toastify({
                    text: "{{ session('warning') }}",
                    duration: 4000,
                    close: false,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#F59E0B", // Modern amber

                    style: {
                        boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                        borderRadius: "8px",
                        fontFamily: "'Inter', sans-serif",
                        fontSize: "14px",
                        width: "250px"
                    },
                    stopOnFocus: true,
                }).showToast();
            });
        </script>
    @endif

    @if (session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Toastify({
                    text: "{{ session('info') }}",
                    duration: 4000,
                    close: false,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#3B82F6", // Modern blue
                    // avatar: "{{ asset('icons/info.svg') }}",
                    style: {
                        boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                        borderRadius: "8px",
                        fontFamily: "'Inter', sans-serif",
                        fontSize: "14px",
                        width: "250px"
                    },
                    stopOnFocus: true,
                }).showToast();
            });
        </script>
    @endif





    {{-- ---------------sweetalert --}}
    @if (session('successalert'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('successalert') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    position: 'center',
                    background: '#F0FDF4',
                    color: '#166534',
                    customClass: {
                        popup: 'swal2-border-radius'
                    }
                });
            });
        </script>
    @endif
    @if (session('erroralert'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                @if (session('erroralert') || $errors->any())
                    Swal.fire({
                        icon: 'warning', // Changed from 'error' to 'warning'
                        title: 'Access Denied', // Updated title
                        text: "{{ session('erroralert') ?? $errors->first() }}",
                        showConfirmButton: true, // Changed from false to true (user must click)
                        confirmButtonColor: '#f59e0b', // Amber color for warning
                        timer: 5000, // Longer timer (5 seconds)
                        timerProgressBar: true,
                        position: 'center',
                        background: '#fffbeb', // Light amber background
                        color: '#92400e', // Dark amber text
                        customClass: {
                            popup: 'swal2-border-radius'
                        }
                    });
                @endif
                                });
        </script>
    @endif


</div>
