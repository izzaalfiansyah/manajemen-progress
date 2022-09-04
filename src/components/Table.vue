<script setup="" lang="ts">
	import { computed } from 'vue';

	const props = defineProps(['keys', 'items', 'loading']);

	const headers = computed(() => {
		return props.keys ? Object.keys(props.keys) : [];
	});

	const keys = computed(() => {
		return props.keys ? Object.values(props.keys) : [];
	});

	const items = computed(() => {
		return props.items ? props.items : [];
	});
</script>

<template>
	<div class="table-responsive">
		<table class="table table-nowrap table-borderless table-hover d-lg-table d-none">
			<thead>
				<tr>
					<th v-for="item in headers">{{ item.toUpperCase() }}</th>
				</tr>
			</thead>
			<tbody>
				<template v-if="props.loading">
					<tr>
						<td :colspan="headers.length" class="p-0">
							<div class="p-4 skeleton"></div>
						</td>
					</tr>
				</template>
				<template v-else>
					<tr v-if="items.length" v-for="item in items">
						<td v-for="key in keys">
							<slot :name="key" :item="item">{{ item[key] }}</slot>
						</td>
					</tr>
					<tr v-else>
						<td :colspan="headers.length" class="text-center">Data tidak tersedia</td>
					</tr>
				</template>
			</tbody>
		</table>
		<table class="d-lg-none d-block table table-hover table-borderless">
			<tbody class="d-block">
				<template v-if="props.loading">
					<tr class="d-block">
						<td class="d-block p-0" :colspan="headers.length">
							<div class="p-4 skeleton"></div>
						</td>
					</tr>
				</template>
				<template v-else>
					<tr v-if="items.length" v-for="item in items" class="d-block">
						<td class="d-flex align-items-center" v-for="(key, i) in keys">
							<div class="col pr-2">
								<strong>{{ headers[i].toUpperCase() }}</strong>
							</div>
							<div class="col pl-2" align="right">
								<slot :name="key" :item="item">{{ item[key] }}</slot>
							</div>
						</td>
					</tr>
					<tr v-else class="d-block">
						<td class="text-center d-block">data tidak tersedia</td>
					</tr>
				</template>
			</tbody>
		</table>
	</div>
</template>
