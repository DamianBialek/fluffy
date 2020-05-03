<template>
    <div :class="['wrapper', {'mini-navbar': miniNavbar}]">
        <Sidebar :routes="routes" />
        <div class="page-content">
            <header>
                <nav class="navbar justify-content-between" role="navigation">
                    <div class="btn bars-btn" @click="miniNavbar = !miniNavbar">
                        <i class="fa fa-bars"></i>
                    </div>
                    <div class="col-auto login-info">
                        Zalogowano jako: <b>{{user.name}}</b><span class="ml-2 logout" @click="logout"><i class="fas fa-sign-out-alt mr-2"></i>Wyloguj</span>
                    </div>
                </nav>
            </header>
            <main>
                <header class="page-header" v-if="activePageHeader">
                    <h2>{{routeTitle}}</h2>
                    <ol class="breadcrumb">
                        <li :class="['breadcrumb-item', {'active': index === breadcrumbs.length-1}]" v-for="(breadcrumb, index) in breadcrumbs" :key="index">
                            <router-link v-if="index !== breadcrumbs.length-1" tag="a" :to="{name: breadcrumb.name}" v-html="breadcrumb.title"></router-link>
                            <strong v-else v-html="breadcrumb.title"></strong>
                        </li>
                    </ol>
                </header>
                <router-view></router-view>
            </main>
        </div>
    </div>
</template>

<script>
    import Sidebar from './sidebar/Sidebar'
    import {layoutRoutes} from "../router";

    export default {
        name: "Layout",
        components: {
            Sidebar
        },
        data() {
            return {
                miniNavbar: false,
                modulesIcons: {
                    dashboard: 'fas fa-home',
                    calendar: 'fas fa-calendar-alt',
                    tasks: 'fas fa-list-ol',
                    customers: 'fas fa-users',
                    mechanics: 'fas fa-users-cog',
                    configuration: 'fas fa-cogs',
                },
                routes: [],
                breadcrumbs: [],
                activePageHeader: this.$route.name !== 'dashboard'
            }
        },
        computed: {
            user() {
                return this.$store.getters.currentUser
            },
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

            this.updateBreadcrumbs();
        },
        methods: {
            routeHasChildren(route) {
                return !!route.children;
            },
            logout() {
                this.$store.dispatch("logout");
                this.$router.push({name: 'login'})
            },
            updateBreadcrumbs() {
                this.breadcrumbs = this.$route.matched.map(route => {
                    return {
                        title: route.meta.title,
                        name: route.name
                    }
                })
            }
        },
        watch: {
            $route() {
                this.updateBreadcrumbs();
                this.activePageHeader = this.$route.name !== 'dashboard';
            }
        }
    }
</script>

<style lang="scss">
    @import '@scss/_variables';
    .wrapper {
        display: flex;
        flex-wrap: wrap;
    }
    .mini-navbar {
        .sidebar {
            width: 70px;
            .list-group-item {
                text-align: center;
                i {
                    margin-right: 0!important;
                }
                span {
                    display: none;
                }
            }
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
        .sidebar-navbar {
            margin-top: 100px;
            .list-group-item {
                background-color: transparent;
                border-color: $mediumDark;
                transition: background-color .2s;
                color: #A7B1C2;
                &.active {
                    border-left: 4px solid $turquoise;
                    color: #ffffff;
                    i {
                        margin-left: -4px;
                    }
                }
                &:not(.active) {
                    cursor: pointer;
                }
                &:hover, &.active {
                    background-color: $black;
                }
            }
        }
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

        main {
            padding: 0 15px;

            .page-header {
                background-color: #ffffff;
                margin: 0 -15px;
                padding: 15px;

                .breadcrumb {
                    background-color: transparent;
                    margin-bottom: 0;
                    padding-left: 0;
                    padding-bottom: 0;
                }
            }
        }

        .page-wrapper {
            background-color: #fff;
            margin-top: 1rem;
            padding: 0 15px;
        }
    }
</style>
