<script setup="" lang="ts">
	import { reactive } from 'vue';
	import DataList from '../components/DataList.vue';
	import Modal from '../components/Modal.vue';

	const state = reactive({
		isEdit: false,
		modal: {
			save: false,
		},
		req: {
			id: '',
			jenis: '',
			petugas_id: '',
			deadline: '',
		},
	});

	function nullable() {
		state.req = {
			id: '',
			jenis: '',
			petugas_id: '',
			deadline: '',
		};
	}
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
							"
							class="btn btn-primary"
						>
							+ Tambah
						</button>
					</div>
				</div>
				<DataList :header="['No', 'Jenis Tugas', 'Petugas', 'Deadline', 'Opsi']"></DataList>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<div class="card-title">Telah Diselesaikan</div>
				<DataList :header="['No', 'Jenis Tugas', 'Petugas', 'Deadline', 'Opsi']"></DataList>
			</div>
		</div>

		<Modal v-model="state.modal.save" title="Tambah Tugas">
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
				<label for="">Petugas</label>
				<select v-model="state.req.petugas_id" class="form-control">
					<option value="">Pilih Petugas</option>
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
		</Modal>
	</div>
</template>
