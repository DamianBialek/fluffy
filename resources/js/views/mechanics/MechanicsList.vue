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
                            <button class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
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
            this.$api.get("/api/mechanics")
            .then(res => {
                this.mechanics = res.data.data.mechanics;
            })
        }
    }
</script>

<style>

</style>
