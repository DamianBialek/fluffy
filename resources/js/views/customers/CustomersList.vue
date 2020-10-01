<template>
    <div class="page">
        <div class="my-3 text-right">
            <router-link tag="button" :to="{name: 'customersAddCustomer'}" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Dodaj nowego klienta</router-link>
        </div>
        <section>
            <div class="d-flex justify-content-center">
                <div class="col-8">
                    <div class="input-group">
                        <input @keyup="onSearchInputKeyUp" v-model="searchQuery" aria-label="Szukaj samochodu" type="text" class="form-control" />
                        <div class="input-group-append" v-show="searchQuery.length">
                            <button v-tooltip="'Resetuj'" @click="resetSearchQuery" type="button" class="btn btn-outline-secondary"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="input-group-append">
                            <button v-tooltip="'Szukaj'" @click="getCustomers" type="button" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-3">
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
                            <router-link v-tooltip="'Edytuj'" tag="button" :to="{name: 'customersEditCustomer', params: {id: customer.id}}" class="btn btn-outline-secondary m-1"><i class="fas fa-user-edit"></i></router-link>
                            <button v-tooltip="'Usuń'" class="btn btn-outline-danger m-1" @click="deleteCustomer(customer)"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-3 pb-3 d-flex justify-content-center">
                <Pagination :pagination="paginationData" @paginate="getCustomers()" :offset="2"></Pagination>
            </div>
        </section>
    </div>
</template>

<script>
    import Pagination from "../../components/Pagination";

    export default {
        name: "CustomersList",
        data() {
            return {
                customers: [],
                paginationData: {},
                searchQuery: ''
            }
        },
        components: {
            Pagination
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
                                this.$notify("Pomyślnie usunięto klienta !", "", "success").then(() => {
                                    this.getCustomers();
                                })
                            })
                    })
            },
            getCustomers() {
                this.setLoading(true);
                this.$api.get("/api/customers", { params: {page: this.paginationData.current_page, query: this.searchQuery} })
                    .then(res => {
                        this.customers = res.data.data.customers;
                        this.paginationData = res.data.data.pagination;
                        this.setLoading(false);
                    })
            },
            onSearchInputKeyUp(e) {
                if (e instanceof KeyboardEvent && e.code === 'Enter') {
                    this.getCustomers();
                }
            },
            resetSearchQuery() {
                this.searchQuery = '';
                this.getCustomers();
            }
        }
    }
</script>

<style scoped>

</style>
