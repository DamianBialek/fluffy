<template>
    <div class="page">
        <div class="my-3 text-right">
            <router-link tag="button" :to="{name: 'customersList'}" class="btn btn-info"><i class="fas fa-level-up-alt"></i></router-link>
        </div>
        <section>
            <CustomerForm @submit="saveCustomer" @unassignCustomerVehicle="unassignCustomerVehicle" @assignCustomerVehicle="assignCustomerVehicle" :customer="this.customer" :error-fields="errorFields" />
        </section>
    </div>
</template>

<script>
    import CustomerForm from "./components/CustomerForm";
    import swal from "sweetalert";

    export default {
        name: "CustomersEditCustomer",
        components: {
            CustomerForm
        },
        data() {
            return {
                customer: {},
                errorFields: {}
            }
        },
        computed: {
            vehicles() {
                return this.$store.getters.unassignedCustomerVehicles;
            }
        },
        mounted() {
            this.setLoading(true);
            this.$api.get(`/api/customers/${this.$route.params.id}`).then(res => {
                this.customer = res.data.data.customer;
                this.setLoading(false);
            })
        },
        methods: {
            saveCustomer() {
                this.errorFields = {};
                this.setLoading(true);
                this.$api.put(`/api/customers/${this.customer.id}`, this.customer)
                    .then(res => {
                        if(res.data.success) {
                            swal("Pomyślnie zaaktulizowano dane !", "", "success").then(() => {
                                this.$router.push({name: 'customersList'})
                            })
                        }
                    })
                    .catch(err => {
                        if(err.response.status !== 422) {
                            return;
                        }

                        this.errorFields = err.response.data.data.fields;

                        if(Object.keys(this.errorFields).length) {
                            swal("Proszę poprawić błędy w formularzu !", "", "error");
                        } else {
                            swal("Wystąpił błąd podczas dodawania nowego klienta !", "", "error");
                        }
                    })
                    .finally(() => {
                        this.setLoading(false);
                    })
            },
            unassignCustomerVehicle(vehicle) {
                vehicle = Object.assign(vehicle, {
                    customer_id: null
                })

                this.updateCustomerVehicleData(vehicle)
                    .then(res => {
                        if(res.data.success) {
                            swal("Pomyślnie zaaktulizowano dane !", "", "success").then(() => {
                                this.customer.vehicles.splice(this.customer.vehicles.findIndex(v => v.id === vehicle.id), 1);
                            })
                        }
                    })
            },
            assignCustomerVehicle(vehicle) {
                vehicle = Object.assign(vehicle, {
                    customer_id: this.customer.id
                })

                this.updateCustomerVehicleData(vehicle)
                    .then(res => {
                        if(res.data.success) {
                            swal("Pomyślnie zaaktulizowano dane !", "", "success").then(() => {
                                this.customer.vehicles.push(vehicle);
                                this.$store.commit("removeUnassignedCustomerVehicles", vehicle.id);
                            })
                        }
                    })
            },
            updateCustomerVehicleData(vehicle) {
                return this.$api.put(`/api/vehicles/${vehicle.id}`, vehicle);
            }
        }
    }
</script>

<style scoped>

</style>
