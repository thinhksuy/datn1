<section id="sidebar">
	<a href="#" class="brand">
		<i class='bx'>
			<img src="https://img.icons8.com/?size=100&id=UUr7TPbn4dvp&format=png&color=000000" alt="">
		</i>
		<span class="text">Vicnex</span>
	</a>

	<ul class="side-menu top">
        <ul>
            <li class="has-submenu {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
        </ul>


		<li class="has-submenu  {{ request()->is('admin/categories*') ? 'active' : '' }}">
			<a href="#">
				<i class='bx bxs-shopping-bag-alt'></i>
				<span class="text">Danh mục sản phẩm</span>
			</a>
			<ul class="submenu">
				<li>
    <a href="{{ route('admin.categories.index') }}"  class="{{ request()->routeIs('admin.categories.index') ? 'active1' : '' }}">
        <i class='bx bx-list-ul'></i>
        <span class="text">Danh sách danh mục</span>
    </a>
</li>
<li>
    <a href="{{ route('admin.categories.create') }}"  class="{{ request()->routeIs('admin.categories.create') ? 'active1' : '' }}">
        <i class='bx bx-plus'></i>
        <span class="text">Thêm danh mục</span>
    </a>
</li>

			</ul>
		</li>

		<li class="has-submenu  {{ request()->is('admin/products*') ? 'active' : '' }}">
    <a href="#"><i class='bx bx-package'></i><span class="text">Sản phẩm</span></a>
    <ul class="submenu">
        <li>
            <a href="{{ route('admin.products.index') }}"  class="{{ request()->routeIs('admin.products.index') ? 'active1' : '' }}">
                <i class='bx bx-list-ul'></i>
                <span class="text">Danh sách sản phẩm</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.products.create') }}"  class="{{ request()->routeIs('admin.products.create') ? 'active1' : '' }}">
                <i class='bx bx-plus'></i>
                <span class="text">Thêm sản phẩm</span>
            </a>
        </li>
		<li>
            <a href="{{ route('admin.category-attribute.create') }}"  class="{{ request()->routeIs('admin.category-attribute.create') ? 'active1' : '' }}">
                <i class='bx bx-link'></i>
                <span class="text">Gán thuộc tính danh mục</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.product-statistics') }}"  class="{{ request()->routeIs('admin.product-statistics') ? 'active1' : '' }}">
                <i class='bx bx-bar-chart'></i>
                <span class="text">Thống kê sản phẩm</span>
            </a>
        </li>
    </ul>
</li>


		<li class="has-submenu  {{ request()->is('admin/posts*') ? 'active' : '' }}">
			<a href="#"><i class='bx bx-envelope'></i><span class="text">Bài viết</span></a>
			<ul class="submenu">
				<li>
                    <a href="{{ route('admin.posts.index') }}"  class="{{ request()->routeIs('admin.posts.index') ? 'active1' : '' }}">
                        <i class='bx bx-list-ul'></i>
                        <span class="text">Danh sách bài viết</span>
                    </a>
                </li>
				<li>
                    <a href="{{ route('admin.posts.create') }}"  class="{{ request()->routeIs('admin.posts.create') ? 'active1' : '' }}">
                        <i class='bx bx-plus'></i>
                        <span class="text">Thêm bài viết</span>
                    </a>
                </li>
			</ul>
		</li>

		<li class="has-submenu  {{ request()->is('admin/users*') ? 'active' : '' }}">
    <a href="#"><i class='bx bxs-group'></i><span class="text">Tài khoản</span></a>
    <ul class="submenu">
        <li>
            <a href="{{ route('admin.users.index') }}"  class="{{ request()->routeIs('admin.users.index') ? 'active1' : '' }}">
                <i class='bx bx-list-ul'></i>
                <span class="text">Danh sách tài khoản</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.users.create') }}"  class="{{ request()->routeIs('admin.users.create') ? 'active1' : '' }}">
                <i class='bx bx-plus'></i>
                <span class="text">Thêm tài khoản</span>
            </a>
        </li>
    </ul>
</li>

<li class="has-submenu  {{ request()->is('admin/roles*') ? 'active' : '' }}">
    <a href="#"><i class='bx bx-shield-quarter'></i><span class="text">Vai trò</span></a>
    <ul class="submenu">
        <li>
            <a href="{{ route('admin.roles.index') }}"  class="{{ request()->routeIs('admin.roles.index') ? 'active1' : '' }}">
                <i class='bx bx-list-ul'></i>
                <span class="text">Danh sách vai trò</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.roles.create') }}"  class="{{ request()->routeIs('admin.roles.create') ? 'active1' : '' }}">
                <i class='bx bx-plus'></i>
                <span class="text">Thêm vai trò</span>
            </a>
        </li>
    </ul>
