<template>
    <div class="sidebar">
        <div class="list-group sidebar-navbar list-group-root">
            <SidebarItem v-for="(route, index) in routes" :key="index" :route="route" :is-expanded="isRouteExpanded(route)" />
        </div>
    </div>
</template>

<script>
import SidebarItem from "./SidebarItem";

export default {
        name: "Sidebar",
        components: {SidebarItem},
        props: {
            routes: {
                default: () => []
            }
        },
        methods: {
            getCurrentMatchedRouteParentRoute() {
                return this.$route.matched.find(matchedRoute => matchedRoute.parent && matchedRoute.parent.name === 'start');
            },
            isRouteExpanded(route) {
                const parentMatchedRoute = this.getCurrentMatchedRouteParentRoute();
                const parentRoute = this.routes.find(route => route.name === parentMatchedRoute.name);

                if(!parentRoute) {
                    return false;
                }

                return route.name === parentRoute.name;
            }
        },
        watch: {
            $route() {
                const parentRoute = this.getCurrentMatchedRouteParentRoute();

                if(parentRoute) {
                    const parentRouteName = parentRoute.name;

                    document.querySelectorAll(".collapse.show").forEach(node => {
                        const parentNodeName = node.id.replace("-children", "");
                        if(parentNodeName !== parentRouteName) {
                            $(`#${node.id}`).collapse("hide");
                        }
                    })
                }
            }
        }
    }
</script>

<style lang="scss">
    .mini-navbar {
        .sidebar {
            .sidebar-navbar {
                .list-group-item {
                    text-align: center;
                    justify-content: center;

                    .route-title-box {
                        justify-content: center;
                    }

                    i {
                        margin-right: 0 !important;
                    }

                    &.list-group-root {
                        position: relative;

                        .route-list-children {
                            position: absolute;
                            z-index: 2;
                            left: 70px;
                            background-color: $dark;
                            top: 0;
                            margin-top: 0;
                            min-width: 200px;
                            display: none;
                        }

                        &:hover {
                            .route-list-children {
                                display: flex;
                            }
                        }
                    }
                }

                > .list-group-item {
                    > .route-info, .route-title-box {
                        span.route-title, i.collapse-icon {
                            display: none;
                        }
                    }
                }
            }
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

                &.expanded {
                    background-color: $black;
                    color: #ffffff;
                }

                .route-title-box {
                    justify-content: space-between;
                }

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

        .list-group.list-group-root .list-group {
            margin-bottom: 0;
            margin-top: 12px;
        }

        .list-group-root .list-group-item {
            border-radius: 0;
            border-width: 1px 0 0 0;
        }

        .list-group.list-group-root > .list-group-item:first-child {
            border-top-width: 0;
        }

        .list-group.list-group-root > .list-group > .list-group-item {
            padding-left: 40px;
        }
    }
</style>
