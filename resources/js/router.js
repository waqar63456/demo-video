import { createRouter, createWebHistory } from 'vue-router';
// const appName = __APP_NAME__;


const routes = [
    {
        path: '/',
        name: 'odearnhome',
        component: () => import("./pages/OdLogin.vue"),
        meta: {
            title: `Home `,
            description: 'Welcome to the homepage of MyApp.'
        }

    },
   
    {
        path: '/odlogin',
        name: 'OdLogin',
        component: () => import("./pages/OdLogin.vue"),
        meta: {
            title: `Login`,
            description: `Login to your  account.`
        }

    },
    {
        path: '/Dashboard',
        name: 'Dashboard',
        component: () => import("./pages/Dashboard.vue"),
        meta: {
            title: `Dashboard`,
            description: `Access your personal dashboard on .`,
            requiresAuth: true
        }

    },
   
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
    scrollBehavior(to, from, savedPosition) {
        // always scroll to top
        return { top: 0 };
    },
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('authToken'); // Retrieve token from localStorage

    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!token) {
            next('/');
        } else {
            next(); // Allow access
        }
    } else {
        next(); // Allow access to routes without `requiresAuth`
    }
});


export default router;
