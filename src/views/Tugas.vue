<script context="module" lang="ts">
	export interface UserTugas {
		id?: string;
		userId?: string;
		user?: User;
		jenis?: string;
		deadline?: string;
		status?: string;
		created_at?: string;
		updated_at?: string;
	}
</script>

<script setup="" lang="ts">
	import { computed, reactive, ref, watch } from 'vue';
	import Table from '../components/Table.vue';
	import Modal from '../components/Modal.vue';
	import { createResource, http, notif, formatDate } from '../lib';
	import { User } from './User.vue';

	const req = ref<UserTugas>({});

	const state = reactive({
		isEdit: false,
		modal: {
			save: false,
			delete: false,
		},
		req,
		filter: {
			_limit: 10,
			_page: 1,
		},
	});

	const {
		data: tugasSelesai,
		isValidating,
		error,
		mutate: mutateTugasSelesai,
	} = createResource(['/user/tugas', { ...state.filter, status: '1' }], (url, filter) =>
		http.get(url, {
			params: filter,
		}),
	);
	const { data: tugasBelum, mutate: mutateTugasBelum } = createResource(
		['/user/tugas', { ...state.filter, status: '0' }],
		(url, filter) =>
			http.get(url, {
				params: filter,
			}),
	);
	const { data: user } = createResource('/user', (url) => http.get(url));

	function nullable() {
		state.req = {
			userId: '',
			status: '0',
			deadline: '',
		};
	}

	function get() {
		mutateTugasBelum();
		mutateTugasSelesai();
	}

	function save() {
		if (state.isEdit) {
			http
				.put('/user/tugas/' + state.req.id, state.req)
				.then((res) => {
					notif('data berhasil diedit');
					state.modal.save = false;
					get();
				})
				.catch((err) => notif(err.response.data, 'danger'));
		} else {
			http
				.post('/user/tugas', state.req)
				.then((res) => {
					notif('data berhasil ditambah');
					state.modal.save = false;
					get();
				})
				.catch((err) => notif(err.response.data, 'danger'));
		}
	}

	function destroy() {
		http
			.delete('/user/tugas/' + state.req.id)
			.then((res) => {
				notif('data berhasil dihapus');
				state.modal.delete = false;
				get();
			})
			.catch((err) => {
				notif(err.response.data, 'danger');
			});
	}

	watch(error, (val) => {
		if (val) {
			notif(val.response.data, 'danger');
		}
	});
</script>

<template>
	<div>
		<div class="h3 mb-3">Data Tugas</div>
		<div class="card">
			<div class="card-body">
				<div class="row mb-3 align-items-center">
					<div class="col">
						<div class="card-title mb-0">Belum Diselesaikan</div>
					</div>
					<div class="col" align="right">
						<button
							type="button"
							@click="
								nullable();
								state.isEdit = false;
								state.modal.save = true;
								state.req.status = '0';
							"
							class="btn btn-primary"
						>
							+ Tambah
						</button>
					</div>
				</div>
				<Table
					:loading="isValidating"
					:keys="{
						'Jenis Tugas': 'jenis',
						Karyawan: 'user',
						Deadline: 'deadline',
						Lampiran: 'lampiran',
						Opsi: 'opsi',
					}"
					:items="tugasBelum?.data"
				>
					<template #user="{ item }">
						{{ item.user?.nama }}
					</template>

					<template #lampiran="{ item }">
						<button type="button" class="btn btn-sm btn-success" v-if="item.totalPengumpulan">
							{{ item.totalPengumpulan }} Berkas
						</button>
						<span v-else>Tidak tersedia</span>
					</template>

					<template #deadline="{ item }">
						{{ formatDate(item.deadline, true) }}
					</template>

					<template #opsi="{ item }">
						<button
							class="btn btn-sm mx-1 btn-primary"
							@click="
								state.modal.save = true;
								state.isEdit = true;
								state.req = JSON.parse(JSON.stringify(item));
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
				Menampilkan {{ tugasBelum?.data.length }} dari
				{{ tugasBelum?.headers['x-total-count'] }} data
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<div class="card-title">Telah Diselesaikan</div>
				<Table
					:loading="isValidating"
					:keys="{
						'Jenis Tugas': 'jenis',
						Petugas: 'user',
						Deadline: 'deadline',
						Lampiran: 'lampiran',
						Opsi: 'opsi',
					}"
					:items="tugasSelesai?.data"
				>
					<template #user="{ item }">
						{{ item.user?.nama }}
					</template>

					<template #lampiran="{ item }">
						<button type="button" class="btn btn-sm btn-success" v-if="item.totalPengumpulan">
							{{ item.totalPengumpulan }} Berkas
						</button>
						<span v-else>Tidak tersedia</span>
					</template>

					<template #deadline="{ item }">
						{{ formatDate(item.deadline, true) }}
					</template>

					<template #opsi="{ item }">
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
				Menampilkan {{ tugasSelesai?.data.length }} dari
				{{ tugasSelesai?.headers['x-total-count'] }} data
			</div>
		</div>

		<Modal v-model="state.modal.save" :title="state.isEdit ? 'Edit Tugas' : 'Tambah Tugas'">
			<form @submit.prevent="save">
				<div class="mb-3">
					<label for="">Jenis Tugas</label>
					<input
						type="text"
						class="form-control"
						v-model="state.req.jenis"
						placeholder="Masukkan Jenis Tugas"
					/>
				</div>
				<div class="mb-3">
					<label for="">Karyawan</label>
					<select v-model="state.req.userId" class="form-control">
						<option value="">Pilih Karyawan</option>
						<option v-for="item in user?.data" :value="item.id">{{ item.nama }}</option>
					</select>
				</div>

				<div class="mb-3">
					<label for="">Deadline (Batas Waktu)</label>
					<input
						type="datetime-local"
						v-model="state.req.deadline"
						class="form-control"
						placeholder="Masukkan Deadline"
					/>
				</div>

				<div class="mt-5">
					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>
			</form>
		</Modal>

		<Modal v-model="state.modal.delete" title="Hapus Karyawan">
			<form @submit.prevent="destroy">
				<p>
					Anda yakin menghapus data tugas <strong>{{ state.req.jenis }}</strong
					>?
				</p>
				<div class="mt-5">
					<button type="submit" class="btn btn-danger">Hapus</button>
				</div>
			</form>
		</Modal>
	</div>
</template>
