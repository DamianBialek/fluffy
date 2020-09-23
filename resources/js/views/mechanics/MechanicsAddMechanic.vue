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
        name: "MechanicsAddMechanic",
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
        methods: {
            saveMechanic() {
                this.errorFields = {};
                this.setLoading(true);
                this.$api.post("/api/mechanics", this.mechanic)
                .then(res => {
                    if(res.data.success) {
                        this.$notify("Pomyślnie dodano nowego mechanika !", "", "success").then(() => {
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
                        this.$notify("Wystąpił błąd podczas dodawania nowego mechanika !", "", "error");
                    }
                })
                .finally(() => {
                    this.setLoading(false);
                })
            }
        }
    }
</script>

<style>

</style>
