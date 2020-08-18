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
                @saveNewService="saveNewService"
                @removeService="removeService"
                @saveEditedService="saveEditedService"
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
                    services: []
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
            saveNewService(data) {
                this.$api.post(`/api/orders/${this.order.id}/service`, data.newService).then(res => {
                    if(res.data.success && res.data.data.service) {
                        this.order.services.push(res.data.data.service);
                        swal("Pomyślnie dodano usługę !", "", "success").then(() => {
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
                        swal("Wystąpił błąd podczas dodawania nowej usługi !", "", "error");
                    }
                })
            },
            removeService(service) {
                this.$api.delete(`/api/orders/${this.order.id}/service/${service.id}`).then(res => {
                    if(res.data.success){
                        swal("Pomyślnie usunięto usługę !", "", "success").then(() => {
                            this.order.services.splice(this.order.services.findIndex(s => s.id === service.id), 1);
                        })
                    }
                }).catch(() => {
                    swal("Wystąpił błąd podczas usuwania usługi !", "", "error");
                })
            },
            saveEditedService(data) {
                this.$api.put(`/api/orders/${this.order.id}/service/${data.service.id}`, data.service).then(res => {
                    if(res.data.success && res.data.data.service) {
                        // this.order.services.push(res.data.data.service);
                        swal("Pomyślnie zaktualizowano usługę !", "", "success").then(() => {
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
                        swal("Wystąpił błąd podczas aktualizacji usługi !", "", "error");
                    }

                    return err.response;
                }).then(res => {
                    if(res.data.data.service) {
                        this.updateOrderService(res.data.data.service)
                    }
                })
            },
            updateOrderService(service) {
                const index = this.order.services.findIndex(s => s.id === service.id);

                if(index >= 0) {
                    this.order.services[index] = service;
                    console.log(this.order.services[index], service);
                }
            }
        }
    }
</script>

<style>

</style>
