<template>
    <div :class="['wrapper', {'mini-navbar': miniNavbar}]">
        <Sidebar :routes="routes" />
        <div class="page-content">
            <header>
                <nav class="navbar" role="navigation">
                    <div class="btn bars-btn" @click="miniNavbar = !miniNavbar">
                        <i class="fa fa-bars"></i>
                    </div>
                </nav>
            </header>
            <main>
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
                routes: []
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

            this.routes = this.routes.sort((a, b) => modulesOrder.indexOf(a.name) - modulesOrder.indexOf(b.name))
        },
        methods: {
            routeHasChildren(route) {
                return !!route.children;
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
    }
    .page-content {
        width: calc(100% - 220px);
        background-color: #f3f3f4;
        color: $black;
        transition: width .4s;
        main {
            padding: 0 15px;
        }
        .page-wrapper {
            background-color: #fff;
            margin-top: 1rem;
            padding: 0 15px;
        }
    }
</style>
