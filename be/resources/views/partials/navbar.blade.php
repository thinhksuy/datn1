<nav>
	<i class='bx bx-menu' ></i>
	<a href="#" class="nav-link">Categories</a>
	<form action="{{ route('admin.products.index') }}" method="GET">
    <div class="form-input">
        <input type="search" name="keyword" placeholder="Search..." value="{{ request('keyword') }}">
        <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
    </div>
</form>

	<input type="checkbox" id="switch-mode" hidden>
	<label for="switch-mode" class="switch-mode"></label>
	<a href="#" class="notification">
		<i class='bx bxs-bell' ></i>
		<span class="num">0</span>
	</a>
	<ul class="side-menu">
		<li>
			<a href="#">
				<i class='bx bxs-cog'></i>
			</a>
		</li>
	</ul>
	<a href="#" class="profile">
		<img src="{{ asset('WebAdmin/img/people.png') }}">
	</a>
	<ul class="side-menu">
		<li>
			<a href="#" class="logout">
				<i class='bx bx-log-out'></i>
				<span class="text">Logout</span>
			</a>
		</li>
	</ul>
</nav>
