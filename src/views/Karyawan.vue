<script setup="" lang="ts">
	import { reactive } from 'vue';
	import DataList from '../components/DataList.vue';
	import Modal from '../components/Modal.vue';

	const state = reactive({
		isEdit: false,
		modal: {
			save: false,
			delete: false,
		},
		items: [
			{
				username: 'Alfiansyah',
				role: '1',
				telepon: '6281231921351',
			},
			{
				username: 'Amelia',
				role: '2',
				telepon: '6281259203376',
			},
		],
		req: {
			username: '',
			password: '',
			role: '',
			telepon: '',
		},
	});

	function nullable() {
		state.req = {
			username: '',
			password: '',
			role: '',
			telepon: '',
		};
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
			<DataList
				:header="['No', 'Nama', 'Jabatan', 'Nomor HP', 'Opsi']"
				:items="state.items"
				:body="
					state.items.map((item, i) => [
						i + 1,
						item.username,
						item.role == '1' ? 'Admin' : 'Pegawai',
						item.telepon,
						'opsi',
					])
				"
			>
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
			</DataList>
		</div>

		<Modal v-model="state.modal.save" :title="state.isEdit ? 'Edit Karyawan' : 'Tambah Karyawan'">
			<div class="mb-2">
				<label for="">Username</label>
				<input
					type="text"
					placeholder="Masukkan Username"
					class="form-control"
					v-model="state.req.username"
				/>
			</div>
			<div class="mb-2">
				<label for="">Password</label>
				<input
					type="password"
					placeholder="Masukkan Password"
					class="form-control"
					v-model="state.req.password"
				/>
			</div>
			<div class="mb-2">
				<label for="">Jabatan</label>
				<select v-model="state.req.role" class="form-control">
					<option value="">Pilih Jabatan</option>
					<option value="1">Admin</option>
					<option value="2">Pegawai</option>
				</select>
			</div>
			<div class="mb-2">
				<label for="">Nomor HP</label>
				<input
					type="tel"
					placeholder="Masukkan Nomor HP"
					class="form-control"
					v-model="state.req.telepon"
				/>
			</div>

			<div class="mt-5">
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>
		</Modal>

		<Modal v-model="state.modal.delete" title="Hapus Karyawan">
			<p>
				Anda yakin menghapus data karyawan <strong>{{ state.req.username }}</strong
				>?
			</p>
			<div class="mt-5">
				<button type="submit" class="btn btn-danger">Hapus</button>
			</div>
		</Modal>
	</div>
</template>
