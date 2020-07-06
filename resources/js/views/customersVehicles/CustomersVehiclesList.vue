<template>
    <div class="page">
        <div class="my-3 text-right">
            <router-link tag="button" :to="{name: 'customersCarsAdd'}" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Dodaj nowy samochód</router-link>
        </div>
        <section>
            <div class="table-responsive">
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
        </section>
    </div>
</template>

<script>
    import swal from "sweetalert";

    export default {
        name: "CustomersVehiclesList",
        data() {
            return {
                vehicles: []
            }
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
                                swal("Pomyślnie usunięto samochód !", "", "success").then(() => {
                                    this.getVehicles();
                                })
                            })
                    })
            },
            getVehicles() {
                this.setLoading(true);
                this.$api.get("/api/vehicles")
                    .then(res => {
                        this.vehicles = res.data.data.vehicles;
                        this.setLoading(false);
                    })
            }
        }
    }
</script>

<style scoped>

</style>
