<template>
    <div class="sidebar">
        <div class="list-group sidebar-navbar list-group-root">
            <template v-for="(route, index) in routes">
                <Link :route="route" :hasChildren="routeHasChildren(route)" />
                <div v-if="routeHasChildren(route)" class="list-group collapse" :id="`${route.name}-children`">
                    <Link v-for="(routeChild, indexChild) in route.children" :key="indexChild" :route="routeChild" />
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import Link from "./Link";

    export default {
        name: "Sidebar",
        props: {
            routes: {
                default: () => []
            }
        },
        components: {
            Link
        },
        methods: {
            routeHasChildren(route) {
                return !!route.children;
            }
        },
        watch: {
            $route() {
                const parentRoute = this.$route.matched[this.$route.matched.length - 2];

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
        },
    }
</script>

<style lang="scss">
    @import '@scss/_variables';

    .sidebar {
        background-color: $dark;
        min-height: 100vh;
        width: 220px;
        transition: width .4s;

        .list-group.list-group-root {
            padding: 0;
            overflow: hidden;
        }

        .list-group.list-group-root .list-group {
            margin-bottom: 0;
        }

        .list-group.list-group-root .list-group-item {
            border-radius: 0;
            border-width: 1px 0 0 0;
        }

        .list-group.list-group-root > .list-group-item:first-child {
            border-top-width: 0;
        }

        .list-group.list-group-root > .list-group > .list-group-item {
            padding-left: 40px;
        }

        .sidebar-navbar {
            margin-top: 100px;

            .list-group-item {
                background-color: transparent;
                border-color: $mediumDark;
                transition: background-color .2s;
                color: #A7B1C2;

                &.active {
                    border-left: 4px solid $turquoise;

                    i {
                        margin-left: -4px;
                    }
                }

                &:not(.active) {
                    cursor: pointer;
                }

                &:hover, &.active {
                    background-color: $black;
                    text-decoration: none;
                    color: #ffffff;
                }
            }
        }
    }


</style>
