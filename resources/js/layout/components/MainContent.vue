<template>
    <main>
        <PageHeader v-if="activePageHeader" />
        <div v-show="!loading" class="content">
            <router-view></router-view>
        </div>
        <div v-if="loading" class="d-flex justify-content-center py-5">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </main>
</template>

<script>
    import PageHeader from "./PageHeader";
    export default {
        name: "MainContent",
        components: {PageHeader},
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
                background-color: #ffffff;
                padding: 15px;
                border: 1px solid #e7eaec;
            }
        }


    }
</style>
