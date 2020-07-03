<template>
    <div class="page">
        <div class="my-3 text-right">
            <router-link tag="button" :to="{name: 'customersList'}" class="btn btn-info"><i class="fas fa-level-up-alt"></i></router-link>
        </div>
        <section>
            <CustomerForm @submit="saveCustomer" :customer="this.customer" :error-fields="errorFields" />
        </section>
    </div>
</template>

<script>
    import CustomerForm from "./components/CustomerForm";
    import swal from "sweetalert";

    export default {
        name: "CustomersAddCustomer",
        components: {
            CustomerForm
        },
        data() {
            return {
                customer: {},
                errorFields: {}
            }
        },
        methods: {
            saveCustomer() {
                this.errorFields = {};
                this.setLoading(true);
                this.$api.post("/api/customers", this.customer)
                    .then(res => {
                        if(res.data.success) {
                            swal("Pomyślnie dodano nowego klienta !", "", "success").then(() => {
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
            }
        }
    }
</script>

<style scoped>

</style>
