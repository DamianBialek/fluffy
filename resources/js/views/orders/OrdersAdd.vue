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
        name: "OrdersAdd",
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
        methods: {
            saveOrder() {
                this.errorFields = {};
                this.setLoading(true);
                this.$api.post("/api/orders", this.order)
                    .then(res => {
                        if(res.data.success) {
                            swal("Pomyślnie dodano nowe zlecenie !", "", "success").then(() => {
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
                            swal("Wystąpił błąd podczas dodawania nowego zlecenia !", "", "error");
                        }
                    })
                    .finally(() => {
                        this.setLoading(false);
                    })
            },
            saveNewPosition(data) {
                this.order.positions.push(data.newPosition);
                swal("Pomyślnie dodano pozycję !", "", "success").then(() => {
                    data.done();
                })
            },
            removePosition(position) {
                swal("Pomyślnie usunięto pozycję !", "", "success").then(() => {
                    this.order.positions.splice(this.order.positions.findIndex(p => p.id === position.id), 1);
                })
            },
            saveEditedPosition(data) {
                swal("Pomyślnie zaktualizowano pozycję !", "", "success").then(() => {
                    this.updateOrderPosition(data.position)
                    data.done();
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
