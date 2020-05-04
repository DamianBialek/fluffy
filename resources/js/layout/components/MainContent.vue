<template>
    <main>
        <PageHeader v-if="activePageHeader" />
        <transition name="fade">
            <div class="content">
                <router-view></router-view>
            </div>
        </transition>
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
