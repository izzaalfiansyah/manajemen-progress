<script setup="" lang="ts">
	import { reactive, watch } from 'vue';
	import { auth, createResource, http, notif } from '../lib';
	import { User } from './User.vue';

	const state = reactive<{
		req: User;
	}>({
		req: {
			id: '',
			username: '',
			nama: '',
			password: '',
			telepon: '',
		},
	});

	const { data, error, isValidating, mutate } = createResource('/user/' + auth.id, (url) =>
		http.get(url),
	);

	watch(data, (val) => {
		state.req = val?.data;
		state.req.password = '';
	});

	watch(error, (val) => {
		if (val) {
			notif(val.response.data, 'danger');
		}
	});

	function update() {
		http
			.put('/user/' + auth.id, state.req)
			.then((res) => {
				notif('data berhasil diedit');
				mutate();
			})
			.catch((err) => {
				notif(err.response.data, 'danger');
			});
	}
</script>

<template>
	<div class="h3 mb-3">Profil</div>
	<div class="card">
		<form @submit.prevent="update" class="card-body">
			<div class="mb-3">
				<label for="">Nama</label>
				<input
					type="text"
					placeholder="Masukkan Nama"
					class="form-control"
					v-model="state.req.nama"
					:disabled="isValidating"
				/>
			</div>
			<div class="mb-3">
				<label for="">Telepon</label>
				<input
					type="tel"
					placeholder="Masukkan Telepon"
					class="form-control"
					v-model="state.req.telepon"
					:disabled="isValidating"
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
							:disabled="isValidating"
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
							:disabled="isValidating"
						/>
						<small class="text-secondary">Kosongkan jika tidak ingin mengganti password</small>
					</div>
				</div>
			</div>
			<div class="mb-3">
				<label for="">Alamat</label>
				<textarea
					rows="3"
					class="form-control"
					v-model="state.req.alamat"
					:disabled="isValidating"
				></textarea>
			</div>
			<div class="mt-5">
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>
		</form>
	</div>
</template>
