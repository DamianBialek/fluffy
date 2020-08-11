<template>
    <div>
        <form @submit.prevent="$emit('submit')">
            <div class="form-group row">
                <label for="vehicle" class="col-sm-2 col-form-label">Pojazd</label>
                <div class="col-sm-10">
                    <div @click="openCustomerVehiclesModal" class="form-control">{{order.vehicle.mark ? `${order.vehicle.mark} ${order.vehicle.model} (${order.vehicle.registration_number})` : ''}}</div>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label">Nazwa</label>
                <div class="col-sm-10">
                    <input v-model="order.name" type="text" class="form-control" id="name">
                </div>
            </div>

            <div class="text-right">
                <button class="btn btn-primary">Zapisz</button>
            </div>
        </form>
        <div id="customerVehiclesModal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Wybierz samochód</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-8">
                                <div class="input-group">
                                    <input @keyup="searchCustomerVehiclesByQuery" v-model="customerVehiclesSearchQuery" aria-label="Szukaj samochodu" type="text" class="form-control" />
                                    <div class="input-group-append">
                                        <button @click="searchCustomerVehiclesByQuery" type="button" class="btn btn-outline-secondary"><i class="fas fa-search mr-2"></i>Szukaj</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <Loading v-if="customersVehiclesLoading" />
                        <div v-show="!customersVehiclesLoading" class="table-responsive">
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
                                <tr v-for="vehicle in customerVehicles" :key="vehicle.id">
                                    <th scope="row">{{vehicle.id}}</th>
                                    <td>{{vehicle.registration_number}}</td>
                                    <td>{{`${vehicle.mark} ${vehicle.model} (${vehicle.production_year})`}}</td>
                                    <td class="text-center">
                                        <button @click="selectCustomerVehicle(vehicle)" type="button" class="btn btn-outline-secondary m-1"><i class="fa fa-plus text-success mr-2" />Wybierz</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Loading from "../../../components/Loading";

export default {
    name: "OrderForm",
    components: {
        Loading
    },
    data() {
        return {
            customerVehiclesSearchQuery: '',
            customersVehiclesLoading: false
        }
    },
    props: {
        order: {
            type: Object,
            required: true,
            default: () => {
                return {
                    vehicle: {
                        mark: '',
                        model: '',
                        registration_number: ''
                    },
                    name: ''
                }
            }
        },
        errorFields: {
            type: Object,
            required: false,
            default: () => {}
        }
    },
    computed: {
        fieldHasError() {
            return field => this.errorFields.hasOwnProperty(field)
        },
        fieldInvalidFeedback() {
            return field => {
                if(!this.fieldHasError(field)) return '';

                let error = this.errorFields[field];

                if(Array.isArray(error)) {
                    error = error.join("<br />")
                }

                return error;
            }
        },
        customerVehicles() {
            return this.$store.getters.customerVehicles;
        }
    },
    methods: {
        openCustomerVehiclesModal() {
            $("#customerVehiclesModal").modal("show");
            this.loadCustomersVehicles();
        },
        loadCustomersVehicles() {
            this.customersVehiclesLoading = true;
            this.$api.get("/api/vehicles", {params: {query: this.customerVehiclesSearchQuery}}).then(res => {
                this.$store.commit("setCustomerVehicles", res.data.data.vehicles);
                this.customersVehiclesLoading = false;
            })
        },
        searchCustomerVehiclesByQuery(e) {
            if(e instanceof KeyboardEvent && e.code !== 'Enter') {
                return;
            }

            this.loadCustomersVehicles();
        },
        selectCustomerVehicle(vehicle) {
            this.order.vehicle = vehicle;
            this.order.vehicle_id = vehicle.id
            $("#customerVehiclesModal").modal("hide");
        }
    }
}
</script>

<style>

</style>
