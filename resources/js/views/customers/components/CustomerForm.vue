<template>
    <div>
        <form @submit.prevent="$emit('submit')">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Dane</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="vehicles-tab" data-toggle="tab" href="#vehicles" role="tab" aria-controls="profile" aria-selected="false">Samochody</a>
                </li>
            </ul>
            <div class="tab-content m-3" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="form-group row">
                        <label for="type" class="col-sm-2 col-form-label">Typ</label>
                        <div class="col-sm-10">
                            <select v-model="customer.type" :class="['form-control', {'is-invalid': fieldHasError('type')}]" id="type">
                                <option v-for="type in availableTypes" :value="type">{{type}}</option>
                            </select>
                            <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('type')"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Imię</label>
                        <div class="col-sm-10">
                            <input v-model="customer.name" type="text" :class="['form-control', {'is-invalid': fieldHasError('name')}]" id="name">
                            <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('name')"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="surname" class="col-sm-2 col-form-label">Nazwisko</label>
                        <div class="col-sm-10">
                            <input v-model="customer.surname" type="text" class="form-control" :class="['form-control', {'is-invalid': fieldHasError('surname')}]" id="surname">
                            <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('surname')"></div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="vehicles" role="tabpanel" aria-labelledby="vehicles-tab">
                    <h3 class="text-center" v-if="!hasVehicles">Brak samochodów</h3>
                    <div class="customers-vehicles" v-if="hasVehicles">
                        <div class="table-responsive">
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
                                <tr v-for="vehicle in customer.vehicles" :key="vehicle.id">
                                    <th scope="row">{{vehicle.id}}</th>
                                    <td>{{vehicle.registration_number}}</td>
                                    <td>{{`${vehicle.mark} ${vehicle.model} (${vehicle.production_year})`}}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger m-1" @click="unassignCustomerVehicle(vehicle)"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <button @click="openCustomerVehiclesModal" type="button" class="btn btn-outline-secondary"><i class="fa fa-plus text-success mr-2" />Dodaj nowy samochód</button>
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
                        <div v-if="customersVehiclesLoading" class="d-flex justify-content-center py-5">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
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
                                <tr v-for="vehicle in vehicles" :key="vehicle.id">
                                    <th scope="row">{{vehicle.id}}</th>
                                    <td>{{vehicle.registration_number}}</td>
                                    <td>{{`${vehicle.mark} ${vehicle.model} (${vehicle.production_year})`}}</td>
                                    <td class="text-center">
                                        <button @click="assignCustomerVehicle(vehicle)" type="button" class="btn btn-outline-secondary m-1"><i class="fa fa-plus text-success mr-2" />Dodaj</button>
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
    export default {
        name: "CustomerForm",
        props: {
            customer: {
                type: Object,
                required: true,
                default: () => {
                    return {
                        type: '',
                        name: '',
                        surname: ''
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
                availableTypes: [
                    'natural_person',
                    'company'
                ],
                customerVehiclesSearchQuery: '',
                customersVehiclesLoading: false
            }
        },
        computed: {
            hasVehicles() {
                return this.customer.vehicles && this.customer.vehicles.length;
            },
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
            vehicles() {
                return this.$store.getters.unassignedCustomerVehicles;
            }
        },
        methods: {
            openCustomerVehiclesModal() {
                $("#customerVehiclesModal").modal("show");

            },
            searchCustomerVehiclesByQuery(e) {
                if(e instanceof KeyboardEvent && e.code !== 'Enter') {
                    return;
                }

                this.customersVehiclesLoading = true;
                this.$api.get("/api/vehicles/getUnassignedVehicles", {params: {query: this.customerVehiclesSearchQuery}}).then(res => {
                    this.$store.commit("setUnassignedCustomerVehicles", res.data.data.vehicles);
                    this.customersVehiclesLoading = false;
                })
            },
            unassignCustomerVehicle(vehicle) {
                this.$emit("unassignCustomerVehicle", vehicle);
            },
            assignCustomerVehicle(vehicle) {
                this.$emit("assignCustomerVehicle", vehicle);
            }
        }
    }
</script>

<style>

</style>
