<template>
    <div class="page">
        <div class="my-3 text-right">
            <router-link tag="button" :to="{name: 'mechanicsAddMechanic'}" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Dodaj nowego mechanika</router-link>
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
                            <button @click="getMechanics" type="button" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
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
                    <tr v-for="mechanic in mechanics" :key="mechanic.id">
                        <th scope="row">{{mechanic.id}}</th>
                        <td>{{mechanic.name}}</td>
                        <td>{{mechanic.surname}}</td>
                        <td class="text-center">
                            <router-link tag="button" :to="{name: 'mechanicsEditMechanic', params: {id: mechanic.id}}" class="btn btn-outline-secondary m-1"><i class="fas fa-user-edit"></i></router-link>
                            <button class="btn btn-outline-danger m-1" @click="deleteMechanic(mechanic)"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-3 pb-3 d-flex justify-content-center">
                <Pagination :pagination="paginationData" @paginate="getMechanics()" :offset="2"></Pagination>
            </div>
        </section>
    </div>
</template>

<script>
    import swal from "sweetalert";
    import Pagination from "../../components/Pagination";

    export default {
        name: "MechanicsList",
        data() {
            return {
                mechanics: [],
                paginationData: {},
                searchQuery: ''
            }
        },
        components: {
            Pagination
        },
        mounted() {
            this.getMechanics();
        },
        methods: {
            deleteMechanic(mechanic) {
                this.$confirm(`Czy na pewno chcesz usunać tego mechanika ${mechanic.name} ${mechanic.surname} ?`)
                .then(() => {
                    this.$api.delete(`/api/mechanics/${mechanic.id}`)
                    .then(() => {
                        swal("Pomyślnie usunięto mechanika !", "", "success").then(() => {
                            this.getMechanics();
                        })
                    })
                })
            },
            getMechanics() {
                this.setLoading(true);
                this.$api.get("/api/mechanics", { params: {page: this.paginationData.current_page, query: this.searchQuery} })
                    .then(res => {
                        this.mechanics = res.data.data.mechanics;
                        this.paginationData = res.data.data.pagination;
                        this.setLoading(false);
                    })
            },
            onSearchInputKeyUp(e) {
                if (e instanceof KeyboardEvent && e.code === 'Enter') {
                    this.getMechanics();
                }
            },
            resetSearchQuery() {
                this.searchQuery = '';
                this.getMechanics();
            }
        }
    }
</script>

<style>

</style>
