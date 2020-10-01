<template>
    <div class="page">
        <div class="my-3 text-right">
            <router-link v-tooltip="'Powrót'" tag="button" :to="{name: 'customersList'}" class="btn btn-info"><i class="fas fa-level-up-alt"></i></router-link>
        </div>
        <section>
            <CustomerForm @submit="saveCustomer" @unassignCustomerVehicle="unassignCustomerVehicle" @assignCustomerVehicle="assignCustomerVehicle" :customer="this.customer" :error-fields="errorFields" />
        </section>
    </div>
</template>

<script>
    import CustomerForm from "./components/CustomerForm";

    export default {
        name: "CustomersAddCustomer",
        components: {
            CustomerForm
        },
        data() {
            return {
                customer: {
                    vehicles: []
                },
                errorFields: {}
            }
        },
        computed: {
            vehicles() {
                return this.$store.getters.unassignedCustomerVehicles;
            }
        },
        methods: {
            saveCustomer() {
                this.errorFields = {};
                this.setLoading(true);
                this.$api.post("/api/customers", this.customer)
                    .then(res => {
                        if(res.data.success) {
                            this.$notify("Pomyślnie dodano nowego klienta !", "", "success").then(() => {
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
                            this.$notify("Proszę poprawić błędy w formularzu !", "", "error");
                        } else {
                            this.$notify("Wystąpił błąd podczas dodawania nowego klienta !", "", "error");
                        }
                    })
                    .finally(() => {
                        this.setLoading(false);
                    })
            },
            unassignCustomerVehicle(vehicle) {
                this.customer.vehicles.splice(this.customer.vehicles.findIndex(v => v.id === vehicle.id), 1);
            },
            assignCustomerVehicle(vehicle) {
                if(!this.customer.vehicles) {
                    this.customer.vehicles= [];
                }

                this.customer.vehicles.push(vehicle);
                this.$store.commit("removeUnassignedCustomerVehicles", vehicle.id);
            }
        }
    }
</script>

<style scoped>

</style>
