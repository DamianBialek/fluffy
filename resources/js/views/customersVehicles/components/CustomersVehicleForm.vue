<template>
    <form @submit.prevent="$emit('submit')">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="data-tab" data-toggle="tab" href="#data" role="tab" aria-controls="data" aria-selected="true">Dane</a>
            </li>
            <li class="nav-item" v-if="vehicle.id">
                <a class="nav-link" id="orders-tab" data-toggle="tab" href="#orders" role="tab" aria-controls="orders" aria-selected="true">Zlecenia</a>
            </li>
        </ul>
        <div class="tab-content m-3" id="myTabContent">
            <div class="tab-pane fade show active" id="data" role="tabpanel" aria-labelledby="data-tab">
                <div class="form-group row">
                    <label for="mark" class="col-sm-2 col-form-label">Marka</label>
                    <div class="col-sm-10">
                        <input v-model="vehicle.mark" type="text" :class="['form-control', {'is-invalid': fieldHasError('mark')}]" id="mark">
                        <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('mark')"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="model" class="col-sm-2 col-form-label">Model</label>
                    <div class="col-sm-10">
                        <input v-model="vehicle.model" type="text" :class="['form-control', {'is-invalid': fieldHasError('model')}]" id="model">
                        <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('model')"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="registration_number" class="col-sm-2 col-form-label">Numer rejestracyjny</label>
                    <div class="col-sm-10">
                        <input v-model="vehicle.registration_number" type="text" class="form-control" :class="['form-control', {'is-invalid': fieldHasError('registration_number')}]" id="registration_number">
                        <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('registration_number')"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="vin" class="col-sm-2 col-form-label">Numer vin</label>
                    <div class="col-sm-10">
                        <input v-model="vehicle.vin" type="text" :class="['form-control', {'is-invalid': fieldHasError('vin')}]" id="vin">
                        <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('vin')"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="production_year" class="col-sm-2 col-form-label">Rok rejestracji</label>
                    <div class="col-sm-10">
                        <input maxlength="4" minlength="4" v-model="vehicle.production_year" step="1" min="1900" max="9999" type="number" :class="['form-control', {'is-invalid': fieldHasError('production_year')}]" id="production_year">
                        <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('production_year')"></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                <Loading v-if="ordersLoading" />
                <div class="table-responsive mt-3" v-if="!ordersLoading">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th>Numer</th>
                            <th>Status</th>
                            <th>Nazwa</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="order in orders" :key="order.id">
                            <th scope="row">{{order.id}}</th>
                            <th>{{order.number}}</th>
                            <th>{{order.state_name}}</th>
                            <td>{{order.name && order.name.length ? order.name : '---'}}</td>
                            <td class="text-center">
                                <router-link v-tooltip="'Podgląd'" tag="button" :to="{name: 'ordersEdit', params: {id: order.id}}" class="btn btn-outline-secondary m-1"><i class="fas fa-search"></i></router-link>
                                <a v-tooltip="'Zobacz fakturę'" v-if="order.invoice_number" target="_blank" :href="`/orders/${order.id}/invoice`" class="btn btn-outline-secondary ml-2"><i class="far fa-file-pdf"></i></a>
                            </td>
                        </tr>
                        <tr v-if="!orders.length">
                            <td colspan="4" class="text-center"><strong>Brak zleceń</strong></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="text-right">
            <button class="btn btn-primary">Zapisz</button>
        </div>
    </form>
</template>

<script>
    import Loading from "../../../components/Loading";

    export default {
        name: "CustomerVehicleForm",
        components: {
            Loading
        },
        props: {
            vehicle: {
                type: Object,
                required: true,
                default: () => {
                    return {
                        mark: '',
                        model: '',
                        vin: '',
                        registration_number: '',
                        production_year: '',
                        id: null
                    }
                }
            },
            errorFields: {
                type: Object,
                required: false,
                default: () => {}
            }
        },
        data() {
            return {
                orders: [],
                ordersLoading: false
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
            vehicleId() {
                return this.vehicle.id
            }
        },
        methods: {
            loadOrders() {
                this.ordersLoading = true;
                this.$api.get(`/api/vehicles/${this.vehicleId}/orders`).then(res => {
                    this.orders = res.data.data.orders;
                    this.ordersLoading = false;
                })
            }
        },
        watch: {
            vehicleId() {
                this.loadOrders();
            }
        }
    }
</script>

<style scoped>

</style>
