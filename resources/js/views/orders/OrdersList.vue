<template>
    <div class="page">
        <div class="my-3 text-right">
            <router-link tag="button" :to="{name: 'ordersAdd'}" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Dodaj nowe zlecenie</router-link>
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
                            <button @click="getOrders" type="button" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th>Rejestracja samochodu</th>
                        <th>Nazwa</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="order in orders" :key="order.id">
                        <th scope="row">{{order.id}}</th>
                        <td>{{order.vehicle ? `${order.vehicle.mark} ${order.vehicle.model} (${order.vehicle.registration_number})` : ''}}</td>
                        <td>{{order.name && order.name.length ? order.name : '---'}}</td>
                        <td class="text-center">
                            <router-link tag="button" :to="{name: 'ordersEdit', params: {id: order.id}}" class="btn btn-outline-secondary m-1"><i class="fas fa-user-edit"></i></router-link>
                            <button class="btn btn-outline-danger m-1" @click="deleteOrder(order)"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    <tr v-if="!orders.length">
                        <td colspan="4" class="text-center"><strong>Brak zleceń</strong></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-3 pb-3 d-flex justify-content-center">
                <Pagination :pagination="paginationData" @paginate="getOrders()" :offset="2"></Pagination>
            </div>
        </section>
    </div>
</template>

<script>
    import swal from "sweetalert";
    import Pagination from "../../components/Pagination";

    export default {
        name: "OrdersList",
        data() {
            return {
                orders: [],
                paginationData: {},
                searchQuery: ''
            }
        },
        components: {
            Pagination
        },
        mounted() {
            this.getOrders();
        },
        methods: {
            deleteOrder(order) {
                this.$confirm(`Czy na pewno chcesz usunać to zlecenie ${order.name} (#${order.id}) ?`)
                    .then(() => {
                        this.$api.delete(`/api/orders/${order.id}`)
                            .then(() => {
                                swal("Pomyślnie usunięto zlecenie !", "", "success").then(() => {
                                    this.getOrders();
                                })
                            })
                    })
            },
            getOrders() {
                this.setLoading(true);
                this.$api.get("/api/orders", { params: {page: this.paginationData.current_page, query: this.searchQuery} })
                    .then(res => {
                        this.orders = res.data.data.orders;
                        this.paginationData = res.data.data.pagination;
                        this.setLoading(false);
                    })
            },
            onSearchInputKeyUp(e) {
                if (e instanceof KeyboardEvent && e.code === 'Enter') {
                    this.getOrders();
                }
            },
            resetSearchQuery() {
                this.searchQuery = '';
                this.getOrders();
            }
        }
    }
</script>

<style>

</style>
