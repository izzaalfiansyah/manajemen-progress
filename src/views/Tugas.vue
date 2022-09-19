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
	import { reactive, ref, watch } from 'vue';
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
			status: false,
			lampiran: false,
		},
		req,
		filter: {
			_limit: 10,
			_page: 1,
			q: '',
			status: '',
		},
	});

	const { data, mutate, error, isValidating } = createResource(
		['/user/tugas', state.filter],
		(url, filter) =>
			http.get(url, {
				params: filter,
			}),
	);

	const { data: user } = createResource('/user', (url) => http.get(url));

	const {
		data: pengumpulan,
		mutate: mutatePengumpulan,
		isValidating: loadPengumpulan,
	} = createResource(['/user/tugas/pengumpulan'], (url) => {
		return http.get(url, { params: { userTugasId: state.req.id } });
	});

	function nullable() {
		state.req = {
			userId: '',
			status: '0',
			deadline: '',
		};
	}

	function save() {
		if (state.isEdit) {
			state.req.deadline = state.req.deadline?.slice(0, 16);
			http
				.put('/user/tugas/' + state.req.id, state.req)
				.then((res) => {
					notif('data berhasil diedit');
					state.modal.save = false;
					mutate();
				})
				.catch((err) => notif(err.response.data, 'danger'));
		} else {
			http
				.post('/user/tugas', state.req)
				.then((res) => {
					notif('data berhasil ditambah');
					state.modal.save = false;
					mutate();
				})
				.catch((err) => notif(err.response.data, 'danger'));
		}
	}

	function changeStatus() {
		state.req.deadline = state.req.deadline?.slice(0, 16);
		http
			.put('/user/tugas/' + state.req.id, state.req)
			.then((res) => {
				notif('status berhasil disimpan');
				state.modal.status = false;
				mutate();
			})
			.catch((err) => notif(err.response.data, 'danger'));
	}

	function destroy() {
		http
			.delete('/user/tugas/' + state.req.id)
			.then((res) => {
				notif('data berhasil dihapus');
				state.modal.delete = false;
				mutate();
			})
			.catch((err) => {
				notif(err.response.data, 'danger');
			});
	}

	watch(
		() => JSON.parse(JSON.stringify(state.filter)),
		(val, old) => {
			if (val._page == old._page) {
				state.filter._page = 1;
			}
			mutate();
		},
	);

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
				<div class="mb-3">
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
					<div class="mb-3 col-lg-4">
						<input
							type="text"
							class="form-control"
							v-model.lazy="state.filter.q"
							placeholder="Cari..."
						/>
					</div>
					<div class="mb-3 col-lg-4">
						<select v-model="state.filter.status" class="form-control">
							<option value="">Semua</option>
							<option value="0">Menunggu</option>
							<option value="1">Disetujui</option>
							<option value="2">Ditolak</option>
						</select>
					</div>
				</div>
				<Table
					:loading="isValidating"
					:keys="{
						'Jenis Tugas': 'jenis',
						Karyawan: 'user',
						Deadline: 'deadline',
						Dikumpulkan: 'dikumpulkan',
						Lampiran: 'lampiran',
						Status: 'statusDetail',
						Opsi: 'opsi',
					}"
					:items="data?.data"
				>
					<template #user="{ item }">
						{{ item.user?.nama }}
					</template>

					<template #lampiran="{ item }">
						<button
							type="button"
							class="btn btn-sm btn-success"
							v-if="item.totalPengumpulan"
							@click="
								state.req.id = item.id;
								state.modal.lampiran = true;
								mutatePengumpulan();
							"
						>
							{{ item.totalPengumpulan }} Berkas
						</button>
						<span v-else>Tidak tersedia</span>
					</template>

					<template #deadline="{ item }">
						{{ formatDate(item.deadline, true) }}
					</template>

					<template #dikumpulkan="{ item }">
						{{ item.totalPengumpulan ? formatDate(item.tanggalPengumpulan, true) : 'Belum' }}
					</template>

					<template #opsi="{ item }">
						<button
							class="btn btn-sm m-1 btn-primary"
							@click="
								state.modal.save = true;
								state.isEdit = true;
								state.req = JSON.parse(JSON.stringify(item));
								state.req.deadline = item.deadline;
							"
						>
							<i class="bi bi-vector-pen"></i>
						</button>
						<button
							class="btn btn-sm btn-danger m-1"
							@click="
								state.modal.delete = true;
								state.req = JSON.parse(JSON.stringify(item));
							"
						>
							<i class="bi bi-trash"></i>
						</button>
						<button
							class="btn btn-sm btn-success m-1"
							@click="
								state.modal.status = true;
								state.isEdit = true;
								state.req = JSON.parse(JSON.stringify(item));
								state.req.deadline = item.deadline;
							"
						>
							<i class="bi bi-check"></i> Status
						</button>
					</template>
				</Table>
				Menampilkan {{ data?.data.length }} dari {{ data?.headers['x-total-count'] }} data
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

		<Modal v-model="state.modal.status" title="Setujui/Tolak">
			<form @submit.prevent="changeStatus">
				<div class="mb-2">
					<label for="">Pilih Status : </label>
					<div class="form-check">
						<input
							v-model="state.req.status"
							name="status"
							value="0"
							class="form-check-input"
							type="radio"
							id="flexRadioDefault0"
							disabled
						/>
						<label class="form-check-label" for="flexRadioDefault0"> Menunggu </label>
					</div>
					<div class="form-check">
						<input
							v-model="state.req.status"
							name="status"
							value="1"
							class="form-check-input"
							type="radio"
							id="flexRadioDefault1"
						/>
						<label class="form-check-label" for="flexRadioDefault1"> Setujui </label>
					</div>
					<div class="form-check">
						<input
							v-model="state.req.status"
							name="status"
							value="2"
							class="form-check-input"
							type="radio"
							id="flexRadioDefault2"
						/>
						<label class="form-check-label" for="flexRadioDefault2"> Tolak </label>
					</div>
				</div>
				<div class="mt-5">
					<button type="submit" class="btn btn-success">Simpan</button>
				</div>
			</form>
		</Modal>

		<Modal title="Lampiran Tugas" v-model="state.modal.lampiran" size="lg">
			<Table
				:loading="loadPengumpulan"
				:keys="{
					No: 'no',
					File: 'file',
					Opsi: 'opsi',
				}"
				:items="pengumpulan?.data"
			>
				<template #no="{ index }">
					{{ index + 1 }}
				</template>

				<template #opsi="{ item }">
					<a :href="item.fileUrl" target="_blank" class="btn btn-sm mx-1 btn-success">
						<i class="bi bi-eye"></i>
					</a>
				</template>
			</Table>
		</Modal>
	</div>
</template>
