<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'ProCost Manager') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8fafc;
      color: #334155;
      margin: 0;
      padding: 0;
    }

    .navbar {
      background-color: #ffffff;
      border-bottom: 1px solid #e2e8f0;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar-brand {
      font-size: 1.25rem;
      font-weight: 700;
      color: #0f172a;
      text-decoration: none;
    }

    .main-content {
      padding: 2rem;
      max-width: 1200px;
      margin: 0 auto;
    }

    .card {
      background: white;
      border-radius: 0.5rem;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
      overflow: hidden;
      margin-bottom: 1rem;
    }

    .card-header {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid #f1f5f9;
      font-weight: 600;
      background-color: #f8fafc;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .card-title {
      margin-bottom: 0.5rem;
      font-weight: 600;
    }

    .card-body {
      padding: 1.5rem;
    }

    .btn {
      display: inline-block;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      font-weight: 500;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-primary {
      background-color: #3b82f6;
      color: white;
      border: none;
    }

    .btn-primary:hover {
      background-color: #2563eb;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
    }

    .table th,
    .table td {
      text-align: left;
      padding: 0.75rem 1rem;
      border-bottom: 1px solid #e2e8f0;
    }

    .table th {
      font-weight: 600;
      color: #64748b;
      background-color: #f8fafc;
    }
  </style>
</head>

<body>
  <div id="app">
    <nav class="navbar">
      <a class="navbar-brand" href="{{ url('/') }}">
        ProCost Manager
      </a>
      <div class="navbar-nav">
        <!-- Navigation links can go here -->
        <a href="{{ route('dashboard') }}"
          style="color: #64748b; text-decoration: none; margin-left: 1rem;">ダッシュボード</a>
        <a href="{{ route('production-plans.index') }}"
          style="color: #64748b; text-decoration: none; margin-left: 1rem;">生産計画</a>
        <a href="{{ route('products.index') }}"
          style="color: #64748b; text-decoration: none; margin-left: 1rem;">製品管理</a>
        <a href="{{ route('suppliers.index') }}"
          style="color: #64748b; text-decoration: none; margin-left: 1rem;">取引先</a>
        <a href="{{ route('purchase-orders.index') }}"
          style="color: #64748b; text-decoration: none; margin-left: 1rem;">発注管理</a>
      </div>
    </nav>

    <main class="main-content">
      @yield('content')
    </main>
  </div>
</body>

</html>