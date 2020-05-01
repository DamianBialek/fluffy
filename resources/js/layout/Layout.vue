<template>
    <div class="wrapper">
        <div class="sidebar">
            <ul class="list-group sidebar-navbar">
                <router-link exact-active-class="" v-for="(route, index) in routes" :key="index" tag="li" class="list-group-item" :to="{name: route.name}" active-class="active"><i :class="`mr-2 ${route.icon}`"></i>{{route.title}}</router-link>
            </ul>
        </div>
        <div class="page-content">
            <router-view></router-view>
        </div>
    </div>
</template>

<script lang="ts">
    import Vue from "vue"
    import Component from "vue-class-component"
    import { layoutRoutes } from "../router";

    @Component
    export default class Layout extends Vue {
        modulesIcons: { [key: string]: string } = {
            dashboard: 'fas fa-home',
            calendar: 'fas fa-calendar-alt',
            tasks: 'fas fa-list-ol',
            customers: 'fas fa-users',
            mechanics: 'fas fa-users-cog',
            configuration: 'fas fa-cogs',
        }

        routes: AppRouteConfig[] = []

        mounted(): void {
            Object.keys(layoutRoutes).forEach(module => {
                const route = layoutRoutes[module];

                if(this.modulesIcons.hasOwnProperty(module)) {
                    route['icon'] = this.modulesIcons[module];
                }

                this.routes.push(route);
            });

            const modulesOrder = Object.keys(this.modulesIcons);

            this.routes = this.routes.sort((a, b) => modulesOrder.indexOf(a.name) - modulesOrder.indexOf(b.name))
        }
    }
</script>

<style lang="scss">
    @import '@scss/_variables';

    .wrapper {
        display: flex;
        flex-wrap: wrap;
    }

    .sidebar {
        background-color: $dark;
        min-height: 100vh;
        width: 220px;

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

    .page-content {
        width: calc(100% - 220px);
        padding: 0 15px;
        background-color: #f3f3f4;
        color: $black;

        .page-wrapper {
            background-color: #fff;
            margin-top: 1rem;
            padding: 0 15px;
        }
    }
</style>
