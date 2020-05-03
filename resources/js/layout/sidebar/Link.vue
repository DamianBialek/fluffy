<template>
    <router-link v-if="!hasChildren" exact-active-class="" tag="a" class="list-group-item" :to="{name: route.name}" active-class="active" v-html="routeTitle"></router-link>

    <a v-else class="list-group-item" :data-target="`#${route.name}-children`" data-toggle="collapse" data-expanded="false" v-html="routeTitle"></a>
</template>

<script>
    export default {
        name: "Link",
        props: {
            route: Object,
            hasChildren: Boolean
        },
        computed: {
            routeTitle() {
                let html = '';

                if(this.route.icon) {
                    html += `<i class="\mr-2 ${this.route.icon}\"></i>`;
                }

                html += `<span>${this.route.meta.title}</span>`;

                return html;
            }
        }
    }
</script>

<style scoped>

</style>
