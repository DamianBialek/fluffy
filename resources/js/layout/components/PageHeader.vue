<template>
    <header class="page-header">
        <h2>{{routeTitle}}</h2>
        <ol class="breadcrumb">
            <li :class="['breadcrumb-item', {'active': index === breadcrumbs.length-1}]" v-for="(breadcrumb, index) in breadcrumbs" :key="index">
                <router-link v-if="index !== breadcrumbs.length-1" tag="a" :to="{name: breadcrumb.name}" v-html="breadcrumb.title"></router-link>
                <strong v-else v-html="breadcrumb.title"></strong>
            </li>
        </ol>
    </header>
</template>

<script>
    export default {
        name: "PageHeader",
        data() {
            return {
                breadcrumbs: []
            }
        },
        computed: {
            routeTitle() {
                return this.$route.meta.title;
            }
        },
        mounted() {
            this.updateBreadcrumbs();
        },
        methods: {
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
            }
        }
    }
</script>

<style scoped>

</style>
