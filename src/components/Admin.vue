<script setup lang="ts">
	import { reactive } from 'vue';
	import Modal from './Modal.vue';

	const state = reactive({
		showLogout: false,
	});

	const navItems = [
		{
			title: 'Dashboard',
			icon: 'home',
			path: '/',
		},
		{
			title: 'Data Karyawan',
			icon: 'users',
			path: '/karyawan',
		},
		{
			title: 'Data Tugas',
			icon: 'airplay',
			path: '/tugas',
		},
		{
			title: 'Profile',
			icon: 'user',
			path: '/profil',
		},
	];

	function logout() {
		localStorage.removeItem('id');
		window.location.href = window.location.origin + window.location.pathname;
	}

	function handleSidebar() {
		const { clientWidth } = document.documentElement;

		if (clientWidth <= 992) {
			document.getElementById('sidebar')?.classList.toggle('collapsed');
		}
	}
</script>

<template>
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<RouterLink class="sidebar-brand" to="/" @click="handleSidebar">
					<span class="align-middle">Manajemen Proses</span>
				</RouterLink>

				<ul class="sidebar-nav">
					<li class="sidebar-header">Main Menu</li>

					<li
						v-for="item in navItems"
						class="sidebar-item"
						:class="{ active: $route.path == item.path }"
					>
						<RouterLink class="sidebar-link" :to="item.path" @click="handleSidebar">
							<i class="align-middle" :data-feather="item.icon"></i>
							<span class="align-middle">{{ item.title }}</span>
						</RouterLink>
					</li>
				</ul>

				<div class="sidebar-cta">
					<div class="sidebar-cta-content">
						<div class="d-grid">
							<button type="button" @click="state.showLogout = true" class="btn btn-primary">
								Logout
							</button>
						</div>
					</div>
				</div>
			</div>
		</nav>

		<Modal v-model="state.showLogout" title="Logout">
			<p>Anda yakin akan keluar?</p>
			<div class="mt-4 text-right">
				<button @click="logout" class="btn btn-danger">Keluar</button>
			</div>
		</Modal>

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle js-sidebar-toggle">
					<i class="hamburger align-self-center"></i>
				</a>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<RouterLink class="nav-icon d-inline-block d-sm-none" to="/profil">
								<i class="align-middle" data-feather="settings"></i>
							</RouterLink>

							<RouterLink class="nav-link d-none d-sm-inline-block" to="/profil">
								<span class="text-dark">Charles Hall</span>
							</RouterLink>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content">
				<div class="container-fluid p-0 router-views">
					<RouterView></RouterView>
				</div>
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0">
								<a class="text-muted" href="https://fopegram.id/" target="_blank"
									><strong>Fopegram</strong></a
								>
								&copy;
							</p>
						</div>
						<div class="col-6 text-end">
							<ul class="list-inline">
								<li class="list-inline-item">
									<a class="text-primary" href="https://izzaalfiansyah.vercel.app/" target="_blank"
										>Support</a
									>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="https://fopegram.id/" target="_blank">Privacy</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>
</template>

<style>
	.router-views > * {
		animation: toLeft 0.5s;
	}

	@keyframes toLeft {
		from {
			transform: translateX(100%);
		}
	}
</style>
