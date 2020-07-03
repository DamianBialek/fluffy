<template>
    <div class="page">
        <div class="my-3 text-right">
            <router-link tag="button" :to="{name: 'customersAddCustomer'}" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Dodaj nowego klienta</router-link>
        </div>
        <section>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="customer in customers" :key="customer.id">
                        <th scope="row">{{customer.id}}</th>
                        <td>{{customer.name}}</td>
                        <td>{{customer.surname}}</td>
                        <td class="text-center">
                            <router-link tag="button" :to="{name: 'customersEditCustomer', params: {id: customer.id}}" class="btn btn-outline-secondary m-1"><i class="fas fa-user-edit"></i></router-link>
                            <button class="btn btn-outline-danger m-1" @click="deleteCustomer(customer)"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</template>

<script>
    import swal from "sweetalert";

    export default {
        name: "CustomersList",
        data() {
            return {
                customers: []
            }
        },
        mounted() {
            this.getCustomers();
        },
        methods: {
            deleteCustomer(customer) {
                this.$confirm(`Czy na pewno chcesz usunać tego mechanika ${customer.name} ${customer.surname} ?`)
                    .then(() => {
                        this.$api.delete(`/api/customers/${customer.id}`)
                            .then(() => {
                                swal("Pomyślnie usunięto klienta !", "", "success").then(() => {
                                    this.getCustomers();
                                })
                            })
                    })
            },
            getCustomers() {
                this.setLoading(true);
                this.$api.get("/api/customers")
                    .then(res => {
                        this.customers = res.data.data.customers;
                        this.setLoading(false);
                    })
            }
        }
    }
</script>

<style scoped>

</style>
