<script lang="ts" setup>
	import { reactive } from 'vue';

	const state = reactive({
		req: {
			username: '',
			password: '',
		},
		showPassword: false,
		isLogin: false,
	});

	function login() {
		state.isLogin = true;
		localStorage.setItem('id', state.req.username);
		setTimeout(() => {
			window.location.href = window.location.origin + window.location.pathname;
		}, 1500);
	}
</script>

<template>
	<div
		class="d-flex justify-content-center align-items-center position-fixed bg-white"
		:class="{ 'd-none': !state.isLogin }"
		style="z-index: 1000; top: 0; left: 0; right: 0; bottom: 0"
	>
		<div class="text-lg">Berhasil login. Mengalihkan...</div>
	</div>
	<main class="d-flex w-100">
		<div class="container d-flex flex-column">
			<div class="row vh-100">
				<div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">
						<div class="text-center mt-4">
							<h1 class="h2">Selamat Datang</h1>
							<p class="lead">Masukkan data anda untuk memulai sesi</p>
						</div>

						<div class="card">
							<div class="card-body">
								<div class="m-sm-4">
									<form @submit.prevent="login">
										<div class="mb-3">
											<label class="form-label">Username</label>
											<input
												class="form-control form-control-lg"
												type="text"
												placeholder="Masukkan Username atau Email"
												v-model="state.req.username"
											/>
										</div>
										<div class="mb-3">
											<label class="form-label">Password</label>
											<input
												class="form-control form-control-lg"
												:type="state.showPassword ? 'text' : 'password'"
												placeholder="********"
												v-model="state.req.password"
											/>
										</div>
										<div>
											<label class="form-check">
												<input
													class="form-check-input"
													type="checkbox"
													v-model="state.showPassword"
												/>
												<span class="form-check-label"> Tampilkan Password </span>
											</label>
										</div>
										<div class="text-center mt-3">
											<button type="submit" class="btn btn-lg btn-primary">MASUK</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
</template>
