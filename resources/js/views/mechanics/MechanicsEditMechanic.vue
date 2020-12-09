<template>
    <div class="page">
        <div class="my-3 text-right">
            <router-link v-tooltip="'Powrót'" tag="button" :to="{name: 'mechanicsList'}" class="btn btn-info"><i class="fas fa-level-up-alt"></i></router-link>
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
            this.setLoading(true);
            this.$api.get(`/api/mechanics/${this.$route.params.id}`).then(res => {
                this.mechanic = res.data.data.mechanic;
                this.setLoading(false);
            })
        },
        methods: {
            saveMechanic() {
                this.errorFields = {};
                this.setLoading(true);
                this.$api.put(`/api/mechanics/${this.mechanic.id}`, this.mechanic)
                    .then(res => {
                        if(res.data.success) {
                            this.$notify("Pomyślnie zaktualizowano dane !", "", "success").then(() => {
                                this.$router.push({name: 'mechanicsList'})
                            })
                        }
                    })
                    .catch(err => {
                        if(err.response.status !== 422) {
                            return;
                        }

                        this.errorFields = err.response.data.data.fields;

                        if(Object.keys(this.errorFields).length) {
                            this.$notify("Proszę poprawić błędy w formularzu !", "", "error");
                        } else {
                            this.$notify("Wystąpił błąd podczas zapisywania danych !", "", "error");
                        }
                    })
                    .finally(() => {
                        this.setLoading(false);
                    })
            }
        }
    }
</script>

<style scoped>

</style>
