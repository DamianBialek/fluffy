<template>
    <div class="page">
        <div class="my-3 text-right">
            <router-link tag="button" :to="{name: 'customersCarsAdd'}" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Dodaj nowy samochód</router-link>
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
                            <button @click="getVehicles" type="button" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th>Numer rejestarcyjny</th>
                        <th>Marka i model (rok produkcji)</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="vehicle in vehicles" :key="vehicle.id">
                        <th scope="row">{{vehicle.id}}</th>
                        <td>{{vehicle.registration_number}}</td>
                        <td>{{`${vehicle.mark} ${vehicle.model} (${vehicle.production_year})`}}</td>
                        <td class="text-center">
                            <router-link tag="button" :to="{name: 'customersCarsEdit', params: {id: vehicle.id}}" class="btn btn-outline-secondary m-1"><i class="fas fa-user-edit"></i></router-link>
                            <button class="btn btn-outline-danger m-1" @click="deleteVehicle(vehicle)"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-3 pb-3 d-flex justify-content-center">
                <Pagination :pagination="paginationData" @paginate="getVehicles()" :offset="2"></Pagination>
            </div>
        </section>
    </div>
</template>

<script>
    import Pagination from "../../components/Pagination";

    export default {
        name: "CustomersVehiclesList",
        data() {
            return {
                vehicles: [],
                paginationData: {},
                searchQuery: ''
            }
        },
        components: {
            Pagination
        },
        mounted() {
            this.getVehicles();
        },
        methods: {
            deleteVehicle(vehicle) {
                this.$confirm(`Czy na pewno chcesz usunać ten samochód ${vehicle.mark} ${vehicle.model} (#${vehicle.id}) ?`)
                    .then(() => {
                        this.$api.delete(`/api/vehicles/${vehicle.id}`)
                            .then(() => {
                                this.$notify("Pomyślnie usunięto samochód !", "", "success").then(() => {
                                    this.getVehicles();
                                })
                            })
                    })
            },
            getVehicles() {
                this.setLoading(true);
                this.$api.get("/api/vehicles", { params: {page: this.paginationData.current_page, query: this.searchQuery} })
                    .then(res => {
                        this.vehicles = res.data.data.vehicles;
                        this.paginationData = res.data.data.pagination;
                        this.setLoading(false);
                    })
            },
            onSearchInputKeyUp(e) {
                if (e instanceof KeyboardEvent && e.code === 'Enter') {
                    this.getVehicles();
                }
            },
            resetSearchQuery() {
                this.searchQuery = '';
                this.getVehicles();
            }
        }
    }
</script>

<style scoped>

</style>
