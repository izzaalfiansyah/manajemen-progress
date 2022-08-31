<script setup lang="ts">
	import './assets/css/app.css';
	import './assets/js/app.js';

	import Admin from './components/Admin.vue';
	import Login from './components/Login.vue';

	const id = localStorage.getItem('id');
</script>

<template>
	<Admin v-if="id"></Admin>
	<Login v-else></Login>
</template>

<style>
	.toast-top-right {
		position: fixed;
		top: 10px;
		right: 10px;
		max-width: 90vw;
		animation: toastAnimation 0.5s;
	}

	@keyframes toastAnimation {
		from {
			transform: translateY(-100%);
		}
	}
</style>
