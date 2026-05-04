<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RTS CRM</title>
    <!-- Bootstrap CSS & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- ADD DATATABLES CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        /* 1. Ensure the body handles the main scrollbar */
        body { 
            background-color: #f1f5f9; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 0;
            overflow-x: hidden;
        }   

        /* 2. Wrapper spans the full width and allows height to grow */
        .wrapper { 
            display: flex; 
            width: 100%; 
            min-height: 100vh; 
        }

        /* 3. FIX THE SIDEBAR: Stays fixed on the left (Like Image 2) */
        /* Note: Adjust 'width' if your sidebar is wider or narrower */
        .sidebar-container {
            width: 260px;
            min-width: 260px;
            background-color: #1e293b;
            color: white;
            min-height: 100vh;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        /* 4. MAIN CONTENT: Fills the remaining space */
        .main-content { 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
            min-width: 0; /* Prevents flex items from overflowing */
        }

        /* 5. Header stays at the top */
        header {
            position: sticky;
            top: 0;
            z-index: 999;
            width: 100%;
        }

        /* 6. CONTENT BODY: No inner scrollbar, grows with form */
        .content-body { 
            padding: 30px; 
            flex-grow: 1;
        }

        .sidebar a:hover { background-color: #1e293b !important; color: #ffffff !important; }
        
        /* DataTables Cleaning */
        div.dataTables_wrapper div.dataTables_length select { width: auto; display: inline-block; }
        div.dataTables_wrapper div.dataTables_filter input { width: auto; display: inline-block; margin-left: 0.5em; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar with fixed container -->
        <div class="sidebar-container">
            @include('layouts.sidebar')
        </div>

        <div class="main-content">
            @include('layouts.header')

            <main class="content-body">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- ADD DATATABLES JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    {{-- Script stack for page-specific JS --}}
    @stack('scripts')

    {{-- Global Initializers --}}
    <script>
        $(document).ready(function() {
            // Check if DataTable should be initialized
            if ($('.dynamic-table').length > 0) {
                $('.dynamic-table').DataTable({
                    retrieve: true,
                    "language": {
                        "search": "Quick Search:"
                    }
                });
            }

             // Modal fix
            $('.modal').on('shown.bs.modal', function () {
                $(this).find('form').trigger('reset');
            });
        });
    </script>
</body>
</html>