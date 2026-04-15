<div>


    <style>
        /* Animation Effects */
        .animate-float-in {
            animation: floatIn 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
            opacity: 0;
            transform: translateY(10px);
        }

        @keyframes floatIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hover Effects */
        .hover-grow {
            transition: all 0.2s ease;
        }

        .hover-grow:hover {
            transform: scale(1.15);
        }

        /* Modern Table Styling */
        .table-hover tbody tr {
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .bg-light-primary {
            background-color: rgba(59, 130, 246, 0.1);
        }

        .bg-primary-soft {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--ra-primary-set);
            font-size: .8em;
        }

        .btn-icon {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-soft-primary {
            background-color: rgba(59, 130, 246, 0.1);

            border: none;
        }

        .btn-soft-info {
            background-color: rgba(6, 182, 212, 0.1);
            color: #06b6d4;
            border: none;
        }

        .btn-soft-warning {
            background-color: rgba(234, 179, 8, 0.1);
            color: #eab308;
            border: none;
        }

        .btn-soft-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: none;
        }

        .rounded-circle {
            border-radius: 50% !important;
        }
    </style>






</div>
