<template>
    <div class="page">
        <div class="my-3 text-right">
            <router-link tag="button" :to="{name: 'mechanicsAddMechanic'}" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Dodaj nowego mechanika</router-link>
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
        </section>
    </div>
</template>

<script>
    export default {
        name: "MechanicsList",
        data() {
            return {
                mechanics: []
            }
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
                        this.getMechanics();
                    })
                })
            },
            getMechanics() {
                this.setLoading(true);
                this.$api.get("/api/mechanics")
                    .then(res => {
                        this.mechanics = res.data.data.mechanics;
                        this.setLoading(false);
                    })
            }
        }
    }
</script>

<style>

</style>
