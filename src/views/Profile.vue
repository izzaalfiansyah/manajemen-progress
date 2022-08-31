<script setup="" lang="ts">
	import { reactive } from 'vue';

	const state = reactive({
		req: {
			id: '',
			username: '',
			nama: '',
			password: '',
			telepon: '',
		},
	});
</script>

<template>
	<div class="h3 mb-3">Profil</div>
	<div class="card">
		<div class="card-body">
			<div class="mb-3">
				<label for="">Nama</label>
				<input
					type="text"
					placeholder="Masukkan Nama"
					class="form-control"
					v-model="state.req.nama"
				/>
			</div>
			<div class="mb-3">
				<label for="">Telepon</label>
				<input
					type="tel"
					placeholder="Masukkan Telepon"
					class="form-control"
					v-model="state.req.telepon"
				/>
			</div>
			<div class="row">
				<div class="col-lg">
					<div class="mb-3">
						<label for="">Username</label>
						<input
							type="text"
							placeholder="Masukkan Username"
							class="form-control"
							v-model="state.req.username"
						/>
					</div>
				</div>
				<div class="col-lg">
					<div class="mb-3">
						<label for="">Password</label>
						<input
							type="text"
							placeholder="Masukkan Password"
							class="form-control"
							v-model="state.req.password"
						/>
						<small class="text-secondary">Kosongkan jika tidak ingin mengganti password</small>
					</div>
				</div>
			</div>
			<div class="mt-5">
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>
		</div>
	</div>
</template>
