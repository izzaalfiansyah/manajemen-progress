import HomeVue from './views/Home.vue';
import UserVue from './views/User.vue';
import TugasVue from './views/Tugas.vue';
import NotFoundVue from './views/NotFound.vue';
import ProfileVue from './views/Profile.vue';

const router = [
	{
		path: '/',
		component: HomeVue,
	},
	{
		path: '/user',
		component: UserVue,
	},
	{
		path: '/tugas',
		component: TugasVue,
	},
	{
		path: '/profil',
		component: ProfileVue,
	},
	{
		path: '/:any(.*)',
		component: NotFoundVue,
	},
];

export default router;
