<script setup lang="ts">
	import { reactive, watch } from 'vue';
	import { auth, createResource, http, notif } from '../lib';
	import { User } from '../views/User.vue';
	import Modal from './Modal.vue';

	const user: User = {};

	const state = reactive({
		showLogout: false,
		isLogout: false,
		user,
	});

	let navItems: { title: string; icon: string; path: string }[] = [];

	if (auth.role == '1') {
		navItems = [
			{
				title: 'Beranda',
				icon: 'home',
				path: '/',
			},
			{
				title: 'Data User',
				icon: 'users',
				path: '/user',
			},
			{
				title: 'Data Tugas',
				icon: 'airplay',
				path: '/tugas',
			},
			{
				title: 'Akun',
				icon: 'user',
				path: '/profil',
			},
		];
	} else if (auth.role == '2') {
		navItems = [
			{
				title: 'Beranda',
				icon: 'home',
				path: '/',
			},
			{
				title: 'Data Tugas',
				icon: 'airplay',
				path: '/tugas',
			},
			{
				title: 'Akun',
				icon: 'user',
				path: '/profil',
			},
		];
	} else {
		navItems = [
			{
				title: 'Beranda',
				icon: 'home',
				path: '/',
			},
			{
				title: 'Tugas Saya',
				icon: 'airplay',
				path: '/tugas-saya',
			},
			{
				title: 'Akun',
				icon: 'user',
				path: '/profil',
			},
		];
	}

	const { data, error } = createResource('/user/' + auth.id, (url) => http.get(url));

	watch(data, (val) => {
		state.user = val?.data;
		state.user.password = '';
	});

	watch(error, (val) => {
		if (val) {
			notif(val.response.data, 'danger');
		}
	});

	function logout() {
		state.showLogout = false;
		state.isLogout = true;
		localStorage.removeItem('id');
		setTimeout(() => {
			window.location.href = window.location.origin + window.location.pathname;
		}, 400);
	}

	function handleSidebar(e: Event) {
		e.preventDefault();
		const { clientWidth } = document.documentElement;

		if (clientWidth <= 992) {
			document.getElementById('sidebar')?.classList.toggle('collapsed');
		}
	}
</script>

<template>
	<div
		class="loading d-flex align-items-center justify-content-center position-fixed bg-white"
		:class="{ 'd-none': !state.isLogout }"
		style="z-index: 1000; top: 0; left: 0; right: 0; bottom: 0"
	>
		Mengakhiri sesi. Mengalihkan...
	</div>
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
				<a href="#" @click="handleSidebar" class="d-lg-none d-block py-3">
					<i class="hamburger align-self-center"></i>
				</a>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<RouterLink class="nav-icon d-inline-block d-sm-none" to="/profil">
								<i class="align-middle" data-feather="user"></i>
							</RouterLink>

							<RouterLink class="nav-link d-none d-sm-inline-block" to="/profil">
								<span class="text-dark">{{ state.user.nama }}</span>
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
