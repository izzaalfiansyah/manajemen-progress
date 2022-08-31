<script setup="" lang="ts">
	const props = defineProps(['header', 'body', 'items']);
</script>

<template>
	<div class="table-responsive">
		<table class="table table-borderless table-hover d-lg-table d-none">
			<thead>
				<tr>
					<th v-for="item in props.header">{{ item.toUpperCase() }}</th>
				</tr>
			</thead>
			<tbody>
				<tr v-if="props.body" v-for="(rows, x) in props.body">
					<td v-for="(item, i) in rows">
						<slot :name="item" :item="items[x]">{{ item }}</slot>
					</td>
				</tr>
				<tr v-else>
					<td :colspan="props.header.length" class="text-center">Data tidak tersedia</td>
				</tr>
			</tbody>
		</table>
		<table class="d-lg-none d-block table table-hover table-borderless">
			<tbody class="d-block">
				<tr v-if="props.body" v-for="(rows, x) in props.body" class="d-block">
					<td class="d-flex row" v-for="(item, i) in rows">
						<div class="col">
							<strong>{{ props.header[i].toUpperCase() }}</strong>
						</div>
						<div class="col" align="right">
							<slot :name="item" :item="items[x]">{{ item }}</slot>
						</div>
					</td>
				</tr>
				<tr v-else class="d-block">
					<td class="text-center d-block">data tidak tersedia</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>
