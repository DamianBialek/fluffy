<template>
    <div :class="['wrapper', {'mini-navbar': miniNavbar}]">
        <Sidebar :routes="routes" :class="{'show': miniNavbar}" />
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
        },
        watch: {
            miniNavbar() {
                if(window.innerWidth < 768) {
                    if(this.miniNavbar) {
                        document.body.classList.add("overflow-hidden");
                    } else {
                        document.body.classList.remove("overflow-hidden");
                    }
                }
            },
            $route() {
                this.miniNavbar = false;
            }
        }
    }
</script>

<style lang="scss">
    .wrapper {
        display: flex;
        flex-wrap: wrap;
    }

    .sidebar {
        background-color: $dark;
        height: 100%;
        display: none;
        position: fixed;
        overflow: auto;
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
        background-color: #f3f3f4;
        color: $black;
        width: 100%;

        .page-wrapper {
            background-color: #fff;
            margin-top: 1rem;
            padding: 0 15px;
        }

        > header {
            border-bottom: 1px solid #889AA4;

            .navbar {
                height: 60px;
                flex-wrap: nowrap;
            }
        }
    }

    @media all and (min-width: 768px) {
        .mini-navbar {
            .sidebar {
                width: 70px;
            }

            .page-content, .page-content > header {
                margin-left: 70px;
            }
        }

        .sidebar {
            display: block;
            width: 220px;
            top: 0;
            left: 0;
            overflow: unset;
            z-index: 2;
        }

        .page-content, .page-content > header {
            margin-left: 220px;
            transition: margin-left .4s;
        }
    }

    @media all and (max-width: 767.98px) {
        .sidebar {
            &.show {
                display: block;
                width: 100%;
                position: absolute;
                margin-top: 60px;
                overflow-y: auto;
                z-index: 1029;
            }
        }
    }
</style>
