<template>
    <div class="page">
        <div class="my-3 text-right">
            <router-link v-tooltip="'Powrót'" tag="button" :to="{name: 'customersCarsList'}" class="btn btn-info"><i class="fas fa-level-up-alt"></i></router-link>
        </div>
        <section>
            <CustomerVehicleForm @submit="saveVehicle" :vehicle="this.vehicle" :error-fields="errorFields" />
        </section>
    </div>
</template>

<script>
    import CustomerVehicleForm from "./components/CustomersVehicleForm";

    export default {
        name: "CustomersVehiclesEdit",
        components: {
            CustomerVehicleForm
        },
        data() {
            return {
                vehicle: {},
                errorFields: {}
            }
        },
        mounted() {
            this.setLoading(true);
            this.$api.get(`/api/vehicles/${this.$route.params.id}`).then(res => {
                this.vehicle = res.data.data.vehicle;
                this.setLoading(false);
            })
        },
        methods: {
            saveVehicle() {
                this.errorFields = {};
                this.setLoading(true);
                this.$api.put(`/api/vehicles/${this.vehicle.id}`, this.vehicle)
                    .then(res => {
                        if(res.data.success) {
                            this.$notify("Pomyślnie zaaktulizowano dane !", "", "success").then(() => {
                                this.$router.push({name: 'customersCarsList'})
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
                            this.$notify("Wystąpił błąd podczas edytowania danych pojadu !", "", "error");
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
