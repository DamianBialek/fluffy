<template>
    <div class="page py-3">
        <section>
            <h1 class="text-center">Statystyki</h1>
            <div class="col mt-3">
                <div class="row">
                    <div class="col-3" v-for="count in counts" :key="count.name">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-center">{{ count.name }}</h5>
                                <h4 class="card-text text-center">{{count.value}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        name: "Dashboard",
        data() {
            return {
                counts: {
                    orders: {
                        name: "Zlecenia",
                        value: "Brak danych"
                    },
                    customers: {
                        name: "Klienci",
                        value: "Brak danych"
                    },
                    vehicles: {
                        name: "Pojazdy",
                        value: "Brak danych"
                    },
                    mechanics: {
                        name: "Mechanicy",
                        value: "Brak danych"
                    }
                }
            }
        },
        mounted() {
            this.setLoading(true);
            const all = Object.keys(this.counts).map(t => {
                    return this.$api.get(`/api/${t}/count`).then(res => {
                        this.counts[t].value = res.data.data.count;
                    });
                })

            Promise.all(all).then(() => {
                this.setLoading(false);
            })
        }
    }
</script>

<style>

</style>
