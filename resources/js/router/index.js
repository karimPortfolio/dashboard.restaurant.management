import { createRouter, createWebHistory, RouterView } from "vue-router";
import { AuthMiddleware } from "@/middlewares/AuthMiddleware";
import { RedirectAuthMiddeleware } from "@/middlewares/RedirectAuthMiddelware";

const router = createRouter({
    history: createWebHistory(), // createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: "/",
            name: "dashboard",
            component: () => import("@/views/HomeView.vue"),
            meta: {
                middleware: [AuthMiddleware],
            },
            children: [
                {
                    path: "",
                    component: () => RouterView,
                    children: [
                        {
                            path: "",
                            name: "home",
                            alias: "/home",
                            component: () => import("@/views/home/HomeView.vue"),                        
                        },
                        {
                            path: "employees",
                            name: "employees.index",
                            component: () => import("@/views/employees/IndexView.vue"),                        
                        },
                        {
                            path: "categories",
                            name: "categories.index",
                            component: () => import("@/views/categories/IndexView.vue"),                        
                        },
                    ]
                },
                
            ],
        },
        {
            path: "/auth/login",
            name: "auth.login",
            meta: {
                middleware: [RedirectAuthMiddeleware],
            },
            component: () => import("@/views/auth/LoginView.vue"),
        },
        {
            path: "/auth/forget-password",
            name: "auth.forget.password",
            meta: {
                middleware: [RedirectAuthMiddeleware],
            },
            component: () => import("@/views/auth/ForgetPasswordView.vue"),
        },
        {
            path: "/auth/reset-password/:token",
            name: "auth.reset.password",
            meta: {
                middleware: [RedirectAuthMiddeleware],
            },
            component: () => import("@/views/auth/ResetPasswordView.vue"),
        },
    ],
});

router.beforeEach(async (to, from, next) => {
    if (!to.meta.middleware) {
        return next();
    }

    // Get all middlewares from matched routes
    const middleware = to.matched
        .map((route) => route.meta.middleware)
        .filter(Boolean)
        .flat();

    const context = {
        to,
        from,
        next,
        //   store  | You can also pass store as an argument
    };

    return await middleware[0]({
        ...context,
        // next: middlewarePipeline(context, middleware, 1),
    });
});

export default router;
