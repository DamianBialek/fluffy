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
                @copyPosition="copyPosition"
                @saveEditedPosition="saveEditedPosition"
            />
        </section>
    </div>
</template>

<script>
    import OrderForm from "./components/OrderForm";

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
                            this.$notify("Pomyślnie dodano nowe zlecenie !", "", "success").then(() => {
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
                            this.$notify("Proszę poprawić błędy w formularzu !", "", "error");
                        } else {
                            this.$notify("Wystąpił błąd podczas dodawania nowego zlecenia !", "", "error");
                        }
                    })
                    .finally(() => {
                        this.setLoading(false);
                    })
            },
            saveNewPosition(data) {
                this.order.positions.push(data.newPosition);
                this.$notify("Pomyślnie dodano pozycję !", "", "success").then(() => {
                    data.done();
                })
            },
            removePosition(position) {
                this.$notify("Pomyślnie usunięto pozycję !", "", "success").then(() => {
                    this.order.positions.splice(this.order.positions.findIndex(p => p.id === position.id), 1);
                })
            },
            saveEditedPosition(data) {
                this.$notify("Pomyślnie zaktualizowano pozycję !", "", "success").then(() => {
                    this.updateOrderPosition(data.position)
                    data.done();
                })
            },
            updateOrderPosition(position) {
                if(typeof position.indexInArray != 'undefined') {
                    const index = position.indexInArray;
                    delete position.indexInArray;
                    this.$set(this.order.positions, index, position);
                }
            },
            copyPosition(position) {
                this.order.positions.push(position);
                this.$notify("Pomyślnie skopiowano pozycję !", "", "success")
            }
        }
    }
</script>

<style>

</style>
