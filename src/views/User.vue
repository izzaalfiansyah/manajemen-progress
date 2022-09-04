<script type="module" lang="ts">
	export interface User {
		id?: string;
		username?: string;
		nama?: string;
		password?: string;
		role?: string;
		telepon?: string;
		alamat?: string;
	}
</script>

<script setup="" lang="ts">
	import { computed, reactive, ref, watch } from 'vue';
	import Table from '../components/Table.vue';
	import Modal from '../components/Modal.vue';
	import { http, createResource, notif } from '../lib';

	const req = ref<User>({});
	const state = reactive({
		isEdit: false,
		modal: {
			save: false,
			delete: false,
		},
		req,
		filter: {
			q: '',
			_limit: 5,
			_page: 1,
		},
	});

	const { data, error, isValidating, mutate } = createResource(
		['/user', state.filter],
		(url, filter) =>
			http.get(url, {
				params: filter,
			}),
	);

	watch(error, (val) => {
		if (val) {
			notif(val.response.data, 'danger');
		}
	});

	watch(
		() => JSON.parse(JSON.stringify(state.filter)),
		(val, old) => {
			if (val._page == old._page) {
				state.filter._page = 1;
			}
			mutate();
		},
	);

	const totalPage = computed(() => {
		return Math.ceil(
			parseInt((data.value?.headers['x-total-count'] as string) || '0') / state.filter._limit,
		);
	});

	function nullable() {
		state.req = {
			role: '',
		};
	}

	function save() {
		if (state.isEdit) {
			http
				.put('/user/' + state.req.id, state.req)
				.then((res) => {
					state.modal.save = false;
					notif('data berhasil diedit');
					mutate();
				})
				.catch((err) => {
					notif(err.response.data, 'danger');
				});
		} else {
			http
				.post('/user', state.req)
				.then((res) => {
					state.modal.save = false;
					notif('data berhasil disimpan');
					mutate();
				})
				.catch((err) => {
					notif(err.response.data, 'danger');
				});
		}
	}

	function destroy() {
		http
			.delete('/user/' + state.req.id)
			.then((res) => {
				state.modal.delete = false;
				notif('data berhasil dihapus');
				mutate();
			})
			.catch((err) => {
				notif(err.response.data, 'danger');
			});
	}
</script>

<template>
	<div class="h3 mb-3">Data Karyawan</div>
	<div class="card">
		<div class="card-body">
			<div class="mb-3">
				<button
					@click="
						nullable();
						state.isEdit = false;
						state.modal.save = true;
					"
					type="button"
					class="btn btn-primary"
				>
					+ Tambah
				</button>
			</div>
			<div class="row">
				<div class="mb-3 col-lg-2">
					<select v-model="state.filter._limit" class="form-control">
						<option value="5">5</option>
						<option value="10">10</option>
						<option value="20">20</option>
						<option value="50">50</option>
						<option value="100">100</option>
					</select>
				</div>
				<div class="mb-3 col-lg-6">
					<input
						type="text"
						class="form-control"
						v-model.lazy="state.filter.q"
						placeholder="Cari..."
					/>
				</div>
			</div>
			<Table
				:keys="{
					Nama: 'nama',
					Username: 'username',
					Jabatan: 'role',
					'Nomor HP': 'telepon',
					Opsi: 'opsi',
				}"
				:items="data?.data"
				:loading="isValidating"
			>
				<template #role="{ item }">
					{{ ({ 1: 'Superadmin', 2: 'Manager', 3: 'Karyawan' } as any)[item.role] }}
				</template>

				<template #opsi="{ item }">
					<button
						class="btn btn-sm mx-1 btn-primary"
						@click="
							state.modal.save = true;
							state.isEdit = true;
							state.req = JSON.parse(JSON.stringify(item));
							state.req.password = '';
						"
					>
						<i class="bi bi-vector-pen"></i>
					</button>
					<button
						class="btn btn-sm btn-danger"
						@click="
							state.modal.delete = true;
							state.req = JSON.parse(JSON.stringify(item));
						"
					>
						<i class="bi bi-trash"></i>
					</button>
				</template>
			</Table>
			<div>
				Halaman :
				<select
					v-model="state.filter._page"
					class="form-control d-inline p-1 px-2"
					style="width: 50px"
				>
					<option v-for="(_, index) in Array(totalPage)" :value="index + 1">
						{{ index + 1 }}
					</option>
				</select>
			</div>
		</div>

		<Modal
			v-model="state.modal.save"
			:title="state.isEdit ? 'Edit Karyawan' : 'Tambah Karyawan'"
			size="lg"
		>
			<form @submit.prevent="save">
				<div class="row">
					<div class="col-lg">
						<div class="mb-2">
							<label for="">Username</label>
							<input
								type="text"
								placeholder="Masukkan Username"
								class="form-control"
								required
								v-model="state.req.username"
							/>
						</div>
					</div>
					<div class="col-lg">
						<div class="mb-2">
							<label for="">Password</label>
							<input
								type="password"
								placeholder="Masukkan Password"
								class="form-control"
								:required="!state.isEdit"
								v-model="state.req.password"
							/>
							<small v-if="state.isEdit" class="text-secondary"
								>Kosongkan jika tidak ingin mengganti password</small
							>
						</div>
					</div>
				</div>
				<div class="mb-2">
					<label for="">Nama</label>
					<input
						type="text"
						placeholder="Masukkan Nama"
						class="form-control"
						required
						v-model="state.req.nama"
					/>
				</div>
				<div class="mb-2">
					<label for="">Jabatan</label>
					<select required v-model="state.req.role" class="form-control">
						<option value="">Pilih Jabatan</option>
						<option value="1">Superadmin</option>
						<option value="2">Manager</option>
						<option value="3">Karyawan</option>
					</select>
				</div>
				<div class="mb-2">
					<label for="">Nomor HP</label>
					<input
						type="tel"
						placeholder="Masukkan Nomor HP"
						class="form-control"
						required
						v-model="state.req.telepon"
					/>
				</div>
				<div class="mb-2">
					<label for="">Alamat</label>
					<textarea
						rows="3"
						class="form-control"
						placeholder="Masukkan Alamat"
						required
						v-model="state.req.alamat"
					></textarea>
				</div>

				<div class="mt-5">
					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>
			</form>
		</Modal>

		<Modal v-model="state.modal.delete" title="Hapus Karyawan">
			<form @submit.prevent="destroy">
				<p>
					Anda yakin menghapus data karyawan <strong>{{ state.req.nama }}</strong
					>?
				</p>
				<div class="mt-5">
					<button type="submit" class="btn btn-danger">Hapus</button>
				</div>
			</form>
		</Modal>
	</div>
</template>
