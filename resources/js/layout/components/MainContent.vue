<template>
    <main>
        <PageHeader v-if="activePageHeader" />
        <div v-show="!loading" class="content">
            <router-view></router-view>
        </div>
        <Loading v-if="loading" />
    </main>
</template>

<script>
    import PageHeader from "./PageHeader";
    import Loading from "../../components/Loading";

    export default {
        name: "MainContent",
        components: {
            PageHeader,
            Loading
        },
        data() {
            return {
                activePageHeader: this.$route.name !== 'dashboard'
            }
        },
        computed: {
            loading() {
                return this.$store.getters.isLoading;
            }
        },
        watch: {
            $route() {
                this.activePageHeader = this.$route.name !== 'dashboard';
            }
        }
    }
</script>

<style lang="scss">
    main {
        margin-top: 60px;

        padding: 0 15px;

        .page-header {
            background-color: #ffffff;
            margin: 0 -15px;
            padding: 15px;
            border-bottom: 1px solid #e7eaec;

            .breadcrumb {
                background-color: transparent;
                margin-bottom: 0;
                padding-left: 0;
                padding-bottom: 0;
            }
        }

        .content {
            margin-top: 1rem;

            section {
                margin-top: 1rem;
                background-color: #ffffff;
                padding: 15px;
                border: 1px solid #d3d8db;
                border-radius: 5px;
            }
        }


    }
</style>
