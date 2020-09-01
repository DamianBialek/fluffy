<template>
    <div class="page">
        <div class="my-3 text-right">
            <router-link tag="button" :to="{name: 'ordersList'}" class="btn btn-info"><i class="fas fa-level-up-alt"></i></router-link>
        </div>
        <section>
            <OrderForm
                :order="order"
                :error-fields="errorFields"
                @submit="saveOrder"
                @saveNewPosition="saveNewPosition"
                @removePosition="removePosition"
                @saveEditedPosition="saveEditedPosition"
            />
        </section>
    </div>
</template>

<script>
    import OrderForm from "./components/OrderForm";
    import swal from "sweetalert";

    export default {
        name: "OrdersEdit",
        components: {
            OrderForm
        },
        data() {
            return {
                order: {
                    vehicle: {
                        mark: '',
                        model: '',
                        registration_number: ''
                    },
                    name: '',
                    positions: []
                },
                errorFields: {}
            }
        },
        mounted() {
            this.setLoading(true);
            this.$api.get(`/api/orders/${this.$route.params.id}`).then(res => {
                this.order = res.data.data.order;
                this.setLoading(false);
            })
        },
        methods: {
            saveOrder() {
                this.errorFields = {};
                this.setLoading(true);
                this.$api.put(`/api/orders/${this.order.id}`, this.order)
                    .then(res => {
                        if(res.data.success) {
                            swal("Pomyślnie zaaktulizowano dane !", "", "success").then(() => {
                                this.$router.push({name: 'ordersList'})
                            })
                        }
                    })
                    .catch(err => {
                        if(err.response.status !== 422) {
                            return;
                        }

                        this.errorFields = err.response.data.data.fields;

                        if(Object.keys(this.errorFields).length) {
                            swal("Proszę poprawić błędy w formularzu !", "", "error");
                        } else {
                            swal("Wystąpił błąd podczas aktualizacji danych !", "", "error");
                        }
                    })
                    .finally(() => {
                        this.setLoading(false);
                    })
            },
            saveNewPosition(data) {
                this.$api.post(`/api/orders/${this.order.id}/position`, data.newPosition).then(res => {
                    if(res.data.success && res.data.data.position) {
                        this.order.positions.push(res.data.data.position);
                        swal("Pomyślnie dodano pozycję !", "", "success").then(() => {
                            data.done();
                        })
                    }
                }).catch(err => {
                    if(err.response.status !== 422) {
                        return;
                    }

                    this.errorFields = err.response.data.data.fields;

                    if(Object.keys(this.errorFields).length) {
                        swal("Proszę poprawić błędy w formularzu !", "", "error");
                    } else {
                        swal("Wystąpił błąd podczas dodawania nowej pozycji !", "", "error");
                    }
                })
            },
            removePosition(position) {
                this.$api.delete(`/api/orders/${this.order.id}/position/${position.id}`).then(res => {
                    if(res.data.success){
                        swal("Pomyślnie usunięto pozycję !", "", "success").then(() => {
                            this.order.positions.splice(this.order.positions.findIndex(p => p.id === position.id), 1);
                        })
                    }
                }).catch(() => {
                    swal("Wystąpił błąd podczas usuwania pozycji !", "", "error");
                })
            },
            saveEditedPosition(data) {
                this.$api.put(`/api/orders/${this.order.id}/position/${data.position.id}`, data.position).then(res => {
                    if(res.data.success && res.data.data.position) {
                        swal("Pomyślnie zaktualizowano pozycję !", "", "success").then(() => {
                            data.done();
                        })
                    }

                    return res;
                }).catch(err => {
                    if(err.response.status !== 422) {
                        return;
                    }

                    this.errorFields = err.response.data.data.fields;

                    if(Object.keys(this.errorFields).length) {
                        swal("Proszę poprawić błędy w formularzu !", "", "error");
                    } else {
                        swal("Wystąpił błąd podczas aktualizacji pozycji !", "", "error");
                    }

                    return err.response;
                }).then(res => {
                    if(res.data.data.position) {
                        this.updateOrderPosition(res.data.data.position)
                    }
                })
            },
            updateOrderPosition(position) {
                const index = this.order.positions.findIndex(p => p.id === position.id);

                if(index >= 0) {
                    this.order.positions[index] = position;
                }
            }
        }
    }
</script>

<style>

</style>
