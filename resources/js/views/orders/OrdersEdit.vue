<template>
    <div class="page">
        <div class="my-3 text-right">
            <router-link v-tooltip="'Powrót'" tag="button" :to="{name: 'ordersList'}" class="btn btn-info"><i class="fas fa-level-up-alt"></i></router-link>
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
                @generateInvoice="generateInvoice"
            />
        </section>
    </div>
</template>

<script>
    import OrderForm from "./components/OrderForm";

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
                    positions: [],
                    customer: {
                        company: '',
                        name: '',
                        surname: '',
                        address: '',
                        city: '',
                        postcode: '',
                        phone: '',
                    },
                    note: '',
                    responsible_person: null,
                    responsible_person_id: null
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
                            this.$notify("Pomyślnie zaktualizowano dane !", "", "success").then(() => {
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
                            this.$notify("Wystąpił błąd podczas aktualizacji danych !", "", "error");
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
                        this.$notify("Pomyślnie dodano pozycję !", "", "success").then(() => {
                            data.done();
                        })
                    }
                }).catch(err => {
                    if(err.response.status !== 422) {
                        return;
                    }

                    this.errorFields = err.response.data.data.fields;

                    if(Object.keys(this.errorFields).length) {
                        this.$notify("Proszę poprawić błędy w formularzu !", "", "error");
                    } else {
                        this.$notify("Wystąpił błąd podczas dodawania nowej pozycji !", "", "error");
                    }
                })
            },
            removePosition(position) {
                this.$api.delete(`/api/orders/${this.order.id}/position/${position.id}`).then(res => {
                    if(res.data.success){
                        this.$notify("Pomyślnie usunięto pozycję !", "", "success").then(() => {
                            this.order.positions.splice(this.order.positions.findIndex(p => p.id === position.id), 1);
                        })
                    }
                }).catch(() => {
                    this.$notify("Wystąpił błąd podczas usuwania pozycji !", "", "error");
                })
            },
            saveEditedPosition(data) {
                this.$api.put(`/api/orders/${this.order.id}/position/${data.position.id}`, data.position).then(res => {
                    if(res.data.success && res.data.data.position) {
                        this.$notify("Pomyślnie zaktualizowano pozycję !", "", "success").then(() => {
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
                        this.$notify("Proszę poprawić błędy w formularzu !", "", "error");
                    } else {
                        this.$notify("Wystąpił błąd podczas aktualizacji pozycji !", "", "error");
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
                    this.$set(this.order.positions, index, position);
                }
            },
            copyPosition(position) {
                this.$api.post(`/api/orders/${this.order.id}/position`, position).then(res => {
                    if(res.data.success && res.data.data.position) {
                        this.order.positions.push(res.data.data.position);
                        this.$notify("Pomyślnie skopiowano pozycję !", "", "success");
                    }
                }).catch(err => {
                    if(err.response.status !== 422) {
                        return;
                    }

                    this.$notify("Wystąpił błąd podczas kopiowanie pozycji !", "", "error");
                })
            },
            generateInvoice() {
                this.setLoading(true);
                this.$api.post(`/api/orders/${this.$route.params.id}/invoice/generate`).then(res => {
                    this.order.invoice_number = res.data.data.invoice_number;
                    this.setLoading(false);
                })
            }
        }
    }
</script>

<style>

</style>