</li>




		<li class="has-submenu  {{ request()->is('admin/orders*') ? 'active' : '' }}">
    <a href="#"><i class='bx bx-cart'></i><span class="text">Đơn hàng</span></a>
    <ul class="submenu">
        <li>
            <a href="{{ route('admin.orders.index') }}"  class="{{ request()->routeIs('admin.orders.index') ? 'active1' : '' }}">
                <i class='bx bx-list-ul'></i><span class="text">Danh sách đơn hàng</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.orders.statistics') }}"  class="{{ request()->routeIs('admin.orders.statistics') ? 'active1' : '' }}">
                <i class='bx bx-bar-chart'></i><span class="text">Thống kê đơn hàng</span>
            </a>
        </li>
    </ul>
</li>


		<li class="has-submenu  {{ request()->is('admin/courts*') ? 'active' : '' }}">
    <a href="#"><i class='bx bx-calendar-alt'></i><span class="text">Quản lí sân</span></a>
    <ul class="submenu">
        <li>
            <a href="{{ route('admin.courts.index') }}"  class="{{ request()->routeIs('admin.courts.index') ? 'active1' : '' }}">
                <i class='bx bx-list-ul'></i><span class="text">Danh sách sân</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.bookings.index') }}"  class="{{ request()->routeIs('admin.bookings.index') ? 'active1' : '' }}">
                <i class='bx bx-stopwatch'></i><span class="text">Lịch đặt sân</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.courts.create') }}"  class="{{ request()->routeIs('admin.courts.create') ? 'active1' : '' }}">
                <i class='bx bx-plus'></i><span class="text">Thêm sân</span>
            </a>
        </li>
    </ul>
</li>

<!-- Quản lý đặt sân -->
<li class="has-submenu  {{ request()->is('admin/bookings*') ? 'active' : '' }}">
    <a href="#"><i class='bx bx-calendar-alt'></i><span class="text">Quản lý đặt sân</span></a>
    <ul class="submenu">
        <li>
            <a href="{{ route('admin.bookings.index') }}"  class="{{ request()->routeIs('admin.bookings.index') ? 'active1' : '' }}">
                <i class='bx bx-stopwatch'></i><span class="text">Lịch đặt sân</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.bookings.create') }}"  class="{{ request()->routeIs('admin.bookings.create') ? 'active1' : '' }}">
                <i class='bx bx-plus-circle'></i><span class="text">Tạo lịch đặt</span>
            </a>
        </li>
    </ul>
</li>

		<li class="has-submenu  {{ request()->is('admin/comments*') ? 'active' : '' }}">
			<a href="#"><i class='bx bxs-message-dots'></i><span class="text">Bình luận</span></a>
			<ul class="submenu">
				<li><a href="admin.comments.index"  class="{{ request()->routeIs('admin.comments.index') ? 'active1' : '' }}">
                    <i class='bx bx-list-ul'></i><span class="text">Bình luận bài viết</span>
                </a>
                </li>
				<li><a href="admin.comments.create"  class="{{ request()->routeIs('admin.comments.create') ? 'active1' : '' }}">
                    <i class='bx bx-list-ul'></i><span class="text">Bình luận sản phẩm</span>
                </a>
                </li>
			</ul>
		</li>
		<li class="has-submenu  {{ request()->is('admin/vouchers*') ? 'active' : '' }}">
			<a href="#">
				<i class='bx bxs-discount'></i>
				<span class="text">Mã giảm giá</span>
			</a>
			<ul class="submenu">
				<li>
					<a href="admin.comments.index"  class="{{ request()->routeIs('admin.comments.index') ? 'active1' : '' }}">
						<i class='bx bx-list-ul'></i>
						<span class="text">Danh sách Mã giảm giá</span>
					</a>
				</li>
				<li>
					<a href="admin.comments.create"  class="{{ request()->routeIs('admin.comments.create') ? 'active1' : '' }}">
						<i class='bx bx-plus'></i>
						<span class="text">Thêm Mã giảm giá</span>
					</a>
				</li>
			</ul>
		</li>


	</ul>
</section>
