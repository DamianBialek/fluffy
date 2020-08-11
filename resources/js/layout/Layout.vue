<template>
    <div :class="['wrapper', {'mini-navbar': miniNavbar}]">
        <Sidebar :routes="routes" />
        <div class="page-content">
            <Navbar @barsBtnClick="miniNavbar = !miniNavbar" />
            <MainContent />
        </div>
    </div>
</template>

<script>
    import Sidebar from './components/Sidebar'
    import {layoutRoutes} from "../router";
    import MainContent from "./components/MainContent";
    import Navbar from "./components/Navbar";

    export default {
        name: "Layout",
        components: {
            Navbar,
            MainContent,
            Sidebar
        },
        data() {
            return {
                miniNavbar: false,
                modulesIcons: {
                    dashboard: 'fas fa-home',
                    calendar: 'fas fa-calendar-alt',
                    orders: 'fas fa-list-ol',
                    customers: 'fas fa-users',
                    mechanics: 'fas fa-users-cog',
                    configuration: 'fas fa-cogs',
                },
                routes: []
            }
        },
        computed: {
            routeTitle() {
                return this.$route.meta.title;
            }
        },
        mounted() {
            Object.keys(layoutRoutes).forEach(module => {
                const route = layoutRoutes[module];

                if(this.modulesIcons.hasOwnProperty(module)) {
                    route['icon'] = this.modulesIcons[module];
                }

                this.routes.push(route);
            });

            const modulesOrder = Object.keys(this.modulesIcons);

            this.routes = this.routes.sort((a, b) => modulesOrder.indexOf(a.name) - modulesOrder.indexOf(b.name));
        },
        methods: {
            routeHasChildren(route) {
                return !!route.children;
            }
        }
    }
</script>

<style lang="scss">
    .wrapper {
        display: flex;
        flex-wrap: wrap;
    }

    .mini-navbar {
        .sidebar {
            width: 70px;
        }

        .page-content {
            width: calc(100% - 70px);
        }
    }

    .sidebar {
        background-color: $dark;
        min-height: 100vh;
        width: 220px;
        transition: width .4s;
    }

    .navbar {
        background-color: $dark;

        .bars-btn {
            border: 1px solid #A7B1C2;
            color: #A7B1C2;

            &:hover {
                background-color: $black;
                color: #ffffff;
            }
        }

        .login-info {
            color: #ffffff;

            .logout {
                color: #A7B1C2;
                transition: color .2s;
                cursor: pointer;

                &:hover {
                    color: #ffffff;
                }
            }
        }
    }

    .page-content {
        width: calc(100% - 220px);
        background-color: #f3f3f4;
        color: $black;
        transition: width .4s;

        .page-wrapper {
            background-color: #fff;
            margin-top: 1rem;
            padding: 0 15px;
        }
    }
</style>
