<script setup="" lang="ts">
	import { ref } from 'vue';

	const props = defineProps(['title', 'modelValue', 'size']);
	const emits = defineEmits(['update:modelValue']);

	const content = ref();

	function handleClick(e: any) {
		if (e.path.indexOf(content.value) < 0) {
			emits('update:modelValue', false);
		}
	}
</script>

<template>
	<div class="modal fade" :class="{ show: props.modelValue }" @click="handleClick">
		<div :class="`modal-dialog animate-down modal-${props.size || 'base'}`" ref="content">
			<div class="modal-content">
				<div class="modal-header" v-if="props.title">
					<div class="modal-title">
						{{ props.title }}
					</div>
					<button type="button" class="btn btn-sm" @click="emits('update:modelValue', false)">
						X
					</button>
				</div>
				<div class="modal-body">
					<slot></slot>
				</div>
			</div>
		</div>
	</div>
</template>

<style scoped>
	.show {
		display: block !important;
		background: rgb(0, 0, 0, 0.5);
	}

	.animate-down {
		animation: animateDown 0.5s;
	}

	@keyframes animateDown {
		from {
			transform: translateY(-300px);
		}
	}
</style>
