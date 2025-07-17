<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Vicnex</title>

	<!-- Boxicons -->
	<link href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" rel="stylesheet">

	<!-- My CSS -->
	<link rel="stylesheet" href="{{ asset('WebAdmin/style.css') }}">
	<link rel="stylesheet" href="{{ asset('css/custom-table.css') }}">
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
	<!-- SIDEBAR -->
	@include('partials.sidebar')

	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		@include('partials.navbar')

		<!-- MAIN -->
		<main>
			@yield('content')
		</main>
	</section>

	<!-- JS -->
	<script src="{{ asset('WebAdmin/script.js') }}"></script>
	@yield('scripts')
</body>
</html>
