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
                            <button @click="resetSearchQuery" type="button" class="btn btn-outline-secondary"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="input-group-append">
                            <button @click="getCustomers" type="button" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
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
                            <router-link tag="button" :to="{name: 'customersEditCustomer', params: {id: customer.id}}" class="btn btn-outline-secondary m-1"><i class="fas fa-user-edit"></i></router-link>
                            <button class="btn btn-outline-danger m-1" @click="deleteCustomer(customer)"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-3 pb-3 d-flex justify-content-center">
<!--                <Pagination :pagination="paginationData" @paginate="getCustomers()" :offset="2"></Pagination>-->
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        name: "OrdersList"
    }
</script>

<style>

</style>
