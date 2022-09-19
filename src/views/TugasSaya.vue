<script context="module" lang="ts">
	export interface UserTugasPengumpulan {
		id?: string;
		userTugasId?: string;
		file?: string;
	}
</script>

<script setup="" lang="ts">
	import { reactive, ref, watch } from 'vue';
	import Table from '../components/Table.vue';
	import { createResource, http, notif, formatDate, auth, readFile } from '../lib';
	import Modal from '../components/Modal.vue';

	const req = ref<UserTugasPengumpulan>({});

	const state = reactive({
		isEdit: false,
		modal: {
			lampiran: false,
		},
		req,
		filter: {
			_limit: 10,
			_page: 1,
			q: '',
			status: '',
			userId: auth.id,
		},
	});

	const { data, mutate, error, isValidating } = createResource(
		['/user/tugas', state.filter],
		(url, filter) =>
			http.get(url, {
				params: filter,
			}),
	);

	const {
		data: pengumpulan,
		mutate: mutatePengumpulan,
		isValidating: loadPengumpulan,
	} = createResource(['/user/tugas/pengumpulan'], (url) => {
		return http.get(url, { params: { userTugasId: state.req.userTugasId } });
	});

	function handleLampiran(e: any) {
		readFile(e.currentTarget.files[0]).then((res) => {
			state.req.file = res as string;
		});
	}

	function store() {
		http
			.post('/user/tugas/pengumpulan', state.req)
			.then((res) => {
				notif('data berhasil disimpan');
				mutatePengumpulan();
			})
			.catch((err) => notif(err.response.data, 'danger'));
	}

	function destroy() {
		http
			.delete('/user/tugas/pengumpulan/' + state.req.id)
			.then((res) => {
				notif('data berhasil dihapus');
				mutatePengumpulan();
			})
			.catch((err) => notif(err.response.data, 'danger'));
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
					}"
					:items="data?.data"
				>
					<template #user="{ item }">
						{{ item.user?.nama }}
					</template>

					<template #lampiran="{ item }">
						<button
							type="button"
							:class="'btn btn-sm ' + (item.totalPengumpulan ? 'btn-success' : 'btn-primary')"
							@click="
								state.req.userTugasId = item.id;
								state.modal.lampiran = true;
								mutatePengumpulan();
							"
						>
							<span v-if="item.totalPengumpulan">{{ item.totalPengumpulan }} Berkas</span>
							<span v-else>+ Tambah</span>
						</button>
					</template>

					<template #deadline="{ item }">
						{{ formatDate(item.deadline, true) }}
					</template>

					<template #dikumpulkan="{ item }">
						{{ item.totalPengumpulan ? formatDate(item.tanggalPengumpulan, true) : 'Belum' }}
					</template>
				</Table>
				Menampilkan {{ data?.data.length }} dari {{ data?.headers['x-total-count'] }} data
			</div>
		</div>

		<Modal title="Lampiran Tugas" v-model="state.modal.lampiran" size="lg">
			<div class="row">
				<div class="col-lg-6">
					<div class="card">
						<div class="card-body">
							<form @submit.prevent="store">
								<div class="mb-2">
									<label for="">Tambah Lampiran : </label>
									<input
										type="file"
										class="form-control"
										@change="handleLampiran"
										accept="application/pdf"
									/>
									<div class="text-center">
										<small class="form-control-feedback">File harus berupa pdf</small>
									</div>
								</div>
								<button class="btn btn-primary btn-block" style="width: 100%">Simpan</button>
							</form>
						</div>
					</div>
				</div>
			</div>
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
					<button
						class="btn btn-sm btn-danger"
						type="button"
						@click="
							state.req.id = item.id;
							destroy();
						"
					>
						<i class="bi bi-trash"></i>
					</button>
				</template>
			</Table>
		</Modal>
	</div>
</template>
