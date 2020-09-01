<template>
    <div>
        <form @submit.prevent="$emit('submit')">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Dane</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="positions-tab" data-toggle="tab" href="#positions" role="tab" aria-controls="profile" aria-selected="false">Pozycje zlecenia</a>
                </li>
            </ul>
            <div class="tab-content m-3" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pojazd</label>
                        <div class="col-sm-10">
                            <div @click="openCustomerVehiclesModal" class="form-control">{{order.vehicle && order.vehicle.mark ? `${order.vehicle.mark} ${order.vehicle.model} (${order.vehicle.registration_number})` : ''}}</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Nazwa</label>
                        <div class="col-sm-10">
                            <input v-model="order.name" type="text" class="form-control" id="name">
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="positions" role="tabpanel" aria-labelledby="positions-tab">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Lp</th>
                                <th>Nazwa</th>
                                <th class="text-center">Cena</th>
                                <th class="text-center">Ilość</th>
                                <th class="text-center">Wartość</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="!order.positions.length"><td colspan="5" class="text-center"><strong>Brak usług</strong></td></tr>
                            <tr v-for="(position, index) in order.positions" :key="position.id">
                                <td @click="editPosition(position)" class="align-middle">{{index+1}}</td>
                                <td @click="editPosition(position)" class="align-middle">{{position.name}}</td>
                                <td @click="editPosition(position)" class="text-center align-middle">{{moneyFormat(position.price)}}</td>
                                <td @click="editPosition(position)" class="text-center align-middle">{{position.quantity}}</td>
                                <td @click="editPosition(position)" class="text-center align-middle">{{moneyFormat(positionTotalSum(position))}}</td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-outline-danger m-1" @click="removePosition(position)"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                            <tr class="lead text-dark" v-if="order.positions.length">
                                <td colspan="4" class="text-right"><strong>Razem:</strong></td>
                                <td class="font-weight-bold text-center">{{moneyFormat(orderPositionsTotalSum)}}</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <button @click="createNewPosition" type="button" class="btn btn-outline-secondary"><i class="fa fa-plus text-success mr-2" />Dodaj nową pozycję</button>
                </div>
            </div>


            <div class="text-right">
                <button class="btn btn-primary">Zapisz</button>
            </div>
        </form>
        <div id="editedPositionModal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{editedPositionModalMode === 'create' ? 'Nowa pozycja' : 'Edycja pozycji'}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Nazwa</th>
                                    <th>Cena</th>
                                    <th>Ilość</th>
                                    <th class="text-center">Wartość</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <input aria-label="Nazwa pozycji" :class="['form-control', {'is-invalid': fieldHasError('name')}]" v-model="editedPosition.name" />
                                        <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('name')"></div>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" aria-label="Cena pozycji" :class="['form-control', {'is-invalid': fieldHasError('price')}]" v-model="editedPosition.price" />
                                        <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('price')"></div>
                                    </td>
                                    <td>
                                        <input aria-label="Ilość" :class="['form-control', {'is-invalid': fieldHasError('quantity')}]" v-model="editedPosition.quantity" />
                                        <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('quantity')"></div>
                                    </td>
                                    <td class="text-center">{{moneyFormat(positionTotalSum(editedPosition))}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-2">
                            <button @click="submitEditPositionModal" class="btn btn-primary">Zapisz</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
                    </div>
                </div>
            </div>
        </div>
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
                        <div class="mt-3 pb-3 d-flex justify-content-center">
                            <Pagination :pagination="customersVehiclesPaginationData" @paginate="loadCustomersVehicles()" :offset="2"></Pagination>
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
import Pagination from "../../../components/Pagination";
import { moneyFormat } from "../../../helpers/utils";

export default {
    name: "OrderForm",
    components: {
        Loading,
        Pagination
    },
    data() {
        return {
            customerVehiclesSearchQuery: '',
            customersVehiclesLoading: false,
            customersVehiclesPaginationData: {},
            editedPosition: {
                name: '',
                price: '',
                quantity: 1
            },
            editedPositionModalMode: null
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
                    name: '',
                    positions: []
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
        },
        positionTotalSum() {
            return position => Number(position.price) * position.quantity
        },
        moneyFormat() {
            return value => moneyFormat(value);
        },
        orderPositionsTotalSum() {
            return this.order.positions.reduce((curr, position) => curr + this.positionTotalSum(position), 0);
        }
    },
    mounted() {
        $("#editedPositionModal").on('hidden.bs.modal', () => {
            this.editedPosition = {
                name: '',
                price: '',
                quantity: 1
            };
            this.editedPositionModalMode = null;
        })
    },
    methods: {
        openCustomerVehiclesModal() {
            $("#customerVehiclesModal").modal("show");
            this.loadCustomersVehicles();
        },
        openEditPositionModal() {
            $("#editedPositionModal").modal("show");
        },
        hideEditPositionModal() {
            $("#editedPositionModal").modal("hide");
        },
        loadCustomersVehicles() {
            this.customersVehiclesLoading = true;
            this.$api.get("/api/vehicles", {params: {query: this.customerVehiclesSearchQuery, page: this.customersVehiclesPaginationData.current_page}}).then(res => {
                this.$store.commit("setCustomerVehicles", res.data.data.vehicles);
                this.customersVehiclesLoading = false;
                this.customersVehiclesPaginationData = res.data.data.pagination;
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
        },
        submitEditPositionModal() {
            if(this.editedPositionModalMode === 'create') {
                this.saveNewPosition();
            } else if(this.editedPositionModalMode === 'edit') {
                this.saveEditedPosition();
            }
        },
        saveNewPosition() {
            this.$emit('saveNewPosition', {
                newPosition: this.editedPosition,
                done: () => {
                    this.editedPosition = {
                        name: '',
                        price: '',
                        quantity: 1
                    };
                    this.hideEditPositionModal();
                }
            })
        },
        saveEditedPosition() {
            this.$emit('saveEditedPosition', {
                position: this.editedPosition,
                done: () => {
                    this.editedPosition = {
                        name: '',
                        price: '',
                        quantity: 1
                    };
                    this.hideEditPositionModal();
                }
            })
        },
        removePosition(position) {
            this.$emit('removePosition', position);
        },
        editPosition(position) {
            this.editedPosition = Object.assign({}, position);
            this.editedPositionModalMode = 'edit';
            this.openEditPositionModal();
        },
        createNewPosition() {
            this.editedPositionModalMode = 'create';
            this.openEditPositionModal();
        }
    }
}
</script>

<style>

</style>
