<template>
    <router-link active-class="active" tag="div" class="list-group-item" exact-active-class="" v-if="!hasChildren" v-html="routeTitle" :to="{name: route.name}"></router-link>
    <div v-else :class="['list-group-root', 'list-group-item', 'flex-column', {'expanded': expanded}]">
        <div class="d-flex align-items-center w-100 route-title-box" v-html="routeTitle" :data-target="`#${route.name}-children`" data-toggle="collapse" :data-expanded="expanded"></div>
        <div :class="['list-group', 'route-list-children', 'collapse', {'show': expanded}]" :id="`${route.name}-children`">
            <SidebarItem v-for="(route, index) in routeChildrenSidebar" :key="index" :route="route" />
        </div>
    </div>
</template>

<script>
    export default {
        name: "SidebarItem",
        props: {
            route: Object,
            isExpanded: Boolean
        },
        data() {
            return {
                expanded: this.isExpanded
            }
        },
        computed: {
            hasChildren() {
                return !!this.route.children && this.route.children.some(child => child.meta.inSidebar == null ? false : child.meta.inSidebar);
            },
            routeTitle() {
                let html = '<span class="route-info">';

                if(this.route.icon) {
                    html += `<i class="\mr-2 ${this.route.icon}\"></i>`;
                }

                html += `<span class="route-title">${this.route.meta.title}</span>`;

                html += '</span>';

                if(this.hasChildren) {
                    const expandedIcon = this.expanded ? 'fa-chevron-up' : 'fa-chevron-down';
                    html += `<i class="fas collapse-icon ${expandedIcon}"></i>`
                }

                return html;
            },
            routeChildrenSidebar() {
                return this.route.children.filter(child => child.meta.inSidebar == null ? false : child.meta.inSidebar)
            }
        },
        mounted() {
            if(this.hasChildren) {
                const vue = this;
                $(`#${this.route.name}-children`).on('hidden.bs.collapse', function () {
                    vue.expanded = false;
                }).on('shown.bs.collapse', function () {
                    vue.expanded = true;
                })
            }
        },
    }
</script>

<style>

</style>
