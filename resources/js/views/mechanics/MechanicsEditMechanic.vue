<template>
    <div class="page">
        <div class="my-3 text-right">
            <router-link tag="button" :to="{name: 'mechanicsList'}" class="btn btn-info"><i class="fas fa-level-up-alt"></i></router-link>
        </div>
        <section>
            <MechanicForm @submit="saveMechanic" :mechanic="this.mechanic" :error-fields="errorFields" />
        </section>
    </div>
</template>

<script>
    import MechanicForm from "./components/MechanicForm";

    export default {
        name: "MechanicsEditMechanic",
        components: {MechanicForm},
        data() {
            return {
                mechanic: {
                    name: '',
                    surname: ''
                },
                errorFields: {}
            }
        },
        mounted() {
            this.$api.get(`/api/mechanics/${this.$route.params.id}`).then(res => {
                this.mechanic = res.data.data.mechanic;
            })
        },
        methods: {
            saveMechanic() {
                this.errorFields = {};
                this.$api.put(`/api/mechanics/${this.mechanic.id}`, this.mechanic)
                    .then(res => {
                        if(res.data.success) {
                            this.$router.push({name: 'mechanicsList'})
                        }
                    })
                    .catch(err => {
                        if(err.response.status !== 422) {
                            return;
                        }

                        this.errorFields = err.response.data.data.fields;
                    })
            }
        }
    }
</script>

<style scoped>

</style>
